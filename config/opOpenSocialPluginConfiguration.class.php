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

    $this->dispatcher->connect('op_confirmation.list', array($this, 'getConfirmList'));
    $this->dispatcher->connect('op_confirmation.decision', array($this, 'processConfirm'));
    $this->dispatcher->connect('op_opensocial.addapp', array($this, 'processAddAppEvent'));
    $this->dispatcher->connect('op_opensocial.removeapp', array($this, 'processRemoveAppEvent'));
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

  public function processAddAppEvent(sfEvent $event)
  {
    $params = array(
      'id' => $event['memberApplication']->getMemberId(),
      'from' => 'gallery',
    );

    $reason = $event['reason'];
    if (is_array($reason) && isset($reason['invite']))
    {
      $params['from'] = 'invite';
      $params['invite_from'] = $reason['invite'];
    }

    return $this->processEvent($event, 'addapp', $params);
  }

  public function processRemoveAppEvent(sfEvent $event)
  {
    $params = array(
      'id' => $event['memberApplication']->getMemberId(),
    );

    return $this->processEvent($event, 'removeapp', $params);
  }

 /**
  * process lifecycle event
  *
  * @param sfEvent $event
  * @param string  $eventType
  * @param array   $params
  * @return boolean
  */
  protected function processEvent(sfEvent $event, $eventType, $params = array())
  {
    $eventType = 'event.'.$eventType;
    $params['eventtype'] = $eventType;
    $links = $event['memberApplication']->getApplication()->getLinks();

    if (!is_array($links))
    {
      return false;
    }

    foreach ($links as $link)
    {
      if (isset($link['rel']) && $link['rel'] === $eventType && isset($link['href']))
      {
        $memberApplicationId = $event['memberApplication']->getId();
        $applicationId = $event['memberApplication']->getApplicationId();

        $queue = Doctrine::getTable('ApplicationLifecycleEventQueue')
          ->findOneByApplicationIdAndMemberIdAndName($applicationId, $params['id'], $eventType);
        if (!$queue)
        {
          $queue = new ApplicationLifecycleEventQueue();
          $queue->setApplicationId($applicationId);

          if ('event.removeapp' === $eventType)
          {
            $delqueue = Doctrine::getTable('ApplicationLifecycleEventQueue')
              ->findOneByApplicationIdAndMemberIdAndName($applicationId, $params['id'], 'event.addapp');
            if ($delqueue)
            {
              $delqueue->delete();
              return true;
            }
          }

          $queue->setMemberId($params['id']);
          $queue->setName($eventType);
          $queue->setParams($params);
          $queue->save();
          return true;
        }
      }
    }

    return false;
  }
}
