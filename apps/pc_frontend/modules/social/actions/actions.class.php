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
  * ex
  * 
  * @param sfRequest $request A request object
  * @param HttpServlet $servlet A servlet object
  */
  protected function ex($request, $servlet)
  {
    $method = "";
    switch($request->getMethod())
    {
      case sfRequest::GET  : $method = 'doGet';  break;
      case sfRequest::POST : $method = 'doPost'; break;
    }
    if (is_callable(array($servlet, $method)))
    {
      $servlet->$method();
    }else
    {
      header("HTTP/1.0 405 Method Not Allowed");
      echo "<html><body><h1>405 Method Not Allowed</h1></body></html>";    }
  }
 /**
  * Executes rpc action
  *
  * @param sfRequest $request A request object
  */
  public function executeRpc($request)
  {
    $class = new JsonRpcServlet();

    try{
      self::ex($request, $class);
    }
    catch (SocialSpiException $e)
    {
    }
    return sfView::NONE;
  }
}
