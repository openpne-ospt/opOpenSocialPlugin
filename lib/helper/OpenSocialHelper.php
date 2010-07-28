<?php

/**
 * OpenSocialHelper
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

sfProjectConfiguration::getActive()->loadHelpers(array('Url', 'Javascript', 'opJavascript'));

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

function op_include_application_setting($view, $hasApp)
{
  static $isFirst = true;
  if ($isFirst)
  {
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $opOpenSocialContainerConfig->generateAndSave();

    $response = sfContext::getInstance()->getResponse();
    $response->addJavascript('/sfProtoculousPlugin/js/prototype');
    $response->addJavascript('/opOpenSocialPlugin/js/tabs-min');
    $response->addJavascript('/opOpenSocialPlugin/js/container');
    $response->addJavascript('/gadgets/js/rpc.js?c=1');
    $response->addJavascript('/opOpenSocialPlugin/js/opensocial-util');

    $request = sfContext::getInstance()->getRequest();
    $isDev   = sfConfig::get('sf_environment') == 'dev';

    $snsUrl  = $request->getUriPrefix().$request->getRelativeUrlRoot();
    $snsUrl .= $isDev ? '/pc_frontend_dev.php' : '';

    $apiUrl  = $request->getUriPrefix().$request->getRelativeUrlRoot().'/api';
    $apiUrl .= $isDev ? '_dev' : '';
    $apiUrl .= '.php';

    echo javascript_tag(sprintf(<<<EOF
gadgets.container = new Container("%s", "%s", "%s", %s);
EOF
  ,$snsUrl, $apiUrl, $view, (($hasApp) ? 'true' : 'false')
));
    echo make_app_setting_modal_box('opensocial_modal_box');
    $isFirst = false;
  }
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
  return link_to_function($text, sprintf("iframeModalBox.open('%s')", url_for($url)));
}

function make_app_setting_modal_box($id)
{
  sfContext::getInstance()->getResponse()->addJavascript(sfConfig::get('sf_prototype_web_dir').'/js/prototype');
  sfContext::getInstance()->getResponse()->addJavascript('util');

  $modalbox = '<div id="'.$id.'" class="modalWall" style="display:none" onclick="iframeModalBox.close(); false;"></div>'
            . '<div id="'.$id.'_contents" class="modalBox" style="display: none;">'
            . '<iframe width="400" height="400" frameborder="0"></iframe>'
            . '</div>';

  $javascript = <<<EOT
var iframeModalBox = new IframeModalBox("%id%");
(function (){
  var contents = $("%id%_contents");
  contents.setStyle(getCenterMuchScreen(contents));
})();
EOT;
  $javascript = preg_replace('/%id%/', $id, $javascript);

  return $modalbox.javascript_tag($javascript);
}
