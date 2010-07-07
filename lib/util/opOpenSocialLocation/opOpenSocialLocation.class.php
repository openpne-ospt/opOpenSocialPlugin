<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialLocation
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialLocation
{
  public static function createInstance(sfWebRequest $request, sfSecurityUser $user)
  {
    $mobile = $request->getMobile();

    $className = 'opOpenSocialLocation';
    if ($mobile->isDoCoMo())
    {
      $className .= 'Docomo';
    }
    elseif ($mobile->isEZweb())
    {
      $className .= 'EZweb';
    }
    elseif ($mobile->isSoftbank() && $mobile->isType3GC())
    {
      $className .= 'Softbank';
    }
    else
    {
      throw new LogicException("This UserAgent isn't supported.");
    }

    return new $className($request, $user);
  }
}
