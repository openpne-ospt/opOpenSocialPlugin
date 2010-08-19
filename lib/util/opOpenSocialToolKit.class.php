<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialToolKit
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialToolKit
{
  static protected function arrayToObject($array)
  {
    foreach ($array as &$a)
    {
      if (is_array($a))
      {
        $a = self::arrayToObject($a);
      }
    }

    return (object)$array;
  }

  /**
   * fetch a OpenSocial application metadata
   *
   * @param string $url
   * @param string $culture
   */
  static public function fetchGadgetMetadata($url, $culture)
  {
    $cul = explode('_', $culture);

    $_GET['nocache'] = 1;
    $context = new MetadataGadgetContext(self::arrayToObject(array(
      'country'   => isset($cul[1]) ? $cul[1] : 'ALL',
      'language'  => $cul[0],
      'view'      => 'default',
      'container' => 'openpne',
    )), $url);
    $gadgetServer = new GadgetFactory($context, null);
    $gadgets = $gadgetServer->createGadget();
    return $gadgets;
  }

 /**
  * Check enable home gadget
  *
  * @return boolean
  */
  static public function isEnableHomeGadget()
  {
    $homeGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('gadget');
    foreach ($homeGadgets as $gadgets)
    {
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if (($gadget instanceof Gadget) && $gadget->getName() == 'applicationBoxes')
          {
            return true;
          }
        }
      }
    }
    return false;
  }

 /**
  * Check enable profile gadget
  *
  * @return boolean
  */
  static public function isEnableProfileGadget()
  {
    $profileGadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('profile');
    foreach ($profileGadgets as $gadgets)
    {
      if ($gadgets)
      {
        foreach ($gadgets as $gadget)
        {
          if (($gadget instanceof Gadget) && $gadget->getName() == 'applicationBoxes')
          {
            return true;
          }
        }
      }
    }
    return false;
  }

 /**
  * get consumer key for RSA-SHA1
  *
  * @return OAuthConsumer
  */
  static public function getOAuthConsumerKey()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('opUtil'));
    $baseUrl = sfConfig::get('op_base_url');
    if ('/' === substr($baseUrl, -1))
    {
      $baseUrl = substr($baseUrl, 0, strlen($baseUrl) - 1);
    }
    return $baseUrl.app_url_for('pc_frontend', '@opensocial_certificates');
  }

 /**
  * get http option for Zend_Http_Client
  *
  * @return array
  */
  static public function getHttpOptions()
  {
    $proxyUrl = Shindig_Config::get('proxy');
    $httpOptions = array();
    if (!empty($proxyUrl))
    {
      $httpOptions['adapter'] = 'Zend_Http_Client_Adapter_Proxy';
      $proxy = parse_url($proxyUrl);
      if (isset($proxy['host']))
      {
        $httpOptions['proxy_host'] = $proxy['host'];
      }

      if (isset($proxy['port']))
      {
        $httpOptions['proxy_port'] = $proxy['port'];
      }

      if (isset($proxy['user']))
      {
        $httpOptions['proxy_user'] = $proxy['user'];
      }

      if (isset($proxy['pass']))
      {
        $httpOptions['proxy_pass'] = $proxy['pass'];
      }
    }
    $httpOptions['timeout'] = Shindig_Config::get('curl_connection_timeout');
    $httpOptions['maxredirects'] = 0;
    return $httpOptions;
  }

 /**
  * getProxyHeaders
  *
  * @param sfWebRequest $request
  * @param boolean      $isStripUid
  * @return array
  */
  static public function getProxyHeaders($request, $isStripUid = true)
  {
    $results = array();
    if ($request->getHttpHeader('User-Agent'))
    {
      $userAgent = $request->getHttpHeader('User-Agent');
      if ($isStripUid)
      {
        if (preg_match('#^(DoCoMo/1\.0.*)/(ser.*)$#', $userAgent, $match))
        {
          $userAgent = $match[1];
        }
        elseif (preg_match('#^(DoCoMo/2\.0) (.*)\((.*);(ser.*)\)$#', $userAgent, $match))
        {
          $userAgent = $match[1].' '.$match[2].'('.$match[3].')';
        }
        elseif (preg_match('#^((SoftBank|Vodafone|J-PHONE)/.*/.*)(/SN\S*) (.*)$#', $userAgent, $match))
        {
          $userAgent = $match[1].' '.$match[4];
        }
      }
      $results['User-Agent'] = $userAgent;
    }

    if (!$isStripUid)
    {
      $headerNames = array('X-DCMGUID', 'X-UP-SUBNO','X-JPHONE-UID');
      foreach ($headerNames as $name)
      {
        if ($request->getHttpHeader($name))
        {
          $results[$name] = $request->getHttpHeader($name);
        }
      }
    }

    $pathArray = $request->getPathInfoArray();

    foreach ($pathArray as $name => $value)
    {
      if (preg_match('/^HTTP_(X_(UP|JPHONE)_.*)$/', $name, $match))
      {
        $name = strtr($match[1], '_', '-');
        if ($name !== 'X-JPHONE-UID' && $name !== 'X-UP-SUBNO')
        {
          $results[$name] = $value;
        }
      }
    }

    $name = 'X-S-DISPLAY-INFO';
    if ($request->getHttpHeader($name))
    {
      $results[$name] = $request->getHttpHeader($name);
    }

    return $results;
  }

  static protected function convertEmojiCallback($matches)
  {
    $o_code    = $matches[1];
    $c_carrier = sfConfig::get('op_opensocial_api_emoji_carrier', 'i');
    $o_carrier = $o_code[0];
    $o_id      = substr($o_code, 2);
    $ktaiEmoji = OpenPNE_KtaiEmoji::getInstance();

    if ($c_carrier == $o_carrier)
    {
      $emojiString = $ktaiEmoji->convert_emoji($o_code, $c_carrier);
      if ($emojiString)
      {
        if ('i' === $c_carrier)
        {
          return mb_convert_encoding($emojiString, 'UTF-8', 'SJIS-win');
        }

        return $emojiString;
      }
    }
    elseif (isset($ktaiEmoji->relation_list[$o_carrier][$c_carrier][$o_id]))
    {
      return self::convertEmoji($ktaiEmoji->relation_list[$o_carrier][$c_carrier][$o_id]);
    }

    return '〓';
  }

  static protected function convertEmoji($str)
  {
    $pattern = '/\[([ies]:[0-9]{1,3})\]/';
    return preg_replace_callback($pattern, array(self, 'convertEmojiCallback'), $str);
  }

 /**
  * convertEmojiForApi
  *
  * @param string $str
  * @return string
  */
  static public function convertEmojiForApi($str)
  {
    if (sfConfig::get('op_opensocial_api_is_strip_emoji', false))
    {
      $pattern = '/\[([ies]:[0-9]{1,3})\]/';
      return preg_replace($pattern, '', $str);
    }
    elseif (sfConfig::get('op_opensocial_api_is_convert_emoji', false))
    {
      return self::convertEmoji($str);
    }

    return $str;
  }
}
