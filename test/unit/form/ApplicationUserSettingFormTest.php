<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(9, new lime_output_color());

function get_test_member_application()
{
  $member = Doctrine::getTable('Member')->findOneByName('A');
  $app    = Doctrine::getTable('Application')->findOneByUrl('http://example.com/dummy.xml');
  return Doctrine::getTable('MemberApplication')->findOneByMemberIdAndApplicationId($member->id, $app->id);
}

$conn = Doctrine::getTable('MemberApplication')->getConnection();
$conn->beginTransaction();

$form = new ApplicationUserSettingForm(array(), array('member_application' => get_test_member_application()), false);

$t->is(count($form), 3, 'ApplicationUserSettingForm has 3 fields.');

$form->bind(array(
  's1' => 'o1',
  's2' => true,
  's3' => 'TEST',
));

$t->ok($form->isValid(), 'ApplicationUserSettingForm accepts valid form parameters.');

$form->save();

$memberApplication = get_test_member_application();

$t->is($memberApplication->getUserSetting('s1'), 'o1',
  'The "s1" of member_application configuration was saved by ApplicationUserSettingForm::save().');

$t->ok($memberApplication->getUserSetting('s2'),
  'The "s2" of member_application configuration was saved by ApplicationUserSettingForm::save().');

$t->is($memberApplication->getUserSetting('s3'), 'TEST',
  'The "s3" of member_application configuration was saved by ApplicationUserSettingForm::save().');


$form->bind(array(
  's1' => 'o1',
  's2' => true,
  's3' => '',
));

$t->ok($form->isValid(), "ApplicationUserSettingForm accepts valid form parameters.");

$form->bind(array(
  's1' => 'o1',
  's2' => '',
  's3' => '',
));

$t->ok(!$form->isValid(), "ApplicationUserSettingForm doesn't accept invalid form parameters.");

$msg = "ApplicationUserSettingForm throws sfValidatorError.";
try
{
  $form->save();
  $t->fail($msg);
}
catch (Exception $e)
{
  if ($e instanceof sfValidatorError)
  {
    $t->pass($msg);
  }
  else
  {
    $t->fail($msg);
  }
}

$msg = 'ApplicationUserSettingForm throws LogicException';
try
{
  $form = new ApplicationUserSettingForm();
  $t->fail($msg);
}
catch(Exception $e)
{
  if ($e instanceof LogicException)
  {
    $t->pass($msg);
  }
  else
  {
    $t->fail($msg);
  }
}

$conn->rollback();
