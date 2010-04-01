<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());

$application = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$member = Doctrine::getTable('Member')->find(1);

$table = Doctrine::getTable('memberApplication');

// ->findOneByApplicationAndMember()
$t->diag('->findOneByApplicationAndMember()');
$memberApplication = $table->findOneByApplicationAndMember($application, $member);
$t->isa_ok($memberApplication, 'MemberApplication', '->findOneByApplicationAndMember() return the MemberApplication object');

// ->getMemberApplications()
$t->diag('->getMemberApplications()');
$memberApplications = $table->getMemberApplications($member->id, $member->id);
$t->isa_ok($memberApplications, 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
$t->is($memberApplications->count(), 2, '->getMemberApplications() return 2 records');

// ->getInstalledFriendIds()
$t->diag('->getInstalledFriendIds()');
$ids = $table->getInstalledFriendIds($application, $member);
$t->is(array(2 => '2'), $ids, '->getInstalledFriendIds() return installed friend ids');
