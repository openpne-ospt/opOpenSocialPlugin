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
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 */
class OpenPNEService implements ActivityService, PersonService, AppDataService, MessagesService
{
  // changed this parameter
  // for example:
  // 
  // const SNSURL = 'http://sns.example.com/';
  const SNSURL = '#SNS_URL#';

  const ACTION = 'social/rpc';

  protected function fetch($postParam, $token)
  {
    $headers = '';
    $context = new GadgetContext('GADGET');
    $context->setIgnoreCache(true);
    $postData = array('request' => json_encode($postParam));
    $url = self::SNSURL.self::ACTION.'?st='.base64_encode($token->toSerialForm());
    $request = new RemoteContentRequest($url, $headers, $postData);
    $request->setContentType('application/json; charset=UTF8');
    $etime = microtime();
    return $this->getResponseData($context->getHttpFetcher()->fetch($request, $context));
  }

  protected function getResponseData($res)
  {
    if ($res->getHttpCode() != 200)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $resjson = json_decode($res->getResponseContent());
    if (!$resjson && count($resjson) == 1)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $data = $resjson[0];
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
      'id'     => 'peopledata',
    ));
    if ($options->getFilterBy())
    {
      $q[0]['params']['filterBy'] = $options->getFilterBy();
    }
    if ($options->getStartIndex())
    {
      $q[0]['params']['startIndex'] = $options->getStartIndex(); 
    }
    if ($options->getCount())
    {
      $q[0]['params']['count'] = $options->getCount();
    }
    if ($options->getSortBy())
    {
      $q[0]['params']['sortBy'] = $options->getSortBy();
    }

    $r = $this->fetch($q, $token);
    
    $people = array();
    if(isset($r->data->list))
    {
      $totalSize = $r->data->totalResults;
      foreach($r->data->list as $d)
      {
        $p = array();
        foreach($d as $key => $value)
        {
          $p[$key] = $value;
        }
        $people[] = $p;
      }
    }
    else
    {
      $totalSize = 1;
      foreach($r->data as $key => $value)
      {
        $p[$key] = $value;
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
    $q = array(array(
      'method' => 'appdata.get',
      'params' => array(
        'userId' => $this->getIdSet($userId,$token),
        'groupId' => $this->getGroupId($groupId),
        'appId' => $appId, 
        'fields' => $fields,
      ),
      'id'     => 'appdata',
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
      'id'     => 'appdata',
    ));

    $this->fetch($q, $token);
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
      'id'     => 'appdata',
    ));
    $this->fetch($q, $token); 
  }

  public function createMessage($userId, $appId, $message, $optionalMessageId, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

}
