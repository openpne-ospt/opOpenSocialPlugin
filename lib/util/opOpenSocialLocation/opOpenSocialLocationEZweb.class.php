<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationEZweb
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocationEZweb extends opOpenSocialLocationAbstract
{
  public function renderGetLocationCell($callback)
  {
    $result = '<form action="device:location?url='.urlencode($callback).'" method="POST">
<input type="submit" value="'.__('Yes').'"><br>
</form>';

    return $result;
  }

  public function renderGetLocationGps($callback)
  {
    $result = '<form action="device:gpsone?url='.urlencode($callback).'&ver=1&datum=0&unit=0&acry=0&number=0" method="POST">
<input type="submit" value="'.__('Yes').'"><br>
</form>
';

    return $result;
  }
}
