<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNEService
 *
 * This class is the one for Shindig.
 * Please set php/config/local.php of Shindig.
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class OpenPNEService implements ActivityService, PersonService, AppDataService, MessagesService, AlbumService, MediaItemService
{
  protected function fetch($postParam, $token)
  {
    $context = new GadgetContext('GADGET');
    $context->setIgnoreCache(true);
    $url = OpenPNEServiceConfig::SNSURL.OpenPNEServiceConfig::ACTION;

    $postData = json_encode($postParam);

    $headers = 'Content-Type: application/json; charaset=utf-8';
    $request = new RemoteContentRequest($url.'?st='.$token->toSerialForm(), $headers, $postData);
    return $this->getResponseData($context->getHttpFetcher()->fetch($request, $context));
  }

  protected function getResponseData($res)
  {
    if ($res->getHttpCode() != 200)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $resjson = json_decode($res->getResponseContent());
    if (!($resjson && is_array($resjson) && 1 === count($resjson)))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $data = isset($resjson[0]->result) ? $resjson[0]->result : $resjson[0]->data;
    if (isset($data->error))
    {
      throw new SocialSpiException($data->error->message, $data->error->code);
    }
    return $data;
  }

  protected function getIdSet($user, SecurityToken $token)
  {
    $ids = array();
    if ($user instanceof UserId)
    {
      if ($user->getType() && $user->getType() != 'userId')
      {
        return array('@'.$user->getType());
      }
      return array($user->getUserId($token));
    }
    elseif (is_array($user))
    {
      foreach ($user as $id)
      {
        $ids = array_merge($ids, $this->getIdSet($id, $token));
      }
    }
    return $ids;
  }

  protected function getGroupId($group)
  {
    if ($group->getType() && $group->getType() != 'groupId')
    {
      return '@'.$group->getType();
    }
    return $group->getGroupId();
  }

  protected function addStandardArguments(&$rpc, $options)
  {
    if ($options->getFilterBy())
    {
      $rpc[0]['params']['filterBy'] = $options->getFilterBy();
    }
    if ($options->getStartIndex())
    {
      $rpc[0]['params']['startIndex'] = $options->getStartIndex();
    }
    if ($options->getCount())
    {
      $rpc[0]['params']['count'] = $options->getCount();
    }
    if ($options->getSortBy())
    {
      $rpc[0]['params']['sortBy'] = $options->getSortBy();
    }
  }

  protected function getRestfulCollection($json)
  {
    $results = array();
    if(isset($json->list))
    {
      $totalSize = $json->totalResults;
      foreach($json->list as $d)
      {
        $result = array();
        foreach($d as $key => $value)
        {
          $result[$key] = $value;
        }
        $results[] = $result;
      }
    }
    else
    {
      $totalSize = 1;
      foreach($json as $key => $value)
      {
        $results[0][$key] = $value;
      }
    }

    $collection = new RestfulCollection($results, $json->data->startIndex, $totalSize);
    $collection->setItemsPerPage($json->data->itemsPerPage);
    return $collection;
  }

  public function getPerson($userId, $groupId, $fields, SecurityToken $token)
  {
    if (!is_object($userId))
    {
      $userId = new UserId('userId', $userId);
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
    $q = array(array(
      'method' => 'people.get',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $this->addStandardArguments($q, $options);
    $r = $this->fetch($q, $token);
    return $this->getRestfulCollection($r);
  }

  public function getActivity($userId, $groupId, $appdId, $fields, $activityId, SecurityToken $token)
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
    $q = array(array(
      'method' => 'activities.get',
      'params' => array(
        'userId' => $this->getIdSet($userIds, $token),
        'groupId' => $this->getGroupId($groupId),
      ),
      'id'     => 'data',
    ));
    $options = new CollectionOptions();
    $options->setSortBy($sortBy);
    $options->setFilterBy($filterBy);
    $options->setFilterOperation($filterOp);
    $options->setFilterValue($filterValue);
    $options->setStartIndex($startIndex);
    $options->setCount($count);
    $this->addStandardArguments($q, $options);
    $r = $this->fetch($q, $token);
    return $this->getRestfulCollection($r);
  }

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token)
  {
    if ($appId == $token->getAppId())
    {
      $appId = '@app';
    }
    $q = array(array(
      'method' => 'activities.create',
      'params' => array(
        'userId' => $this->getIdSet($userId, $token),
        'groupId' => $this->getGroupId($groupId),
        'appId' => $appId,
        'activity' => $activity,
      ),
      'id' => 'key'
    ));
    $this->fetch($q, $token);
    return null;
  }

  public function getPersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    $q = array(array(
      'method' => 'appdata.get',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'appId' => $appId, 
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $r = $this->fetch($q, $token);
    $data = array();
    if (!isset($r->data) || !$r->data)
    {
      return new DataCollection($data);
    }
    foreach($r->data as $memberId => $appdata)
    {
      foreach($appdata as $key => $value)
      {
        $data[$memberId][$key] = $value;
      }
    }
    return new DataCollection($data);
  }

  public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    $q = array(array(
      'method' => 'appdata.delete',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'appId' => $appId, 
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $this->fetch($q, $token);
    return null;
  }

  public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token)
  {
    $q = array(array(
      'method' => 'appdata.update',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'appId' => $appId, 
        'data' => $values,
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $this->fetch($q, $token);
    return null;
  }

  public function createMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessageCollection($userId, $msgCollId, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMessageCollections($userId, $fields, $options, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createMessage($userId, $msgCollId, $message, $token)
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
    $q = array(array(
      'method' => 'albums.get',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $this->addStandardArguments($q, $collectionOptions);

    $r = $this->fetch($q, $token);
    return $this->getRestfulCollection($r);
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
    $q = array(array(
      'method' => 'mediaitems.get',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'fields' => $fields,
      ),
      'id'     => 'data',
    ));
    $this->addStandardArguments($q, $collectionOptions);

    $r = $this->fetch($q, $token);
    return $this->getRestfulCollection($r);
  }

  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMediaItem($userId, $groupId, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMediaItems($userId, $groupId, $albumId, $mediaItemIds, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }
}
