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
    $cul = split('_', $culture);

    $request = self::arrayToObject(array(
      'context' => array(
        'country'   => isset($cul[1]) ? $cul[1] : 'ALL',
        'language'  => $cul[0],
        'view'      => 'default',
        'container' => 'openpne'
      ),
      'gadgets' => array(array('url' => $url, 'moduleId' => 1))
    ));
    $gadgetSigner = Shindig_Config::get('security_token');
    $_GET['nocache'] = 1;
    $handler = new MetadataHandler();
    $response = $handler->process($request);

    if (!is_array($response) || count($response) <= 0)
    {
      throw new Exception();
    }
    if (isset($response[0]['errors']))
    {
      throw new Exception();
    }

    return $response[0];
  }
}
