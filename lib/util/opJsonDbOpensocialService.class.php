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
class opJsonDbOpensocialService implements ActivityService, PersonService, AppDataService, MessagesService, AlbumService, MediaItemService
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

  public function getActivity($userId, $groupId, $appId, $fields, $activityId, SecurityToken $token)
  {
    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }
    $activity = $this->getActivities($userId, $groupId, $appId, null, null, null, null, null, null, $fields, array($activityId), $token);
    if (is_array($person->getEntry()))
    {
      $activity = $activity->getEntry();
      if (is_array($activity) && count($activity) == 1)
      {
        return array_pop($activity);
      }
    }
    throw new SocialSpiException("Activity not found", ResponseError::$BAD_REQUEST);
  }

  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds ,$token)
  {
    $ids = $this->getIdSet($userIds, $groupId, $token);
    $startIndex = $this->fixStartIndex($startIndex);
    $count      = $this->fixCount($count);
    $ret = array();

    $activities = array();
    if (count($ids))
    {
      $query = Doctrine::getTable('ActivityData')->createQuery()
        ->whereIn('member_id', $ids)
        ->andWhere('(public_flag = ? OR public_flag = ?)', array(ActivityDataTable::PUBLIC_FLAG_OPEN, ActivityDataTable::PUBLIC_FLAG_SNS));

      if (count($activityIds))
      {
        if (1 === count($activityIds))
        {
          $query->andWhere('id = ?', $activityIds[0]);
        }
        else
        {
          $query->andWhereIn('id', $activityIds);
        }
      }

      if ($appId)
      {
        $query->andWhere('foreign_table = ?', Doctrine::getTable('Application')->getTableName())
          ->andWhere('foreign_id = ?', $appId);
      }

      $totalSize = $query->count();

      $query->orderBy('created_at DESC');
      $query->offset($startIndex);
      $query->limit($count);
      $activities = $query->execute();
    }

    $results = array();
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('opUtil', 'Asset', 'sfImage'));
    foreach ($activities as $activity)
    {
      $a = array();
      $a['id']         = $activity->getId();
      $a['userId']     = $activity->getMemberId();
      $a['title']      = opOpenSocialToolKit::convertEmojiForApi($activity->getBody());
      $a['postedTime'] = date(DATE_ATOM, strtotime($activity->getCreatedAt()));

      if ($activity->getUri())
      {
        $a['streamUrl'] = app_url_for('pc_frontend', $activity->getUri(), true);
      }
      if ($activity->getForeignTable() == Doctrine::getTable('MemberApplication')->getTableName())
      {
        $ma = Doctrine::getTable('MemberApplication')->find($activity->getForeignId());
        if ($ma)
        {
          $a['appId'] = $ma->getApplicationId();
        }
      }
      $mediaItems = array();
      foreach ($activity->getImages() as $image)
      {
        $mediaItem = array();
        if ($image->getFileId())
        {
          $mediaItem['thumbnailUrl'] = sf_image_path($image->getFile(), array('size' => '76x76'), true);
          $mediaItem['url'] = sf_image_path($image->getFile(), array(), true);
          $mediaItem['type'] = $image->getFile()->getType();
        }
        else
        {
          $mediaItem['url'] = app_url_for('pc_frontend', $image->getUri(), true);
          $mediaItem['type'] = $image->getMimeType();
        }
        $mediaItems[] = $mediaItem;
      }
      if (count($mediaItems))
      {
        $a['mediaItems'] = $mediaItems;
      }
      $results[] = $a;
    }

    $collection = new RestfulCollection($results, $startIndex, $totalSize);
    $collection->setItemsPerPage($count);
    return $collection;
  }

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token)
  {
    if (!($userId instanceof UserId) || $userId->getUserId($token) == null)
    {
      throw new SocialSpiException("Unknown person id", ResponseError::$NOT_FOUND);
    }

    $targetUserId = $userId->getUserId($token);
    $member = Doctrine::getTable('Member')->find($targetUserId);

    if (!$member)
    {
      throw new SocialSpiException("Person not found", ResponseError::$NOT_FOUND);
    }

    if ($targetUserId != $token->getViewerId())
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }

    if (!isset($activity['title']))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }


    $options = array();
    if ($token->getAppId())
    {
      $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationIdAndMemberId($token->getAppId(), $targetUserId);
      if (!$memberApplication)
      {
        throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
      }


      if (sfConfig::get('opensocial_activity_post_limit_time', 30))
      {
        $object = Doctrine::getTable('ActivityData')->createQuery()
          ->where('foreign_table = ?', Doctrine::getTable('Application')->getTableName())
          ->andWhere('foreign_id = ?', $memberApplication->getApplicationId())
          ->andWhere('member_id = ?', $member->getId())
          ->orderBy('created_at DESC')
          ->fetchOne();
        if ($object)
        {
          $interval = time() - strtotime($object->getCreatedAt());
          if ($interval < sfConfig::get('opensocial_activity_post_limit_time', 30))
          {
            throw new SocialSpiException("Service Unavailable", 503);
          }
        }
      }

      switch ($memberApplication->getPublicFlag())
      {
        case "friends" :
          $options['public_flag'] = ActivityDataTable::PUBLIC_FLAG_FRIEND;
          break;
        case "private" :
          $options['public_flag'] = ActivityDataTable::PUBLIC_FLAG_PRIVATE;
      }

      $application = $memberApplication->getApplication();

      $culture = $member->getConfig('language');
      $culture = $culture ? $culture : 'en';
      $application->setDefaultCulture($culture);
      $sourceName = $application->getTitle();
      if (!$sourceName)
      {
        $translations = $application->Translation;
        $keys = $translations->getKeys();
        if (count($keys))
        {
          $translation = $translations[$keys[0]];
          $sourceName = $translation->title;
        }
      }

      sfContext::getInstance()->getConfiguration()->loadHelpers(array('opUtil'));
      $options['source'] = $sourceName;
      $options['source_uri'] = app_url_for('pc_frontend', '@application_info?id='.$application->getId(), true);

      $options['foreign_table'] = Doctrine::getTable('Application')->getTableName();
      $options['foreign_id'] = $memberApplication->getApplicationId();
    }

    if (isset($activity['url']) && $activity['url'])
    {
      $url = $activity['url'];
      if (0 === strpos($url, 'http'))
      {
        $routingOptions = sfContext::getInstance()->getRouting()->getOptions();
        if (!preg_match('#^https?://'.preg_quote($routingOptions['context']['host']).'#', $url))
        {
          throw new SocialSpiException("Bad URL", ResponseError::$BAD_REQUEST);
        }
      }
      $options['uri'] = $url;
    }

    Doctrine::getTable('ActivityData')->updateActivity($targetUserId, $activity['title'], $options);
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
    if (!($userId instanceof UserId) || $userId->getUserId($token) == null)
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

  public function getAlbums($userId, $groupId, $albumIds, $collectionOptions, $fields, $token)
  {
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    $first = $this->fixStartIndex($collectionOptions->getStartIndex());
    $max   = $this->fixCount($collectionOptions->getCount());

    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    $albumIds = array_unique($albumIds);

    $objects = array();
    $totalSize = 0;

    if (count($memberIds))
    {
      $query = Doctrine::getTable('Album')->createQuery()
        ->whereIn('member_id', $memberIds);

      Doctrine::getTable('Album')->addPublicFlagQuery($query, AlbumTable::PUBLIC_FLAG_SNS);

      $totalSize = $query->count();

      $query->orderBy('id');
      if (count($albumIds))
      {
        $query->andWhereIn('id', $albumIds);
      }

      $query->offset($first);
      $query->limit($max);

      $objects = $query->execute();
    }
    $results = array();
    foreach ($objects as $object)
    {
      $result = array();
      $result['id']             = $object->getId();
      $result['title']          = opOpenSocialToolKit::convertEmojiForApi($object->getTitle());
      $result['description']    = opOpenSocialToolKit::convertEmojiForApi($object->getBody());
      $result['mediaItemCount'] = 0;
      if ($object->getAlbumImages())
      {
        $result['mediaItemCount'] = count($object->getAlbumImages());
      }
      $result['ownerId'] = $object->getMemberId();
      $result['thumbnailUrl'] = '';
      if ($object->getFile())
      {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
        $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
      }
      $result['mediaType'] = 'IMAGE';
      $results[] = $result;
    }

    $collection = new RestfulCollection($results, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }

  public function createAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteAlbum($userId, $groupId, $albumId, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMediaItems($userId, $groupId, $albumId, $mediaItemIds, $collectionOptions, $fields, $token)
  {
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    $first = $this->fixStartIndex($collectionOptions->getStartIndex());
    $max   = $this->fixCount($collectionOptions->getCount());

    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    if ($groupId->getType() !== 'self' || count($memberIds) !== 1)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $memberId = $memberIds[0];

    $albumObject = Doctrine::getTable('Album')->find($albumId);
    if (!$albumObject)
    {
      throw new SocialSpiException("Album Not Found", ResponseError::$BAD_REQUEST);
    }
    if ($albumObject->getMemberId() != $memberId &&
      !($albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_SNS ||
      $albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_OPEN))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $totalSize = 0;
    $query = Doctrine::getTable('AlbumImage')->createQuery()
      ->where('album_id = ?', $albumObject->getId());
    $totalSize = $query->count();

    $query->offset($first);
    $query->limit($max);

    $objects = $query->execute();

    $results = array();

    // block check
    $isBlock = false;
    if ($token->getViewerId())
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $token->getViewerId());
      if ($relation && $relation->getIsAccessBlock())
      {
        $isBlock = true;
      }
    }

    if (!$isBlock)
    {
      foreach ($objects as $object)
      {
        $result['albumId']      = $object->getId();
        $result['created']      = $object->getCreatedAt();
        $result['description']  = opOpenSocialToolKit::convertEmojiForApi($object->getDescription());
        $result['fileSize']     = $object->getFilesize();
        $result['id']           = $object->getId();
        $result['lastUpdated']  = $object->getUpdatedAt();
        $result['thumbnailUrl'] = '';
        $result['title']        = $object->getDescription();
        $result['type']         = 'IMAGE';
        $result['url']          = '';
        if ($object->getFile())
        {
          sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
          $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
          $result['url'] = sf_image_path($object->getFile(), array(), true);
        }
        $results[] = $result;
      }
    }

    $collection = new RestfulCollection($results, $first, $totalSize);
    $collection->setItemsPerPage($max);
    return $collection;
  }

  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMediaItem($userId, $groupId, $mediaItem, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMediaItems($userId, $groupId, $albumId, $mediaItemIds, $token)
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
        case 'friends':
          $ids = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($userId);
          break;
        case 'self':
          $ids[] = $userId;
          break;
        case 'all':
        case 'groupId':
          throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
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

    // block check
    if ($token->getViewerId())
    {
      $blockedIds = Doctrine::getTable('MemberRelationship')->getBlockedMemberIdsByTo($token->getViewerId());

      foreach ($ids as $k => $id)
      {
        if (isset($blockedIds[$id]))
        {
          unset($ids[$k]);
        }
      }
    }

    return $ids;
  }

  protected function fixStartIndex($startIndex = null)
  {
    if (!($startIndex !== false && is_numeric($startIndex) && $startIndex >= 0))
    {
      return 0;
    }

    return $startIndex;
  }

  protected function fixCount($count = null)
  {
    if (!($count !== false && is_numeric($count) && $count > 0))
    {
      return RequestItem::$DEFAULT_COUNT;
    }

    if ($count > sfConfig::get('op_opensocial_api_max_count', 100))
    {
      return sfConfig::get('op_opensocial_api_max_count', 100);
    }

    return $count;
  }
}
