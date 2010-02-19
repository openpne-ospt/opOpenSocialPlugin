<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginApplicationInviteTable
 * 
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class PluginApplicationInviteTable extends Doctrine_Table
{
  public function inviteApplicationList(sfEvent $event)
  {
    $invites = Doctrine::getTable('ApplicationInvite')->findByToMemberId($event['member']->id);
    $list = array();
    foreach ($invites as $invite)
    {
      $application = $invite->getApplication();
      $fromMember = $invite->getFromMember();
      $list[] = array(
        'id' => $invite->getId(),
        'image' => array(
          'url' => $fromMember->getImageFileName(),
          'link' => '@obj_member_profile?id='.$fromMember->getId(),
        ),
        'list' => array(
          'App name' => array(
            'text' => $application->getTitle(),
            'link' => '@application_info?id='.$application->getId()
          ),
          'Member who invited this' => array(
            'text' => $fromMember->getName(),
            'link' => '@obj_member_profile?id='.$fromMember->getId()
          )
        ),
      );
    }

    $event->setReturnValue($list);

    return true;
  }

  public function processApplicationConfirm(sfEvent $event)
  {
    $invite = Doctrine::getTable('ApplicationInvite')->find($event['id']);
    if (!$invite)
    {
      return false;
    }

    if ($event['is_accepted'])
    {
      $memberApplication = $invite->accept();
      $invite->delete();
      $action = $event->getSubject();
      if ($action instanceof sfAction)
      {
        $action->redirect('@application_canvas?id='.$memberApplication->getId());
      }
    }
    else
    {
      $invite->delete();
      $event->setReturnValue("You have just rejected request of invitation to app.");
    }

    return true;
  }
}
