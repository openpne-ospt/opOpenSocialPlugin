<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'member_application' table.
 *
 * 
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 */ 
class MemberApplicationPeer extends BaseMemberApplicationPeer
{
  /**
   * retrieve by memberId and applicationId
   *
   * @param integer  $applicationId
   * @param integer  $memberId
   * @param Criteria $criteria
   * @return MemberApplication
   */
  public static function retrieveByApplicationIdAndMemberId($applicationId, $memberId, $criteria = null)
  {
    if (!$criteria)
    {
      $criteria = new Criteria();
    }
    $criteria->add(self::APPLICATION_ID, $applicationId);
    $criteria->add(self::MEMBER_ID, $memberId);
    return self::doSelectOne($criteria);
  }

  /**
   * get home or profile member applications
   *
   * @param integer $memberId
   * @param integer $viewerId
   * @param integer $limit
   * @return array
   */
  public static function getHomeMemberApplications($memberId, $viewerId, $limit = 3)
  {
    $criteria = new Criteria();
    $criteria->add(self::IS_GADGET, false);
    $criteria->add(self::MEMBER_ID ,$memberId);
    $criteria->add(self::IS_DISP_HOME, true);
    if ($memberId != $viewerId)
    {
      $criteria->add(self::IS_DISP_OTHER, true);
    }
    $criteria->addAscendingOrderByColumn(self::SORT_ORDER);
    $criteria->setLimit($limit);
    return self::doSelect($criteria);
  }

  /**
   * get member application list
   *
   * @param integer $memberId
   * @param integer $viewerId
   * @return array
   */
  public static function getMemberApplicationList($memberId, $viewerId)
  {
    $criteria = new Criteria();
    $criteria->add(self::IS_GADGET, false);
    $criteria->add(self::MEMBER_ID, $memberId);
    if ($memberId != $viewerId)
    {
      $criteria->add(self::IS_DISP_OTHER, true);
    }
    $criteria->addAscendingOrderByColumn(self::SORT_ORDER);
    return self::doSelect($criteria);
  }
}
