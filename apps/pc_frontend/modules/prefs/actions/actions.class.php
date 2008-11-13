<?php

/**
 * prefs actions.
 *
 * @package    OpenPNE
 * @subpackage saOpenSocialPlugin
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
    if ($request->isMethod('post') || !$request->hasParameter('st') || !$request->hasParameter('name') || ! $request->hasParameter('value'))
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
      $token = BasicSecurityToken::createFromToken($st, 60);
      $app_id = $token->getAppId();
      $viewer = $token->getViewerId();
      $criteria = new Criteria(ApplicationSettingPeer::DATABASE_NAME);
      $criteria->add(ApplicationSettingPeer::APPLICATION_ID,$app_id);
      $criteria->add(ApplicationSettingPeer::MEMBER_ID,$viewer);
      $criteria->add(ApplicationSettingPeer::NAME,$key);
      $app_setting = ApplicationSettingPeer::doSelectOne($criteria);
      if (empty($app_setting))
      {
        $app_setting = new ApplicationSetting();
        $app_setting->setApplicationId($app_id);
        $app_setting->setMemberId($viewer);
        $app_setting->setName($key);
      }
      $app_setting->setValue($value);
      $app_setting->save();
    }
    catch (Exception $e)
    {
      header("HTTP/1.0 400 Bad Request",true);
      echo "<html><body><h1>400 - Bad Request - ". $e->getMessage() ."</h1></body></html>";
    }
    return sfView::NONE;
  }
}
