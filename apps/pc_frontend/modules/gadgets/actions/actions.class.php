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
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class gadgetsActions extends opOpenSocialServletActions
{
  protected function check()
  {
    if (!(
      sfConfig::get('op_opensocial_is_allow_inner_container', false) ||
      Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig', false)
    ))
    {
      $this->forward('gadgets', 'deny');
    }
  }

  /**
   * Execute js action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeJs(sfWebRequest $request)
  {
    $class = new JsServlet();
    self::servletExecute($class);
    exit;
  }

  /**
   * Execute proxy action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeProxy(sfWebRequest $request)
  {
    $this->check();
    sfConfig::set('sf_web_debug', false);
    $class = new ProxyServlet();
    self::servletExecute($class);
    exit;
  }

  /**
   * Execute makeRequest action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeMakeRequest(sfWebRequest $request)
  {
    $this->check();
    sfConfig::set('sf_web_debug', false);
    $class = new MakeRequestServlet();
    self::servletExecute($class);
    exit;
  }

 /**
  * Executes ifr action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIfr(sfWebRequest $request)
  {
    $this->check();
    $class = new GadgetRenderingServlet();
    self::servletExecute($class);
    exit;
  }

  /**
   * Execute metadata action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeMetadata(sfWebRequest $request)
  {
    $this->check();
    $class = new MetadataServlet();
    self::servletExecute($class);
    exit;
  }

 /**
  * Execute deny action
  *
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDeny(sfWebRequest $request)
  {
  }
}
