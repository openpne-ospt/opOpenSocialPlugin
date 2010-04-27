<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialPluginMobileFrontendRouteCollection
 *
 * @package    opOpenSocialPlugin
 * @subpackage routing
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialPluginMobileFrontendRouteCollection extends opOpenSocialPluginFrontendRouteCollection
{
  protected function generateRoutes()
  {
    return parent::generateRoutes() +
      array(
        'application_render' => new sfDoctrineRoute(
          '/application/:id',
          array('module' => 'application', 'action' => 'render'),
          array('id' => '\d+', 'sf_method' => array('get', 'post')),
          array('model' => 'Application', 'type' => 'object')
        ),
      );
  }
}
