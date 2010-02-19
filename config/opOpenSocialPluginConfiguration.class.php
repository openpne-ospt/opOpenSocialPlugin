<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialPluginConfiguration
 *
 * @package    opOpenSocialPlugin
 * @subpackage config
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    sfToolkit::addIncludePath(array(
      $this->rootDir.'/lib/vendor/Shindig/',
    ));

    $this->dispatcher->connect('op_confirmation.list', array(__CLASS__, 'getConfirmList'));
    $this->dispatcher->connect('op_confirmation.decision', array(__CLASS__, 'processConfirm'));
  }

  public function getConfirmList(sfEvent $event)
  {
    if ('invitation_app' == $event['category'])
    {
      return Doctrine::getTable('ApplicationInvite')->inviteApplicationList($event);
    }
  }

  public function processConfirm(sfEvent $event)
  {
    if ('invitation_app' == $event['category'])
    {
      return Doctrine::getTable('ApplicationInvite')->processApplicationConfirm($event);
    }
  }
}
