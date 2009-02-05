<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opensocial actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opensocialActions extends opOpenSocialServletActions
{
 /**
  * Executes certificates action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeCertificates(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $class = new CertServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }
}
