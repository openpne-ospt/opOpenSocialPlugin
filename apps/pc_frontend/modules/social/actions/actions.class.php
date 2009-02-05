<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * social actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class socialActions extends opOpenSocialServletActions
{
 /**
  * Executes rpc action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeRpc(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug',false);
    $class = new JsonRpcServlet();
    ob_start();
    try
    {
      self::servletExecute($class);
    }
    catch (SocialSpiException $e)
    {
    }
    $this->social_data = ob_get_contents();
    ob_end_clean();
    return sfView::SUCCESS;
  }
}
