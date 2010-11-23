<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

$testRevision = 1;

$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
new sfDatabaseManager($configuration);

try
{
  if ($testRevision > (int)Doctrine::getTable('SnsConfig')->get('opOpenSocialPlugin_test_revision'))
  {
    throw new Exception();
  }
}
catch (Exception $e)
{
  if (class_exists('sfDoctrineBuildTask'))
  {
    // for OpenPNE 3.3.x <=
    $task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
    $task->setConfiguration($configuration);
    $task->run(array(), array(
      'no-confirmation' => true,
      'db'              => true,
      'and-load'        => dirname(__FILE__).'/../fixtures',
    ));
  }
  else
  {
    // for OpenPNE 3.2.x >=
    $task = new sfDoctrineBuildAllReloadTask($configuration->getEventDispatcher(), new sfFormatter());
    $task->run(array('--no-confirmation', '--dir='.dirname(__FILE__).'/../fixtures', '--skip-forms'));
  }

  $snsConfig = Doctrine::getTable('SnsConfig');
  $snsConfig->set('opOpenSocialPlugin_test_revision', $testRevision);
  $snsConfig->set('shindig_token_cipher_key', 'TEST_KEY');
  $snsConfig->set('shindig_token_hmac_key', 'TEST_KEY');
  $snsConfig->set('add_application_rule', 2); // ALLOW
  $snsConfig->set('opensocial_is_enable_mobile', 1);
  $snsConfig->set('is_check_mobile_ip', 0);
}
