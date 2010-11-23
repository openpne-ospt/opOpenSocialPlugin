<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(16, new lime_output_color());

$table = Doctrine::getTable('ApplicationInvite');

$conn = $table->getConnection();
$conn->beginTransaction();

$member2 = Doctrine::getTable('Member')->find(2);

// ->getInvitesByToMemberId()
$result = $table->getInvitesByToMemberId();
$t->isa_ok($result, 'Doctrine_Collection', '->getInvitesByToMemberId() return object of Doctrine_Collection');
$t->is(count($result), 0, '->getInvitesByToMemberId() return empty Doctrine_Collection');

$result = $table->getInvitesByToMemberId($member2->getId());
$t->isa_ok($result, 'Doctrine_Collection', '->getInvitesByToMemberId() return object of Doctrine_Collection');
$t->is(count($result), 1, '->getInvitesByToMemberId() return 1 object');

$result = $table->getInvitesByToMemberId($member2->getId(), true, false);
$t->isa_ok($result, 'Doctrine_Collection', '->getInvitesByToMemberId() return object of Doctrine_Collection');
$t->is(count($result), 1, '->getInvitesByToMemberId() return 1 object');

$result = $table->getInvitesByToMemberId($member2->getId(), true, true);
$t->isa_ok($result, 'Doctrine_Collection', '->getInvitesByToMemberId() return object of Doctrine_Collection');
$t->is(count($result), 0, '->getInvitesByToMemberId() return empty Doctrine_Collection');

// ->inviteApplicationList()
$event = new sfEvent(null, 'name', array(
  'member' => $member2,
));
$t->ok($table->inviteApplicationList($event), '->inviteApplicationList() returns true');
$t->is(count($event->getReturnValue()), 1, '->inviteApplicationList() return list of one members by sfEvent');

// ->processApplicationConfirm()
$invite = $table->find(1);
$t->ok($invite, 'invite object is exists');
$event = new sfEvent(null, 'name', array(
  'id' => 1,
  'is_accepted' => true,
));
$t->ok($table->processApplicationConfirm($event), '->processApplicationConfirm() returns true');

// create new invite object
$invite = new ApplicationInvite();
$invite->setApplicationId(3);
$invite->setToMemberId(2);
$invite->setFromMemberId(1);
$invite->save();

$event = new sfEvent(null, 'name', array(
  'id' => $invite->id,
  'is_accepted' => false,
));
$t->ok($table->processApplicationConfirm($event), '->processApplicationConfirm() returns true');
$invite = $table->find($invite->id);
$t->ok(!$invite, 'invite object was deleted');
$memberApplication = Doctrine::getTable('MemberApplication')
  ->findOneByMemberIdAndApplicationId(2, 3);
$t->ok(!$memberApplication, 'don\'t create member application object by ->processApplicationConfirm()');

$event = new sfEvent(null, 'name', array(
  'id' => 999,
  'is_accepted' => true,
));
$t->ok(!$table->processApplicationConfirm($event), '->processApplicationConfirm() returns false');

$conn->rollback();
