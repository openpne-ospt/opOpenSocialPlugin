<?php

/**
 * opJsonDbOpensocialService
 *
 */

class opJsonDbOpensocialService implements ActivityService, PersonService, AppDataService, MessagesService
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
    $first = $options->getStartIndex();
    $max   = $options->getCount();
    $ret = array();
    $criteria = new Criteria();
    $criteria->add(MemberPeer::ID, $ids, Criteria::IN);
    $totalSize = MemberPeer::doCount($criteria);
    $criteria->addAscendingOrderByColumn(MemberPeer::ID);
    if ($first !== false && $max !== false && is_numeric($first) && is_numeric($max) && $first >= 0 && $max > 0)
    {
      $criteria->setOffset($first);
      $criteria->setLimit($max);
    }
    $members = MemberPeer::doSelect($criteria);
    $people = array();
    foreach ($members as $member)
    {
      $p = array();
      $p['isOwner']    =  (!$token->isAnonymous() && $member->getId() == $token->getOwnerId()) ? true : false;
      $p['isViewer']   =  (!$token->isAnonymous() && $member->getId() == $token->getViewerId()) ? true : false;
      $p['displayName'] = $member->getName();
      $people[] = $p;
    }
    $collection = new RestfulCollection($people, $options->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($options->getCount());
    return $collection;
  }

  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $startIndex, $count, $fields, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getActivity($userId, $groupId, $appdId, $fields, $activityId, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getPersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    $ids = $this->getIdSet($userId, $groupId, $token);
    $criteria = new Criteria();
    $criteria->add(ApplicationSettingPeer::APPLICATION_ID, $appId);
    $criteria->add(ApplicationSettingPeer::MEMBER_ID, $ids, Criteria::IN);
    $app_settings = ApplicationSettingPeer::doSelect($criteria);
    if (!count($app_settings))
    {
      throw new SocialSpiException("UnKnown person app data key(s): ".implode(', ', $fields));
    }
    $data = array();
    foreach($app_settings as $app_setting)
    {
      $data[$app_setting->getMemberId()][$app_setting->getName()] = $app_setting->getValue();
    }
    return new DataCollection($data);
  }

  public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    foreach ($fields as $key)
    {
      if (!preg_match('/[\w\-\.]+/',$key))
      {
        throw new SocialSpiException("The person app data key had in valid characters", ResponseError::$BAD_REQUEST);
      }
    }
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createMessage($userId, $appId, $message, $optionalMessageId, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  private function getIdSet($user, GroupId $group, SecurityToken $token)
  {
    $ids = array();
    if ($user instanceof UserId)
    {
      $userId = $user->getUserId($token);
      if ($group == null)
      {
        return array($userId);
      }
      switch ($group->getType())
      {
        case 'all':
        case 'friends':
        case 'groupId':

          $criteria = new Criteria();
          $criteria->add(MemberRelationshipPeer::MEMBER_ID_FROM,$userId);
          $criteria->add(MemberRelationshipPeer::IS_FRIEND, true);
          $friends = MemberRelationshipPeer::doSelect($criteria);
          
          $friendIds = array();
          foreach ($friends as $friend)
          {
            $friendIds[] = $friend->getMemberIdTo();
          }
          if (count($friendIds))
          {
            $ids = $friendIds;
          }
          break;
        case 'self':
          $ids[] = $userId;
          break;
      }
    }
    elseif (is_array($user))
    {
      $ids = array();
      foreach ($user as $id)
      {
        $ids = array_merge($ids, $this->getIdSet($id, $group, $token));
      }
    }
    return $ids;
  }
}
