<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');

$t = new lime_test(8, new lime_output_color());

$application = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$member = Doctrine::getTable('Member')->find(1);
$memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application, $member);

// ->getApplicationSettings()
$t->diag('->getApplicationSettings()');
$applicationSettings = $memberApplication->getApplicationSettings();
$t->ok(is_array($applicationSettings) && count($applicationSettings) === 2);

// ->getApplicationSetting()
$t->diag('->getApplicationSetting()');
$value1 = $memberApplication->getApplicationSetting('is_view_home', 'default');
$t->is($value1, '1');
$value2 = $memberApplication->getApplicationSetting('tetete', 'default');
$t->is($value2, 'default');

// ->setApplicationSetting()
$t->diag('->setApplicationSetting()');
$memberApplication->setApplicationSetting('tetete', 'tetete');
$value3 = $memberApplication->getApplicationSetting('tetete', 'default');
$t->is($value3, 'tetete');

// ->getUserSettings()
$t->diag('->getUserSettings()');
$userSettings = $memberApplication->getUserSettings();
$t->ok(is_array($userSettings) && count($userSettings) === 1);

// ->getUserSetting()
$t->diag('->getUserSetting()');
$value4 = $memberApplication->getUserSetting('user_setting', 'default');
$t->is($value4, '1');
$value5 = $memberApplication->getUserSetting('tetete', 'default');
$t->is($value5, 'default');

// ->setUserSetting()
$t->diag('->setUserSetting()');
$memberApplication->setUserSetting('tetete', 'tetete');
$value6 = $memberApplication->getUserSetting('tetete', 'default');
$t->is($value6, 'tetete');
