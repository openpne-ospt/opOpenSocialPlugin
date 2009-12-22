<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginApplicationTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class PluginApplicationTable extends Doctrine_Table
{
  const ADD_APPLICATION_DENY = 0; 
  const ADD_APPLICATION_NECESSARY_TO_PERMIT = 1;
  const ADD_APPLICATION_ALLOW = 2;

 /**
  * get add application rule selection
  *
  * @return array
  */
  public function getAddApplicationRuleChoices()
  {
    $result = array(
      self::ADD_APPLICATION_DENY                => "Deny",
      self::ADD_APPLICATION_NECESSARY_TO_PERMIT => "The SNS administrator's permission is necessary",
      self::ADD_APPLICATION_ALLOW               => "Allow",
    );

    return array_map(array(sfContext::getInstance()->getI18N(), '__'), $result);
  }

  /**
   * add a new application
   *
   * @param string  $url
   * @param boolean $update
   * @param string  $culture
   * @return Application
   */
  public function addApplication($url, $update = false, $culture = null)
  {
    if ($culture === null)
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    if ($culture != sfConfig::get('sf_default_culture'))
    {
      self::addApplication($url, $update, sfConfig::get('sf_default_culture'));
    }

    $application = $this->findOneByUrl($url);

    if (!$application)
    {
      $application = new Application();
    }

    if (isset($application->Translation[$culture]) && !$update)
    {
      $ua = $application->getUpdatedAt();
      $time = strtotime($ua);
      if (!empty($ua) && (time() - $ua) <= Doctrine::getTable('SnsConfig')->get('application_cache_time', 24*60*60))
      {
        return $application;
      }
    }

    $gadget = opOpenSocialToolKit::fetchGadgetMetadata($url, $culture);

    $translation = $application->Translation[$culture];
    $application->setUrl($gadget['url']);
    $translation->title              = $gadget['title'];
    $translation->title_url          = $gadget['titleUrl'];
    $translation->description        = $gadget['description'];
    $translation->directory_title    = $gadget['directoryTitle'];
    $translation->screenshot         = $gadget['screenshot'];
    $translation->thumbnail          = $gadget['thumbnail'];
    $translation->author             = $gadget['author'];
    $translation->author_aboutme     = $gadget['authorAboutme'];
    $translation->author_affiliation = $gadget['authorAffiliation'];
    $translation->author_email       = $gadget['authorEmail'];
    $translation->author_photo       = $gadget['authorPhoto'];
    $translation->author_link        = $gadget['authorLink'];
    $translation->author_quote       = $gadget['authorQuote'];
    $translation->settings           = $gadget['userPrefs'];
    $translation->views              = $gadget['views'];
    if ($gadget['scrolling'] == 'true')
    {
      $application->setScrolling(true);
    }
    else
    {
      $application->setScrolling(false);
    }

    if ($gadget['singleton'] == 'true' || empty($gadget['singleton']))
    {
      $application->setSingleton(true);
    }
    else
    {
      $application->setSingleton(false);
    }

    $application->setHeight(!empty($gadget['height']) ? $gadget['height'] : 0);
    $application->save();
    return $application;
  }

  public function getApplicationListPager($page = 1, $size = 20, $memberId = null, $isActive = null, $orderBy = 'id desc')
  {
    $query = $this->createQuery();

    if (null !== $memberId)
    {
      $query->addWhere('member_id = ?', $memberId);
    }

    if (is_bool($isActive))
    {
      $query->addWhere('is_active = ?', $isActive);
    }

    if ($orderBy)
    {
      $query->orderBy($orderBy);
    }

    $pager = new sfDoctrinePager('Application', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();
    return $pager;
  }
}
