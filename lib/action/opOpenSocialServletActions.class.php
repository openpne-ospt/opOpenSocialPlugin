<?php

/**
 * base actions class for the gadgets and social modules
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@tejimaya.net> 
 */

abstract class opOpenSocialServletActions extends sfActions
{
  /**
   * servletExecute
   *
   * @param $servlet A servlet object
   */
  protected function servletExecute($servlet)
  {
    if (!($servlet instanceof HttpServlet) && !($servlet instanceof ApiServlet))
    {
      throw new LogicException();
    }

    $request = $this->getRequest();
    $method = "";
    switch($request->getMethod())
    {
      case sfRequest::GET  : $method = 'doGet';  break;
      case sfRequest::POST : $method = 'doPost'; break;
      case sfRequest::PUT  : $method = 'doPut'; break;
      case sfRequest::DELETE : $method = 'doDelete'; break;
    }
    if (is_callable(array($servlet, $method)))
    {
      $servlet->$method();
    }
    else
    {
      header("HTTP/1.0 405 Method Not Allowed");
      echo "<html><body><h1>405 Method Not Allowed</h1></body></html>";
    }
  }
}
