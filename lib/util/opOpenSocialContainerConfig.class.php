<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialContainerConfig
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialContainerConfig
{

  protected $containerName = null;
  protected $isDevEnvironment = null;

  public function __construct($isDevEnvironment = null, $containerName = 'openpne')
  {
    if ($isDevEnvironment === null)
    {
      if (sfConfig::get('sf_environment') == 'dev')
      {
        $isDevEnvironment = true;
      }
      else
      {
        $isDevEnvironment = false;
      }
    }

    if ($isDevEnvironment)
    {
      $containerName .= '_dev';
    }

    $this->isDevEnvironment = $isDevEnvironment;
    $this->containerName = $containerName;
  }

  public function getContainerName()
  {
    return $this->containerName;
  }

  /**
   * generate and save a configuration
   *
   * @param boolean $force
   * @param string  $snsUrl
   * @param string  $shindigUrl
   * @param string  $apiUrl
   * @return boolean
   */
  public function generateAndSave($force = false, $snsUrl = null, $shindigUrl = null, $apiUrl = null)
  {
    if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
    {
      return false;
    }

    $dirname  = Shindig_Config::get('container_path');
    $filename = $dirname.'/'.$this->containerName.'.js';

    if (file_exists($filename) && !$force)
    {
      return true;
    }

    $json = self::generate($snsUrl, $shindigUrl);

    if (!is_dir($dirname))
    {
      $old_umask = umask(0);
      @mkdir($dirname, 0777, true);
      umask($old_umask);
    }

    if (file_put_contents($filename, $json))
    {
      return true;
    }

    return false;
  }

  protected function loadTemplate($templateFile)
  {
    if (!is_readable($templateFile))
    {
      throw new Exception('template of container configuration is not readable.');
    }

    $str = file_get_contents($templateFile);

    if (empty($str))
    {
      throw new Exception('template of container configuration is empty.');
    }

    // remove comment
    $str = preg_replace('@/\\*.*?\\*/@s', '', $str);
    $str = preg_replace('/(?<!http:|https:|")\/\/.*$/m', '', $str);

    $result = json_decode($str, true);

    if (null === $result)
    {
      throw new Exception('template of container configuration is not json data.');
    }

    return $result;
  }

  /**
   * generate a configutaion
   *
   * @param string $containerName
   * @param string $snsUrl
   * @param string $shindigUrl
   * @param string $apiUrl
   * @return string
   */
  public function generate($snsUrl = null, $shindigUrl = null, $apiUrl = null, $templateFile = null)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset'));

    if (null === $templateFile)
    {
      $templateFile = dirname(__FILE__).'/../vendor/Shindig/config/container.js';
    }

    $containerTemplate = $this->loadTemplate($templateFile);

    $request = sfContext::getInstance()->getRequest();
    if (null === $snsUrl)
    {
      $snsUrl = $request->getUriPrefix().$request->getRelativeUrlRoot().'/';
      if($this->isDevEnvironment)
      {
        $snsUrl .= 'pc_frontend_dev.php/';
      }
    }

    if (null === $apiUrl)
    {
      if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
      {
        $apiUrl = Doctrine::getTable('SnsConfig')->get('shindig_url');
        if (substr($apiUrl, -1) !== '/')
        {
          $apiUrl .= '/';
        }
      }
      else
      {
        $apiUrl = $request->getUriPrefix().$request->getRelativeUrlRoot().'/api';
        if ($this->isDevEnvironment)
        {
          $apiUrl .= '_dev';
        }
        $apiUrl .= '.php/';
      }
    }

    if (null === $shindigUrl)
    {
      if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
      {
        $shindigUrl = $apiUrl;
      }
      else
      {
        $shindigUrl = $snsUrl;
      }
    }

    // override container template
    $containerTemplate['gadgets.container'] = array($this->containerName);
    $containerTemplate['gadgets.jsUriTemplate'] = $shindigUrl.'gadgets/js/%js%';

    $jsUrl = parse_url($shindigUrl.'gadgets/js');

    $containerTemplate['gadgets.uri.js.host'] = $jsUrl['scheme'].'://'.$jsUrl['host'].(isset($jsUrl['port']) ? $jsUrl['port'] : '').'/';
    $containerTemplate['gadgets.uri.js.path'] = $jsUrl['path'];

    $containerTemplate['gadgets.securityTokenType'] = 'secure';
    $containerTemplate['gadgets.osDataUri'] = $apiUrl.'social/rpc';

    $features =& $containerTemplate['gadgets.features'];

    $features['core.io']['proxyUrl']     = $shindigUrl.'gadgets/proxy?refresh=%refresh%&url=%url%';
    $features['core.io']['jsonProxyUrl'] = $shindigUrl.'gadgets/makeRequest';

    $features['views']['profile']['urlTemplate'] = $snsUrl.'member/{var}';
    $features['views']['canvas']['urlTemplate']  = $snsUrl.'application/canvas/id/{var}';

    $features['rpc']['parentRelayUr'] = $snsUrl.'opOpenSocialPlugin/js/rpc_relay.html';

    $features['opensocial']['path']           =$apiUrl.'social/rpc';
    $features['opensocial']['invalidatePath'] = $shindigUrl.'gadgets/api/rpc';

    $export = new opOpenSocialProfileExport();

    $supportedFields = $export->getSupportedFields();
    $supportedFields = array_merge($supportedFields, array(
      'activity' => array('id', 'title', 'userId', 'postedTime', 'streamUrl', 'appId', 'mediaItems'),
      'mediaItem' => array('id', 'albumId', 'created', 'description', 'fileSize', 'lastUpdated', 'thumbnailUrl', 'title', 'type', 'url')
    ));

    $features['opensocial']['supportedFields'] = $supportedFields;

    $features['osapi.services'][$apiUrl.'social/rpc'] = array(
      'system.listMethods',
      'people.get',
      'appdata.get',
      'appdata.update',
      'appdata.delete',
    );

    $features['oapi']['endPoint'] = array($apiUrl.'/social/rpc');

    $json = json_encode($containerTemplate);

    return $json;
  }
}
