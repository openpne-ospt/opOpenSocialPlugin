<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
