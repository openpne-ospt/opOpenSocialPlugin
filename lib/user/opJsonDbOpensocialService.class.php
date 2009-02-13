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
    sfLoader::loadHelpers("Asset");
    sfLoader::loadHelpers("sfImage");
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
      //FIXME
      $p = array();
      $p['isOwner']  =  (!$token->isAnonymous() && $member->getId() == $token->getOwnerId()) ? true : false;
      $p['isViewer'] =  (!$token->isAnonymous() && $member->getId() == $token->getViewerId()) ? true : false;
      $p['id']           = $member->getId();
      $p['displayName']  = $member->getName();
      $p['thumbnailUrl'] = "";
      if ($member->getImage())
      {
        $p['thumbnailUrl'] = sf_image_path($member->getImage()->getFile()->getName(),
          array('size' => '180x180'), true);
      }
      $p['profileUrl']   = sfContext::getInstance()->getController()->genUrl("@member_profile?id=".$member->getId(),true);
      $memberProfiles = MemberProfilePeer::getProfileListByMemberId($member->getId());
      foreach ($memberProfiles as $memberProfile)
      {
        $osPersonField = $memberProfile->getProfile()->getOpensocialPersonField();
        if ($osPersonField)
        {
          $fieldName = $osPersonField->getFieldName();
          switch ($memberProfile->getProfile()->getFormType())
          {
            case "date":
              $p[$fieldName] = date("Y/m/d",strtotime($memberProfile->getValue()));
              break;
            default:
              $p[$fieldName] = $memberProfile->getValue();
          }
        }
      }
      $people[] = $p;
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
    $viewerId = (int)$token->getViewerId();
    if ($targetUserId != $viewerId)
    {
      $memberRelationship = MemberRelationshipPeer::retrieveByFromAndTo($targetUserId, $viewerId);
      if (!($memberRelationship && $memberRelationship->isFriend()))
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }
    }
   if ($groupId->getType() == 'self')
    {
      $appPersistentDatas = ApplicationPersistentDataPeer::retrievesByApplicationIdAndMemberId($appId, $targetUserId, $fields);
    }
    else if($groupId->getType() == 'friends')
    {
      $appPersistentDatas = ApplicationPersistentDataPeer::getMemberFriendPersistentDatas($appId, $targetUserId, $fields);  
    }
    else
    {
      throw new SocialSpiException("We support getting data only when GROUP_ID is SELF or FRIENDS ", ResponseError::$NOT_IMPLEMENTED);
    }
    $data = array();
    foreach ($appPersistentDatas as $appPersistentData)
    {
      $data[$appPersistentData->getMemberId()][$appPersistentData->getName()] = $appPersistentData->getValue();
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

    $appPersistentDatas = ApplicationPersistentDataPeer::retrievesByApplicationIdAndMemberId($appId, $targetUserId, $fields);
    
    foreach ($appPersistentDatas as $appPersistentData)
    {
      $appPersistentData->delete();
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
    if ($token->getOwnerId() == $targetUserId || $token->getViewerId() == $targetUserId)
    {
      $memberApplication = MemberApplicationPeer::retrieveByApplicationIdAndMemberId($appId, $targetUserId);
      if (!$memberApplication)
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }

      foreach($fields as $name)
      {
        $value = isset($values[$name]) ? $values[$name] : null;
        $appPersistentData = ApplicationPersistentDataPeer::retrieveByApplicationIdAndMemberIdAndName($appId, $targetUserId, $name);
        if (!$appPersistentData)
        {
          $appPersistentData = new ApplicationPersistentData();
          $appPersistentData->setApplicationId($appId);
          $appPersistentData->setMemberId($targetUserId);
          $appPersistentData->setName($name);
        }
        $appPersistentData->setValue($value);
        $appPersistentData->save();
      }
    }
    else
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }
  }

  public function createMessage($userId, $appId, $message, $optionalMessageId, SecurityToken $token)
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
          $ids = MemberRelationshipPeer::getFriendMemberIds($userId);
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
