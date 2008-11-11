<?php

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
   * @param string $culture culture 
   * @param bool   $update
   * @return Application application object  
   */
  public static function addApplication($url, $culture = 'en_US', $update = false)
  {
    $criteria = new Criteria(self::DATABASE_NAME);
    $criteria->add(self::URL, $url);
    $app = self::doSelectOne($criteria);
    if (!empty($app) && !$update)
    {
      $ca = $app->getUpdatedAt();
      if (!empty($ca))
        {
          return $app;
        }
    }
    $cul = split('_',$culture);
    $req = self::arrayToObject(array(
      'context' => array(
        'country' => isset($cul[1]) ? $cul[1] : 'US',
        'language' => $cul[0],
        'view' => 'default',
        'container' => 'openpne3'
      ),
      'gadgets' => array(array('url' => $url,'moduleId' => 1))
    ));
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
    $default_gadget = array(
      'url'             => '',
      'title'           => '',
      'directory_title' => '',
      'screenshot'      => '',
      'thumbnail'       => '',
      'author'          => '',
      'author_email'    => '',
      'description'     => ''
    );
    $gadget = $response[0];
    if (isset($gadget['authorEmail']))
    {
      $gadget['author_email'] = $gadget['authorEmail'];
    }
    if (isset($gadget['directoryTitle']))
    {
      $gadget['directory_title'] = $gadget['directoryTitle'];
    }
    $gadget = array_merge($default_gadget,$gadget);
    if (empty($app))
    {
      $app = new Application();
    }
    $app->setUrl($gadget['url']);
    $app->setTitle($gadget['title']);
    $app->setDirectoryTitle($gadget['directory_title']);
    $app->setScreenshot($gadget['screenshot']);
    $app->setThumbnail($gadget['thumbnail']);
    $app->setAuthor($gadget['author']);
    $app->setAuthorEmail($gadget['authorEmail']);
    $app->setDescription($gadget['description']);
    $app->setSettings(isset($gadget['userPrefs']) ? serialize($gadget['userPrefs']) : '');
    $app->setViews(isset($gadget['views']) ? serialize($gadget['views']) : '');
    if ($gadget['scrolling'] == 'true')
    {
      $gadget['scrolling'] = 1;
    }
    $app->setScrolling(! empty($gadget['scrolling']) ? $gadget['scrolling'] : '0');
    $app->setHeight(! empty($gadget['height']) ? $gadget['height'] : '0');
    $iframe_url = $gadget['iframeUrl'];
    $iframe_params = array();
    parse_str($iframe_url, $iframe_params);
    $app->setVersion(isset($iframe_params['v']) ? $iframe_params['v'] : '');
    $app->save();
    return $app;
  }
}
