<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * gadgets actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class gadgetsActions extends opOpenSocialServletActions
{
  /**
   * Execute files action
   *
   * @param sfRequest $request A request object
   */
  public function executeFiles($request)
  {
    $class = new FilesServlet();
    self::servletExecute($class);
  }

  /**
   * Execute js action
   *
   * @param sfRequest $request A request object
   */
  public function executeJs($request)
  {
    $class = new JsServlet();
    self::servletExecute($class);
  }

  /**
   * Execute proxy action
   *
   * @param sfRequest $request A request object
   */
  public function executeProxy($request)
  {
    $class = new ProxyServlet();
    self::servletExecute($class);
  }

  /**
   * Execute makeRequest action
   *
   * @param sfRequest $request A request object
   */
  public function executeMakeRequest($request)
  {
    $_GET = $request->getParameterHolder()->getAll();
    $_GET['output'] = 'js';
    $class = new ProxyServlet();
    self::servletExecute($class);
  }

 /**
  * Executes ifr action
  *
  * @param sfRequest $request A request object
  */
  public function executeIfr($request)
  {
    $class = new opGadgetRenderingServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }

  /**
   * Execute metadata action
   *
   * @param sfRequest $request A request object
   */
  public function executeMetadata($request)
  {
    $class = new MetadataServlet();
    self::servletExecute($class);
    return sfView::NONE;
  }
}
