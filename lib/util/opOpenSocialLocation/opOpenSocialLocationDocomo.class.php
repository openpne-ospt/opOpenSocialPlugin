<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocationDocomo
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocationDocomo extends opOpenSocialLocationAbstract
{
  public function renderGetLocationCell($callback)
  {
    $result = '<form action="http://w1m.docomo.ne.jp/cp/iarea" method="POST">
<input type="hidden" name="ecode" value="OPENAREACODE">
<input type="hidden" name="msn" value="OPENAREAKEY">
<input type="hidden" name="nl" value="'.$callback.'">
<input type="hidden" name="posinfo" value="1">
';

    if (!$this->request->isCookie() && defined('SID') && SID)
    {
      $result .= '<input type="hidden" name="arg1" value="'.SID.'">';
    }

    $result .= '
<input type="submit" value="'.__('Yes').'"><br>
</form>';

    return $result;
  }

  public function renderGetLocationGps($callback)
  {
    $p = '';
    if (!$this->request->isCookie() && defined('SID') && SID)
    {
      $p = SID;
    }

    $result = '<form action="'.$callback.($p ? '?'.$p : '').'" method="POST" lcs>';
    $result .= '<input type="submit" value="'.__('Yes').'"><br>';
    $result .= '</form>';
    return $result;
  }
}
