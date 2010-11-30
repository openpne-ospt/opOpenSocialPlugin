<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(15, new lime_output_color());

$form = new ApplicationSearchForm();

//-----------------------------

$form->bind(array(
  'keyword'  => '',
  'order_by' => ''
));

$t->ok($form->isValid(), 'ApplicationSearchForm accepts valid form parameters');

$pager = $form->getPager();
$t->isa_ok($pager, 'sfDoctrinePager', 'ApplicationSearchForm::getPager() returns a sfDoctrinePager object');
$t->is($pager->getNbResults(), 4, '$pager->getNbResults() returns 4');

$pager = $form->getPager(1, 20, true, true);
$t->isa_ok($pager, 'sfDoctrinePager', 'ApplicationSearchForm::getPager() returns a sfDoctrinePager object');
$t->is($pager->getNbResults(), 3, '$pager->getNbResults() returns 3');

$pager = $form->getPager(1, 20, true, null, true);
$t->isa_ok($pager, 'sfDoctrinePager', 'ApplicationSearchForm::getPager() returns a sfDoctrinePager object');
$t->is($pager->getNbResults(), 1, '$pager->getNbResults() returns 1');

//-----------------------------

$form->bind(array(
  'keyword'  => 'Test 2',
  'order_by' => ''
));

$t->ok($form->isValid(), 'ApplicationSearchForm accepts valid form parameters');

$pager = $form->getPager(1, 20, true, true, null);
$t->isa_ok($pager, 'sfDoctrinePager', 'ApplicationSearchForm::getPager() returns a sfDoctrinePager object');
$t->is($pager->getNbResults(), 1, '$pager->getNbResults() returns 1');

//-----------------------------

$form->bind(array(
  'keyword'  => '',
  'order_by' => 'users_desc'
));

$t->ok($form->isValid(), 'ApplicationSearchForm accepts valid form parameters');

$pager = $form->getPager(1, 20, true, true);
$t->isa_ok($pager, 'sfDoctrinePager', 'ApplicationSearchForm::getPager() returns a sfDoctrinePager object');
$t->is($pager->getNbResults(), 3, '$pager->getNbResults() returns 3');

//-----------------------------

$form->bind(array(
  'invalid'  => '',
));

$t->ok(!$form->isValid(), "ApplicationSearchForm doesn't accept invalid form parameters");

$msg = 'ApplicationSearchForm::getPager() throws sfValidatorErrorSchema';
try
{
  $form->getPager();
  $t->fail($msg);
}
catch (Exception $e)
{
  if ($e instanceof sfValidatorErrorSchema)
  {
    $t->pass($msg);
  }
  else
  {
    $t->fail($msg);
  }
}
