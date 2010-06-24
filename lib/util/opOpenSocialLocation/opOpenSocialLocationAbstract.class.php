<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationAbstract
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
abstract class opOpenSocialLocationAbstract
{
  protected
    $request = null;

  public function __construct(opWebRequest $request)
  {
    $this->request = $request;
  }

  abstract public function renderGetLocationCell($callback, array $params);

  abstract public function renderGetLocationGps($callback, array $params);

  public function fetchLocation()
  {
    $request = $this->request;
    $lat = $request->hasParameter('lat') ? $request->getParameter('lat') : $request->getParameter('LAT');
    if (preg_match('/^([+\-NS])?(\d{1,2})\.(\d{1,2})\.(\d{1,2})\.(\d{1,2})\d*$/', $lat, $match))
    {
      if ('N' == $match[1] || !$match[1])
      {
        $lat = '+';
      }
      elseif ('S' == $match[1])
      {
        $lat = '-';
      }
      else
      {
        $lat = $match[1];
      }

      for ($i = 2; $i < count($match); $i++)
      {
        $lat .= sprintf('%02d', (int)$match[$i]);
        if ($i != count($match) - 1)
        {
          $lat .= '.';
        }
      }
    }
    else
    {
      $lat = '';
    }

    $lon = $request->hasParameter('lon') ? $request->getParameter('lon') : $request->getParameter('LON');
    if (preg_match('/^([+\-EW])?(\d{1,3})\.(\d{1,2})\.(\d{1,2})\.(\d{1,2})\d*$/', $lon, $match))
    {
      if ('E' == $match[1] || !$match[1])
      {
        $lon = '+';
      }
      elseif ('W' == $match[1])
      {
        $lon = '-';
      }
      else
      {
        $lon = $match[1];
      }

      $lon .= sprintf('%03d', (int)$match[2]);
      for ($i = 3; $i < count($match); $i++)
      {
        $lon .= '.';
        $lon .= sprintf('%02d', (int)$match[$i]);
      }
    }
    else
    {
      $lon = '';
    }

    return array(
      'lat' => $lat,
      'lon' => $lon,
      'geo' => ($lat && $lon) ? 'wgs84' : ''
    );
  }

  public function getParameter($name, $default = null)
  {
    return $this->request->getParameter($name, $default);
  }
}
