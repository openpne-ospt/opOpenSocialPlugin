<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialPluginFrontendRouteCollection
 *
 * @package    opOpenSocialPlugin
 * @subpackage routing
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
abstract class opOpenSocialPluginFrontendRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);

    $this->routes = $this->generateRoutes();
    $this->routes += $this->generateNoDefaults();
  }

  protected function generateRoutes()
  {
    return array(
      'application' => new sfRoute(
        '/application',
        array('module' => 'application', 'action' => 'list')
      ),
      'my_application_list' => new sfRoute(
        '/application/list',
        array('module' => 'application', 'action' => 'list')
      ),
      'application_gallery' => new sfRoute(
        '/application/gallery',
        array('module' => 'application', 'action' => 'gallery')
      ),
      'application_add' => new sfDoctrineRoute(
        '/application/add/:id',
        array('module' => 'application', 'action' => 'add'),
        array('id' => '\d+', 'sf_method' => array('get', 'post')),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_remove' => new sfDoctrineRoute(
        '/application/remove/:id',
        array('module' => 'application', 'action' => 'remove'),
        array('id' => '\d+', 'sf_method' => array('post', 'get')),
        array('model' => 'MemberApplication', 'type' => 'object')
      ),
      'application_info' => new sfDoctrineRoute(
        '/application/info/:id',
        array('module' => 'application', 'action' => 'info'),
        array('id' => '\d+'),
        array('model' => 'Application', 'type' => 'object')
      ),
      'application_invite' => new sfDoctrineRoute(
        '/application/invite/:id',
        array('module' => 'application', 'action' => 'invite'),
        array('id' => '\d+', 'sf_method' => array('get', 'post')),
        array('model' => 'MemberApplication', 'type' => 'object')
      ),
    );
  }

  protected function generateNoDefaults()
  {
    return array(
      'application_nodefaults' => new sfRoute(
        '/application/*',
        array('module' => 'default', 'action' => 'error')
      )
    );
  }
}
