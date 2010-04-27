<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application components
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationComponents extends sfComponents
{
 /**
  * Executes caution about application invite
  *
  * @param sfWebRequest $request
  */
  public function executeCautionAboutApplicationInvite(sfWebRequest $request)
  {
    $this->count = Doctrine::getTable('ApplicationInvite')->getInvitesByToMemberId(null, null, true)->count();
  }
}
