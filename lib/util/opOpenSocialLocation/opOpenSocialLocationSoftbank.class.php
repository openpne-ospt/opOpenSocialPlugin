<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationSoftbank
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocationSoftbank extends opOpenSocialLocationAbstract
{
  public function renderGetLocationCell($callback)
  {
    $result = '<form action="location:cell?url='.$callback.'" method="POST">
<input type="submit" value="'.__('Yes').'"><br>
</form>';

    return $result;
  }

  public function renderGetLocationGps($callback)
  {
    $result = '<form action="location:gps?url='.$callback.'" method="POST">
<input type="submit" value="'.__('Yes').'"><br>
</form>';

    return $result;
  }

  public function fetchLocation()
  {
    $request = $this->request;
    $pos = $request->hasParameter('pos') ? $request->getParameter('pos') : $request->getParameter('POS');
    if (preg_match('/^([NS])(\d{1,2}\.\d{1,2}\.\d{1,2}\.)(\d{1,2})\d*([EW])(\d{1,3}\.\d{1,2}\.\d{1,2}\.)(\d{1,2})\d*$/', $pos, $match))
    {
      $request->setParameter('lat', $match[1].$match[2].$match[3]);
      $request->setParameter('lon', $match[4].$match[5].$match[6]);
    }
    return parent::fetchLocation();
  }
}
