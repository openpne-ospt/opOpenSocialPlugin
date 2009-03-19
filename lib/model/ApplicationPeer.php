<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'application' table.
 *
 * 
 *
 * @package plugins.opOpenSocialPlugin.lib.model
 */ 
class ApplicationPeer extends BaseApplicationPeer
{
  /**
   * cast object from array
   *
   * @param array $array
   * @return object
   */
  protected static function arrayToObject($array)
  {
    foreach ($array as &$a)
    {
      if(is_array($a))
      {
        $a = self::arrayToObject($a);
      }
    }
    return (object)$array;
  }

  /**
   * add or update application
   *
   * @param string $url     gadget url
   * @param bool   $update
   * @param string $culture culture 
   * @return Application application object  
   */
  public static function addApplication($url, $update = false, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    if ($culture != sfConfig::get('sf_default_culture'))
    { 
      self::addApplication($url, $update, sfConfig::get('sf_default_culture'));
    }

    $app = self::retrieveByUrl($url);
    if (!$app)
    {
      $app = new Application();
    }
    $app->setCulture($culture);

    if (!($app->getCurrentApplicationI18n()->isNew() || $update))
    {
      $ua = $app->getUpdatedAt('U');
      if (!empty($ua) && (time() - $ua) <= SnsConfigPeer::get('application_cache_time', 24*60*60))
      {
        return $app;
      }
    }
    $cul = split('_',$culture);
    $req = self::arrayToObject(array(
      'context' => array(
        'country' => isset($cul[1]) ? $cul[1] : 'All',
        'language' => $cul[0],
        'view' => 'default',
        'container' => 'openpne3'
      ),
      'gadgets' => array(array('url' => $url,'moduleId' => 1))
    ));
    $_GET['nocache'] = 1;
    $handler = new MetadataHandler();
    $response = $handler->process($req);
    if (!is_array($response) || count($response) <= 0)
    {
      throw new Exception('No data');
    }
    if (isset($response[0]['errors']))
    {
      throw new Exception($response[0]['errors'][0]);
    }
    $gadget = $response[0];
    $app->setUrl($gadget['url']);
    $app->setTitle($gadget['title']);
    $app->setTitleUrl($gadget['titleUrl']);
    $app->setDescription($gadget['description']);
    $app->setDirectoryTitle($gadget['directoryTitle']);
    $app->setScreenshot($gadget['screenshot']);
    $app->setThumbnail($gadget['thumbnail']);
    $app->setAuthor($gadget['author']);
    $app->setAuthorAboutme($gadget['authorAboutme']);
    $app->setAuthorAffiliation($gadget['authorAffiliation']);
    $app->setAuthorEmail($gadget['authorEmail']);
    $app->setAuthorPhoto($gadget['authorPhoto']);
    $app->setAuthorLink($gadget['authorLink']);
    $app->setAuthorQuote($gadget['authorQuote']);
    $app->setSettings(isset($gadget['userPrefs']) ? serialize($gadget['userPrefs']) : '');
    $app->setViews(isset($gadget['views']) ? serialize($gadget['views']) : '');
    if ($gadget['scrolling'] == 'true')
    {
      $app->setScrolling(true);
    }
    else
    {
      $app->setScrolling(false);
    }

    if ($gadget['singleton'] == 'true' || empty($gadget['singleton']))
    {
      $app->setSingleton(true);
    }
    else
    {
      $app->setSingleton(false);
    }
    $app->setHeight(! empty($gadget['height']) ? $gadget['height'] : '0');
    $app->save();
    return $app;
  }

  /**
   * has settings
   *
   * @param integer $modId
   * @return boolean
   */
  public static function hasSetting($modId)
  {
    $app = self::retrieveByPk($modId);
    if (!$app)
    {
      return false;
    }
    return $app->hasSettings();
  }

  /**
   * retrieve by url
   *
   * @param  string $url A url
   * @return Application The application instance
   */
  public static function retrieveByUrl($url)
  {
    $criteria = new Criteria();
    $criteria->add(self::URL, $url);
    return self::doSelectOne($criteria);
  }

  /**
   * update application
   *
   * @param integer $modId
   * @param string  $culture
   * @return Application The application instance
   */
  public static function updateApplication($modId, $culture = null)
  {
    if (is_null($culture))
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    $app = self::retrieveByPk($modId);
    if (!$app)
    {
      return false;
    }

    try
    {
      return self::addApplication($app->getUrl(), true, $culture);
    }
    catch (Exception $e)
    {
      return false;
    }
  }
}
