<?php
/**
 * OpenSocialHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

require_once sfConfig::get('sf_symfony_lib_dir').'/helper/UrlHelper.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/plugins/sfProtoculousPlugin/lib/helper/JavascriptHelper.php';
require_once sfConfig::get('sf_lib_dir').'/helper/opJavascriptHelper.php';

echo make_app_setting_modal_box('opensocial_modal_box');

/**
 * include application information box
 *
 * @param integer     $id
 * @param integer     $aid         An application id
 * @param integer     $mid         A module id
 * @param boolean     $isOwner
 * @param Application $application 
 */
function include_application_information_box($id, $aid, $mid = 0, $isOwner = false , $application)
{
  $params = array(
    'id'          => $id,
    'aid'         => $aid,
    'mid'         => $mid,
    'isOwner'     => $isOwner,
    'application' => $application,
  );
  include_partial('application/informationBox', $params);
}

function link_to_app_setting($text, $mid, $isReload = false)
{
  $response = sfContext::getInstance()->getResponse();
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/builder');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/effects');
  $response->addJavascript('/opOpenSocialPlugin/js/opensocial-util');
  $url = 'application/setting?id='.$mid;
  if ($isReload)
  {
    $url = $url.'&is_reload=1';
  }
  return link_to_function($text,"showIframeModalBox('opensocial_modal_box','".url_for($url)."')");
}

function make_app_setting_modal_box($id)
{
  return make_modal_box($id, '<iframe width="400" height="400" frameborder="0"></iframe>', 400, 400);
}
