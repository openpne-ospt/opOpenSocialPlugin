<?php
class opOpenSocialPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();
    $routing->prependRoute('application_list_route',
      new sfRoute(
        '/application/js.js' ,
        array(
          'module' => 'application',
          'action' => 'js',
        )
    ));
  }
}