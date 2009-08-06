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
    if (is_null($isDevEnvironment))
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
   * @return boolean
   */
  public function generateAndSave($force = false, $snsUrl = null, $shindigUrl = null)
  {
    if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
    {
      return false;
    }

    $dirname  = sfConfig::get('sf_cache_dir').'/pc_frontend';
    if ($this->isDevEnvironment)
    {
      $dirname .= '/dev';
    }
    else
    {
      $dirname .= '/prod';
    }
    $dirname .= '/plugins/opOpenSocialPlugin';
    
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

  /**
   * generate a configutaion
   *
   * @param string $containerName
   * @param string $snsUrl
   * @param string $shindigUrl
   * @return string 
   */
  public function generate($snsUrl = null, $shindigUrl = null)
  {
    //Template
    $containerTemplate = array(
      'gadgets.container' => array(),
      'gadgets.parent' => null,
      'gadgets.lockedDomainRequired' => false,
      'gadgets.lockedDomainSuffix' => '-a.example.com:8080',
      'gadgets.iframeBaseUri' => '/gadgets/ifr',
      'gadgets.jsUriTemplate' => '#shindig_url#gadgets/js/%js%',
      'gadgets.oauthGadgetCallbackTemplate' => '#shindig_url#/gadgets/oauthcallback',
      'gadgets.securityTokenType' => 'secure',
      'gadgets.osDataUri' => '#shindig_url#social/rpc',
      'gadgets.features' => array(
        'core.io' => array(
          'proxyUrl' => '#shindig_url#gadgets/proxy?refresh=%refresh%&url=%url%',
          'jsonProxyUrl' => '#shindig_url#gadgets/makeRequest',
        ),
        'views' => array(
          'profile' => array(
            'isOnlyVisible' => false,
            'urlTemplate' => '#sns_url#member/{var}',
            'aliases' => array('DASHBOARD', 'default')
          ),
          'canvas' => array(
            'isOnlyVisible' => true,
            'urlTemplate' => '#sns_url#application/canvas/id/{var}',
          )
        ),
        'rpc' => array(
          'parentRelayUrl' => '#sns_url#opOpenSocialPlugin/js/rpc_relay.html',
          'useLegacyProtocol' => false
        ),
        'skins' => array(
          'properties' => array(
            'BG_COLOR' => '',
            'BG_IMAGE' => '',
            'BG_POSITION' => '',
            'BG_REPEAT' => '',
            'FONT_COLOR' => '',
            'ANCHOR_COLOR' => ''
          )
        ),
        'opensocial-0.8' => array(
          'impl' => 'rpc',
          'path' => '#shindig_url#social',
          'domain' => 'shindig',
          'enableCaja' => false,
          'supportedFields' => array()
        ),
        'osapi.services' => array(
          'gadgets.rpc' => array('container.listMethods')
        ),
        'osapi' => array(
          'endPoint' => array('#shindig_url#social/rpc', '#shindig_url#gadgets/api/rpc')
        ),
        'osml' => array(
          'library' => 'config/OSML_library.xml'
        )
      ),
    );

    $containerTemplate['gadgets.container'][] = $this->containerName;
    if (!$snsUrl)
    {
      $request = sfContext::getInstance()->getRequest();
      $snsUrl = $request->getUriPrefix().$request->getRelativeUrlRoot().'/';
      if($this->isDevEnvironment)
      {
        $snsUrl .= 'pc_frontend_dev.php/';
      }
    }

    if (!$shindigUrl)
    {
      if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig'))
      {
        $shindigUrl = Doctrine::getTable('SnsConfig')->get('shindig_url');
      }
      else
      {
        $shindigUrl = $snsUrl;
      }
    }

    $opPersonFields = Doctrine::getTable('OpenSocialPersonField')->getNotNullPersonFields();
    $personFields = array('id', 'name', 'thumbnailUrl', 'profileUrl');
    foreach ($opPersonFields as $opPersonField)
    {
      $personFields = $opPersonField->getFieldName();
    }
    
    $supportedFields =& $containerTemplate['gadgets.features']['opensocial-0.8']['supportedFields'];
    $supportedFields['person'] = $personFields;
    $supportedFields['activity'] = array();

    $json = json_encode($containerTemplate);

    $replace = array(
      '/#sns_url#/' => $snsUrl,
      '/#shindig_url#/' => $shindigUrl
    );

    return preg_replace(array_keys($replace), $replace, $json);
  }
}

