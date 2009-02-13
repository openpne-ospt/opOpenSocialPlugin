<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * ApplicationPersistentDataPeer
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 */
class ApplicationPersistentDataPeer extends BaseApplicationPersistentDataPeer
{
  public static function retrieveByApplicationIdAndMemberIdAndName($applicationId, $memberId, $name)
  {
    $criteria = new Criteria();
    $criteria->add(self::APPLICATION_ID, $applicationId);
    $criteria->add(self::MEMBER_ID, $memberId);
    $criteria->add(self::NAME, $name);
    return self::doSelectOne($criteria);
  }

  public static function retrievesByApplicationIdAndMemberId($applicationId, $memberId, $fields = array())
  {
    $criteria = new Criteria();
    $criteria->add(self::APPLICATION_ID, $applicationId);
    $criteria->add(self::MEMBER_ID, $memberId);
    if (count($fields))
    {
      $criteria->add(self::NAME, $fields, Criteria::IN);
    }
    return self::doSelect($criteria);
  }

  public static function getMemberFriendPersistentDatas($applicationId, $memberId, $fields = array())
  {
    $friendIds = MemberRelationshipPeer::getFriendMemberIds($memberId);
    $criteria = new Criteria();
    $criteria->add(self::APPLICATION_ID, $applicationId);
    $criteria->add(self::MEMBER_ID, $friendIds, Criteria::IN);
    if (count($fields))
    {
      $criteria->add(self::NAME, $fields, Criteria::IN);
    }
    return self::doSelect($criteria);
  }
}
