<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * prefs actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class prefsActions extends sfActions
{
  /**
   * Executes set action
   * 
   * @param sfRequest $request A request object
   */
  public function executeSet($request)
  {
    $response = $this->getResponse();
    if ($request->isMethod(sfRequest::POST) || !$request->hasParameter('st') || !$request->hasParameter('name') || ! $request->hasParameter('value'))
    {
      header("HTTP/1.0 400 Bad Request",true);
      echo "<html><body><h1>400 - Bad Request</h1></body></html>";
      return sfView::NONE;
    }
    try
    {
      $st    = urldecode(base64_decode($request->getParameter('st')));
      $key   = $request->getParameter('name');
      $value = $request->getParameter('value');
      $token = opBasicSecurityToken::createFromToken($st, 60);
      $modId = $token->getModuleId();
      $viewer = $token->getViewerId();
      
      $appSetting = MemberApplicationSettingPeer::retrieveByMemberApplicationIdAndName($modId, $key);

      if (empty($appSetting))
      {
        $appSetting = new MemberApplicationSetting();
        $appSetting->setMemberApplicationId($modId);
        $appSetting->setName($key);
      }
      $appSetting->setValue($value);
      $appSetting->save();
    }
    catch (Exception $e)
    {
      header("HTTP/1.0 400 Bad Request",true);
      echo "<html><body><h1>400 - Bad Request - ".$e->getMessage()."</h1></body></html>";
    }
    return sfView::NONE;
  }
}
