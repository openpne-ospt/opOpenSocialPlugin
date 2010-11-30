<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setMemberId(1);

$t = new lime_test(8, new lime_output_color());

function get_test_member_application()
{
  $member = Doctrine::getTable('Member')->findOneByName('A');
  $app    = Doctrine::getTable('Application')->findOneByUrl('http://example.com/dummy.xml');
  return Doctrine::getTable('MemberApplication')->findOneByMemberIdAndApplicationId($member->id, $app->id);
}

$conn = Doctrine::getTable('MemberApplication')->getConnection();
$conn->beginTransaction();

Doctrine::getTable('Gadget')->clearGadgetsCache();
$form = new ApplicationSettingForm(array(), array('member_application' => get_test_member_application()), false);

$t->is(count($form), 3, 'ApplicationSettingForm has 3 fields.');

$form->bind(array(
  'public_flag'     => 'private',
  'is_view_home'    => true,
  'is_view_profile' => true,
));

$t->ok($form->isValid(), 'ApplicationSettingForm accepts valid form parameters.');

$form->save();

$memberApplication = get_test_member_application();

$t->is($memberApplication->getPublicFlag(), 'private',
  'member_application.public_flag has saved by ApplicationSettingForm::save().');

$t->ok($memberApplication->getApplicationSetting('is_view_home'),
  'The is_view_home of member_application configuration was saved by ApplicationSettingForm::save().');

$t->ok($memberApplication->getApplicationSetting('is_view_profile'),
  'The is_view_profile of member_application configuration was saved by ApplicationSettingForm::save().');

$form->bind(array(
  'public_flag'     => '',
  'is_view_home'    => '',
  'is_view_profile' => '',
));

$t->ok(!$form->isValid(), "ApplicationSettingForm doesn't accept invalid form parameters");

$msg = 'ApplicationSettingForm throws sfValidatorError';
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

$msg = 'ApplicationSettingForm throws LogicException'; try
{
  $form = new ApplicationSettingForm();
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
