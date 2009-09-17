<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opJsonDbOpensocialService
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
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

    $members = array();
    if (count($ids))
    {
      $query = Doctrine::getTable('Member')->createQuery()->whereIn('id', $ids);

      $totalSize = $query->count();

      $query->orderBy('id');
      if ($first !== false && $max !== false && is_numeric($first) && is_numeric($max) && $first >= 0 && $max > 0)
      {
        $query->offset($first);
        $query->limit($max);
      }
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
      $p['isOwner']  =  (!$token->isAnonymous() && $member->getId() == $token->getOwnerId()) ? true : false;
      $p['isViewer'] =  (!$token->isAnonymous() && $member->getId() == $token->getViewerId()) ? true : false;
      if ($application)
      {
        $p['hasApp'] = $application->isHadByMember($member->getId());
      }
      $export->member = $member;
      $people[] = $p + $export->getData($fields);
    }

    $collection = new RestfulCollection($people, $options->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($options->getCount());
    return $collection;
  }

  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds ,$token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getActivity($userId, $groupId, $appId, $fields, $activityId, SecurityToken $token)
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
    if (!($userId instanceof UserId))
    {
      throw new SocialSpiException("Not support request", ResponseError::$NOT_IMPLEMENTED);
    }
    $targetUserId = (int)$userId->getUserId($token);
    $viewerId     = (int)$token->getViewerId();
    if ($targetUserId != $viewerId)
    {
      $memberRelationship = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($targetUserId, $viewerId);
      if (!($memberRelationship && $memberRelationship->isFriend()))
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    if ($groupId->getType() == 'self')
    {
      $persistentDatas = $application->getPersistentDatas($targetUserId, $fields);
    }
    else if($groupId->getType() == 'friends')
    {
      $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($targetUserId);
      $persistentDatas = $application->getPersistentDatas($friendIds, $fields);
    }
    else
    {
      throw new SocialSpiException("We support getting data only when GROUP_ID is SELF or FRIENDS ", ResponseError::$NOT_IMPLEMENTED);
    }
    $data = array();
    if ($persistentDatas)
    {
      foreach ($persistentDatas as $persistentData)
      {
        $data[$persistentData->getMemberId()][$persistentData->getName()] = $persistentData->getValue();
      }
    }
    return new DataCollection($data);
  }

  public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    if (!($userId instanceof UserId) || $userId->getUserId($token) == null )
    {
      throw new SocialSpiException("Unknown person id", ResponseError::$NOT_FOUND);
    }

    $targetUserId = $userId->getUserId($token);
    
    if ($targetUserId != $token->getViewerId())
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }
    
    foreach ($fields as $key)
    {
      if (!preg_match('/[\w\-\.]+/',$key))
      {
        throw new SocialSpiException("The person app data key had in valid characters", ResponseError::$BAD_REQUEST);
      }
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $persistentDatas = $application->getPersistentDatas($targetUserId, $fields);
    
    foreach ($persistentDatas as $persistentData)
    {
      $persistentData->delete();
    }
  }

  public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token)
  {
    if ($userId->getUserId($token) == null)
    {
      throw new SocialSpiException("Unknown person id", ResponseError::$NOT_FOUND);
    }

    foreach ($fields as $key)
    {
      if (!preg_match('/[\w\-\.]+/',$key))
      {
        throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
      }
    }

    if ($groupId->getType() != 'self')
    {
      throw new SocialSpiException("We don't support updating data in batches yet", ResponseError::$NOT_IMPLEMENTED);
    }

    $targetUserId = $userId->getUserId($token);
    $member = Doctrine::getTable('Member')->find($targetUserId);
    if (!$member)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    if ($token->getOwnerId() == $targetUserId || $token->getViewerId() == $targetUserId)
    {
      $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application, $member);
      if (!$memberApplication)
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }

      foreach($fields as $name)
      {
        $value = isset($values[$name]) ? $values[$name] : null;
        $persistentData = $application->getPersistentData($targetUserId, $name);
        if (!$persistentData)
        {
          $persistentData = new ApplicationPersistentData();
          $persistentData->setApplication($application);
          $persistentData->setMember($member);
          $persistentData->setName($name);
        }
        $persistentData->setValue($value);
        $persistentData->save();
      }
    }
    else
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }
  }

  public function createMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMessageCollections($userId, $fields, $options, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createMessage($userId, $msgCollection, $message, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMessage($userId, $msgCollId, $message, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessages($userId, $msgCollId, $messageIds, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMessages($userId, $msgCollId, $fields, $msgIds, $options, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  protected function getIdSet($user, GroupId $group, SecurityToken $token)
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
          $ids = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($userId);
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
