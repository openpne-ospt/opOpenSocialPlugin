<?php

/**
 * Subclass for representing a row from the 'application' table.
 *
 * 
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 */ 
class Application extends BaseApplication
{
  /**
   * hasSettings
   *
   * @return boolean
   */
  public function hasSettings()
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
   * @param string $culture
   * @return array
   */
  public function getSettings($culture = null)
  {
    return unserialize(parent::getSettings($culture));
  }
}
