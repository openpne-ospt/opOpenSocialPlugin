<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialPersonService
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialPersonService extends opOpenSocialServiceBase implements PersonService
{
  public function getPerson($userId, $groupId, $fields, SecurityToken $token)
  {
    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }
    $person = $this->getPeople($userId, $groupId, new CollectionOptions(), $fields, $token);
    if (is_array($person->getEntry()))
    {
      $person = $person->getEntry();
      if (is_array($person) && count($person) == 1)
      {
        return array_pop($person);
      }
    }
    throw new SocialSpiException("Person not found", ResponseError::$BAD_REQUEST);
  }

  public function getPeople($userId, $groupId, CollectionOptions $options, $fields, SecurityToken $token)
  {
    $ids = $this->getIdSet($userId, $groupId, $token);
    $first = $this->fixStartIndex($options->getStartIndex());
    $max   = $this->fixCount($options->getCount());
    $ret = array();

    $members = array();

    if (count($ids))
    {
      if (CollectionOptions::HAS_APP_FILTER === $options->getFilterBy() && $token->getAppId())
      {
        $memberApplications = Doctrine::getTable('MemberApplication')->createQuery()
          ->where('application_id = ?', $token->getAppId())
          ->execute();
        if (count($memberApplications))
        {
          $ids = array_intersect($ids, $memberApplications->toKeyValueArray('id', 'member_id'));
        }
        else
        {
          $ids = array();
        }
      }
    }

    if (count($ids))
    {
      $query = Doctrine::getTable('Member')->createQuery()->whereIn('id', $ids);

      $totalSize = $query->count();

      $query->orderBy('id');
      $query->offset($first);
      $query->limit($max);

      $members = $query->execute();
    }

    $people = array();
    $viewer = (!$token->isAnonymous()) ? Doctrine::getTable('Member')->find($token->getViewerId()) : null;
    $application = ($token->getAppId()) ? Doctrine::getTable('Application')->find($token->getAppId()): null;

    $export = new opOpenSocialProfileExport();
    $export->setViewer($viewer);

    foreach ($members as $member)
    {
      $p = array();
      $p['id']       =  $member->getId();
      $p['isOwner']  =  (!$token->isAnonymous() && $member->getId() == $token->getOwnerId()) ? true : false;
      $p['isViewer'] =  (!$token->isAnonymous() && $member->getId() == $token->getViewerId()) ? true : false;
      if ($application)
      {
        $p['hasApp'] = $application->isHadByMember($member->getId());
      }
      $export->member = $member;
      $people[] = $p + $export->getData($fields);
    }

    $collection = new RestfulCollection($people, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }
}

