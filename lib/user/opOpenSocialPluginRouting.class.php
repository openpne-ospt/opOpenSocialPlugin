<?php

/**
 * opOpenSocialPluginRouting
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 */
class opOpenSocialPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();
    $routing->prependRoute('application_list_route',
      new sfRoute(
        '/application/webprefix.js' ,
        array(
          'module' => 'application',
          'action' => 'js',
        )
    ));
  }
}
