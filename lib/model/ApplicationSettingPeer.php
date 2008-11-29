<?php

/**
 * Subclass for performing query and update operations on the 'application_setting' table.
 *
 * 
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 */ 
class ApplicationSettingPeer extends BaseApplicationSettingPeer
{
  public static function retrieveByMemberApplicationIdAndName($memberApplicationId, $name)
  {
    $criteria = new Criteria();
    $criteria->add(self::MEMBER_APPLICATION_ID, $memberApplicationId);
    $criteria->add(self::NAME, $name);
    return self::doSelectOne($criteria);
  }
}
