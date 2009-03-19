<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for representing a row from the 'application' table.
 *
 * 
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 */ 
class Application extends BaseApplication
{
  public function hydrate($row, $startcol = 0, $rehydrate = false)
  {
    $result = parent::hydrate($row, $startcol, $rehydrate);

    if ($this->getCurrentApplicationI18n()->isNew())
    {
      $this->setCulture(sfConfig::get('sf_default_culture'));
    }

    return $result;
  }

  /**
   * hasSetting
   *
   * @return boolean
   */
  public function hasSetting()
  {
    $settings = $this->getSettings();
    if (!is_array($settings))
    {
      return false;
    }
    foreach ($settings as $setting)
    {
      if (!isset($setting['type']) || $setting['type'] != 'HIDDEN')
      {
        return true;
      }
    }
    return false;
  }

  /**
   * getSettings 
   * 
   * @param  string $culture
   * @return array
   */
  public function getSettings($culture = null)
  {
    return unserialize(parent::getSettings($culture));
  }

  /**
   * count installed member 
   *
   * @return integer
   */
  public function countInstalledMember()
  {
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::IS_GADGET, false);
    return $this->countMemberApplications($criteria);
  }
}

