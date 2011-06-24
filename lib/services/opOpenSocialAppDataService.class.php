<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpensocialAppDataService
 *
 * @author Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialAppDataService extends opOpenSocialServiceBase implements AppDataService
{
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
}

