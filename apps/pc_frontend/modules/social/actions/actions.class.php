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
    ob_start();
    try{
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
