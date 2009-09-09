<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');

$t = new lime_test(2, new lime_output_color());

$application = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$member = Doctrine::getTable('Member')->find(2);

$table = Doctrine::getTable('memberApplication');

// ->findOneByApplicationAndMember()
$t->diag('->findOneByApplicationAndMember()');
$memberApplication = $table->findOneByApplicationAndMember($application, $member);
$t->isa_ok($memberApplication, 'MemberApplication', '->findOneByApplicationAndMember() return the MemberApplication object');

// ->getMemberApplications()
$t->diag('->getMemberApplications()');
$t->isa_ok($table->getMemberApplications(2, 2), 'Doctrine_Collection', '->getMemberApplications() return the Doctrine_Collection object');
