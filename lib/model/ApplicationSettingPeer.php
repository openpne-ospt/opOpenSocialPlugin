<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'application_setting' table.
 *
 * 
 * @package plugins.opOpenSocialPlugin.lib.model
 * @author  Shogo Kawahara <kawahara@tejimaya.net>
 */ 
class ApplicationSettingPeer extends BaseApplicationSettingPeer
{
  public static function getSettingsByApplicationId($applicationId)
  {
    $criteria = new Criteria();
    $criteria->add(self::APPLICATION_ID, $memberApplicationId);
    return self::doSelect($criteria);
  }
}
