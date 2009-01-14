<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberApplicationSettingPeer
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 * @author  Shogo Kawahara <kawahara@tejimaya.net>
 */

class MemberApplicationSettingPeer extends BaseMemberApplicationSettingPeer
{
  public static function retrieveByMemberApplicationIdAndName($memberApplicationId, $name)
  {
    $criteria = new Criteria();
    $criteria->add(self::MEMBER_APPLICATION_ID, $memberApplicationId);
    $criteria->add(self::NAME, $name);
    return self::doSelectOne($criteria);
  }

  public static function getSettingsByMemberApplicationId($memberApplicationId)
  {
    $criteria = new Criteria();
    $criteria->add(self::MEMBER_APPLICATION_ID, $memberApplicationId);
    return self::doSelect($criteria);
  }
}
