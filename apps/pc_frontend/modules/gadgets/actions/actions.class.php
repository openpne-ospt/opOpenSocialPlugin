<?php

/**
 * gadgets actions.
 *
 * @package    OpenPNE
 * @subpackage saOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class gadgetsActions extends sfActions
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
    }
    else
    {
      header("HTTP/1.0 405 Method Not Allowed");
      echo "<html><body><h1>405 Method Not Allowed</h1></body></html>";
    }
  }
  /**
   * Execute files action
   *
   * @param sfRequest $request A request object
   */
  public function executeFiles($request)
  {
    $class = new FilesServlet();
    self::ex($request, $class);
  }

  /**
   * Execute js action
   *
   * @param sfRequest $request A request object
   */
  public function executeJs($request)
  {
    $class = new JsServlet();
    self::ex($request, $class);
  }

  /**
   * Execute proxy action
   *
   * @param sfRequest $request A request object
   */
  public function executeProxy($request)
  {
    $class = new ProxyServlet();
    self::ex($request, $class);
  }

  /**
   * Execute makeRequest action
   *
   * @param sfRequest $request A request object
   */
  public function executeMakeRequest($request)
  {
    $_GET['output'] = 'js';
    $class = new ProxyServlet();
    self::ex($request, $class);
  }

 /**
  * Executes ifr action
  *
  * @param sfRequest $request A request object
  */
  public function executeIfr($request)
  {
    $class = new GadgetRenderingServlet();
    self::ex($request, $class);
  }

  /**
   * Execute metadata action
   *
   * @param sfRequest $request A request object
   */
  public function executeMetadata($request)
  {
    $class = new MetadataServlet();
    self::ex($request, $class);
  }
}
