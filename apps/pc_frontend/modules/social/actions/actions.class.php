<?php

/**
 * social actions.
 *
 * @package    OpenPNE
 * @subpackage saOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class socialActions extends sfActions
{
 /**
  * Executes rpc action
  *
  * @param sfRequest $request A request object
  */
  public function executeRpc($request)
  {
    $class = new JsonRpcServlet();
    try{
      if ($request->isMethod('get'))
      {
        $class->doGet();
      }
      else
      {
        $class->doPost();
      }
    }
    catch (SocialSpiException $e)
    {
    }
    return sfView::SUCCESS;
  }
}
