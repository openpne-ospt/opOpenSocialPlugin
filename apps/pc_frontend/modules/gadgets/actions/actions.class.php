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
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIfr($request)
  {
    $class = new GadgetRenderingServlet();
    $class->doGet();
    return sfView::SUCCESS;
  }
}
