<?php

/**
 * social actions.
 *
 * @package    OpenPNE
 * @subpackage saOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class socialActions extends opOpenSocialServletActions
{
 /**
  * Executes rpc action
  *
  * @param sfRequest $request A request object
  */
  public function executeRpc($request)
  {
    sfConfig::set('sf_web_debug',false);
    $class = new JsonRpcServlet();
    try{
      error_reporting(0);
      self::servletExecute($class);
    }
    catch (SocialSpiException $e)
    {
    }
    return sfView::NONE;
  }
}
