<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(11, new lime_output_color());

$table = Doctrine::getTable('MemberApplication');

$conn = $table->getConnection();
$conn->beginTransaction();

$application = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$member = Doctrine::getTable('Member')->find(1);

// ->findOneByApplicationAndMember()
$t->diag('->findOneByApplicationAndMember()');
$memberApplication = $table->findOneByApplicationAndMember($application, $member);
$t->isa_ok($memberApplication, 'MemberApplication', '->findOneByApplicationAndMember() return the MemberApplication object');

// ->getMemberApplications()
$t->diag('->getMemberApplications()');
$memberApplications = $table->getMemberApplications();
$t->isa_ok($memberApplications, 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
$t->is($memberApplications->count(), 2, '->getMemberApplications() return 2 records');

$memberApplications = $table->getMemberApplications($member->id, $member->id);
$t->isa_ok($memberApplications, 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
$t->is($memberApplications->count(), 2, '->getMemberApplications() return 2 records');

$memberApplications = $table->getMemberApplications(null, null, false, true, false);
$t->isa_ok($memberApplications, 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
$t->is($memberApplications->count(), 3, '->getMemberApplications() return 2 records');

$memberApplications = $table->getMemberApplications(null, null, false, true, true);
$t->isa_ok($memberApplications, 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
$t->is($memberApplications->count(), 0, '->getMemberApplications() return empty set');

// ->getMemberApplicationListPager()
$t->diag('->getMemberApplicationListPager()');
$pager = $table->getMemberApplicationListPager(1, 20, $member->id, $member->id);
$t->isa_ok($pager, 'sfDoctrinePager', '->getMemberApplications() return the sfDoctrinePager object');

// ->getInstalledFriendIds()
$t->diag('->getInstalledFriendIds()');
$ids = $table->getInstalledFriendIds($application, $member);
$t->is(array(2 => '2'), $ids, '->getInstalledFriendIds() return installed friend ids');

$conn->rollback();
