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

/**
 * include application information box
 *
 * @param integer     $id
 * @param Application $application a instance of the Application
 * @param integer     $mid         a module id
 * @param boolean     $isOwner 
 */
function op_include_application_information_box($id, $application, $mid = null, $isOwner = false)
{
  $params = array(
    'id'          => $id,
    'application' => $application,
    'mid'         => $mid,
    'isOwner'     => $isOwner
  );
  include_partial('application/informationBox', $params);
}

function op_include_applications($view, $params = array())
{
  static $isFirst = true;
  
  if ($isFirst)
  {
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $opOpenSocialContainerConfig->generateAndSave();

    echo make_app_setting_modal_box('opensocial_modal_box');
    $isFirst = false;
  }

  include_partial('application/'.$view.'Application', $params);
}

function link_to_app_setting($text, $mid, $isReload = false)
{
  $response = sfContext::getInstance()->getResponse();
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/builder');
  $response->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/effects');
  $response->addJavascript('/opOpenSocialPlugin/js/opensocial-util');
  $url = '@application_setting?id='.$mid;
  if ($isReload)
  {
    $url = $url.'&is_reload=1';
  }
  return link_to_function($text,"showIframeModalBox('opensocial_modal_box','".url_for($url)."')");
}

function make_app_setting_modal_box($id)
{
  return make_modal_box($id, '<iframe width="400" height="400" frameborder="0"></iframe>');
}
