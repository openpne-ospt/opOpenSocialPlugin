<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialActivityService
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialActivityService extends opOpenSocialServiceBase implements ActivityService
{
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

      $uri = $activity->getUri();
      if ($uri)
      {
        if (strpos($uri, '://') !== false)
        {
          $a['streamUrl'] = $uri;
        }
        else
        {
          $a['streamUrl'] = app_url_for('pc_frontend', $uri, true);
        }
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
          $imageUri = $image->getUri();
          if (strpos($imageUri, '://') !== false)
          {
            $mediaItem['url'] = $imageUri;
          }
          else
          {
            $mediaItem['url'] = app_url_for('pc_frontend', $imageUri, true);
          }
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
}

