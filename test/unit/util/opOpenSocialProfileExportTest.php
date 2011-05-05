<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(59, new lime_output_color());

$conn = Doctrine::getTable('MemberProfile')->getConnection();
$conn->beginTransaction();

$checkBirthday1 = '1989-01-08';
$checkBirthday2 = '0000-01-08';
$checkAge = (int)(((int)date('Ymd') - (int)date('Ymd', strtotime($checkBirthday1))) / 10000);

function test_birthday_public_flag($class, $birthday_pf, $age_pf, $birthday, $age)
{
  global $t;

  $memberProfile = $class->member->getProfile('op_preset_birthday');
  $memberProfile->setPublicFlag($birthday_pf);
  $memberProfile->save();
  $class->member->setConfig('age_public_flag', $age_pf);

  $msg = sprintf('->getBirthday() returns \'%s\' when public flag of birthday is \'%s\' and public flag of age is \'%s\'',
  $birthday, $birthday_pf, $age_pf);
  $t->is($class->getBirthday(), $birthday, $msg);

  $msg = sprintf('->getAge() returns \'%s\' when public flag of birthday is \'%s\' and public flag of age is \'%s\'',
  $age, $birthday_pf, $age_pf);
  $t->is($class->getAge(), $age, $msg);
}

$t->info('1 - Test when Owner is Viewer');
$class1 = new opOpenSocialProfileExport();
$class1->member = Doctrine::getTable('Member')->find(1);
$class1->setViewer(Doctrine::getTable('Member')->find(1));

// basic test A
$t->info('->getData()');
$datas = $class1->getData();
$t->is(count($datas), 3, '->getData() returns array with 3 items');

$datas = $class1->getData(array('nickname', 'languagesSpoken', 'addresses'));
$t->is(count($datas), 5, '->getData() returns array with 5 items');

$t->info('->__call()');
$msg = '->__call() throws BadMethodCallException';
try
{
  $class1->getXXX();
  $t->fail($msg);
}
catch (BadMethodCallException $e)
{
  $t->pass($msg);
}

// birthday test I
$t->info('->getBirthday() and ->getAge()');
test_birthday_public_flag($class1, 1, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 1, 2, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 1, 3, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 2, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 2, 2, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 2, 3, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 3, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 3, 2, $checkBirthday1, $checkAge);
test_birthday_public_flag($class1, 3, 3, $checkBirthday1, $checkAge);

$t->info("2 - Test when Owner is a Viewer's friend");
$class2 = new opOpenSocialProfileExport();
$class2->member = Doctrine::getTable('Member')->find(2);
$class2->setViewer(Doctrine::getTable('Member')->find(1));

// basic test B
$datas = $class2->getData();
$t->is(count($datas), 3, '->getData() returns array with 3 items');

// birthday test II
$t->info("->getBirthday() and ->getAge() Test (Viewer is Owner's friend)");
test_birthday_public_flag($class2, 1, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class2, 1, 2, $checkBirthday1, $checkAge);
test_birthday_public_flag($class2, 1, 3, $checkBirthday2, '');
test_birthday_public_flag($class2, 2, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class2, 2, 2, $checkBirthday1, $checkAge);
test_birthday_public_flag($class2, 2, 3, $checkBirthday2, '');
test_birthday_public_flag($class2, 3, 1, '', $checkAge);
test_birthday_public_flag($class2, 3, 2, '', $checkAge);
test_birthday_public_flag($class2, 3, 3, '', '');

$t->info("3 - Test when Owner isn't Viewer's friend");
$class3 = new opOpenSocialProfileExport();
$class3->member = Doctrine::getTable('Member')->find(3);
$class3->setViewer(Doctrine::getTable('Member')->find(1));

// basic test C
$datas = $class3->getData();
$t->is(count($datas), 3, '->getData() returns array with 3 items');

// birthday test III
$t->info("->getBirthday() and ->getAge() Test (Viwer is not Owner's friend)");
test_birthday_public_flag($class3, 1, 1, $checkBirthday1, $checkAge);
test_birthday_public_flag($class3, 1, 2, $checkBirthday2, '');
test_birthday_public_flag($class3, 1, 3, $checkBirthday2, '');
test_birthday_public_flag($class3, 2, 1, '', $checkAge);
test_birthday_public_flag($class3, 2, 2, '', '');
test_birthday_public_flag($class3, 2, 3, '', '');
test_birthday_public_flag($class3, 3, 1, '', $checkAge);
test_birthday_public_flag($class3, 3, 2, '', '');
test_birthday_public_flag($class3, 3, 3, '', '');

$conn->rollback();
