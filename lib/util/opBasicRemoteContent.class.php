<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opBasicRemoteContent
 * 
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 *
 */
class opBasicRemoteContent extends BasicRemoteContent
{
  public function __construct(RemoteContentFetcher $basicFetcher = null, $signingFetcherFactory = null, $signer = null)
  {
    $basicFetcher = $basicFetcher ? $basicFetcher : new opBasicRemoteContentFetcher();
    parent::__construct($basicFetcher, $signingFetcherFactory, $signer);
  }
}
