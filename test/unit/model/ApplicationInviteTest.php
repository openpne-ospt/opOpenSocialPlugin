<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(1, new lime_output_color());

$conn = Doctrine::getTable('ApplicationInvite')->getConnection();
$conn->beginTransaction();

$invite = Doctrine::getTable('ApplicationInvite')->find(1);
$memberApplication = $invite->accept();
$t->isa_ok($memberApplication, 'MemberApplication', '->accept() returns object of MemberApplication');

$conn->rollback();
