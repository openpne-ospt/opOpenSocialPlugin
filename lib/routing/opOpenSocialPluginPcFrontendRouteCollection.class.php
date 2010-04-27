<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialPluginPcFrontendRouteCollection
 *
 * @package    opOpenSocialPlugin
 * @subpackage routing
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class opOpenSocialPluginPcFrontendRouteCollection extends opOpenSocialPluginFrontendRouteCollection
{
  protected function generateRoutes()
  {
    return parent::generateRoutes() +
      array(
        'application_list' => new sfDoctrineRoute(
          '/application/list/:id',
          array('module' => 'application', 'action' => 'list'),
          array('id' => '\d+'),
          array('model' => 'Member', 'type' => 'object')
        ),
        'application_setting' => new sfDoctrineRoute(
          '/application/setting/:id',
          array('module' => 'application', 'action' => 'setting'),
          array('id' => '\d+', 'sf_method' => array('get', 'post')),
          array('model' => 'MemberApplication', 'type' => 'object')
        ),
        'application_gallery' => new sfRoute(
          '/application/gallery',
          array('module' => 'application', 'action' => 'gallery')
        ),
        'application_canvas' => new sfDoctrineRoute(
          '/application/canvas/:id',
          array('module' => 'application', 'action' => 'canvas'),
          array('id' => '\d+'),
          array('model' => 'MemberApplication', 'type' => 'object')
        ),
        'application_member' => new sfDoctrineRoute(
          '/application/member/:id',
          array('module' => 'application', 'action' => 'member'),
          array('id' => '\d+'),
          array('model' => 'Application', 'type' => 'object')
        ),
        'application_install' => new sfRoute(
          '/application/install',
          array('module' => 'application', 'action' => 'install'),
          array('sf_method' => array('get', 'post'))
        ),
        'application_installed_list' => new sfRoute(
          '/application/installedList',
          array('module' => 'application', 'action' => 'installedList')
        ),
        'application_update' => new sfDoctrineRoute(
          '/application/update/:id',
          array('module' => 'application', 'action' => 'update'),
          array('id' => '\d+', 'sf_method' => 'post'),
          array('model' => 'Application', 'type' => 'object')
        ),
        'application_delete' => new sfDoctrineRoute(
          '/application/delete/:id',
          array('module' => 'application', 'action' => 'delete'),
          array('id' => '\d+', 'sf_method' => array('get', 'post')),
          array('model' => 'Application', 'type' => 'object')
        ),
        'application_sort' => new sfRoute(
          '/application/sort',
          array('module' => 'application', 'action' => 'sort'),
          array('sf_method' => array('post'))
        ),
        'application_invite_list' => new sfDoctrineRoute(
          '/application/inviteList/:id',
          array('module' => 'application', 'action' => 'inviteList'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'MemberApplication', 'type' => 'object')
        ),
        'application_invite_post' => new sfDoctrineRoute(
          '/application/invitePost/:id',
          array('module' => 'application', 'action' => 'invitePost'),
          array('id' => '\d+', 'sf_method' => array('post')),
          array('model' => 'MemberApplication', 'type' => 'object')
        ),
        'application_show_consumer_secret' => new sfDoctrineRoute(
          '/application/showConsumerSecret/:id',
          array('module' => 'application', 'action' => 'showConsumerSecret'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'Application', 'type' => 'object')
        ),
        'application_update_consumer_secret' => new sfDoctrineRoute(
          '/application/updateConsumerSecret/:id',
          array('module' => 'application', 'action' => 'updateConsumerSecret'),
          array('id' => '\d+', 'sf_method' => array('get', 'post')),
          array('model' => 'Application', 'type' => 'object')
        ),
        'application_delete_consumer_secret' => new sfDoctrineRoute(
          '/application/deleteConsumerSecret/:id',
          array('module' => 'application', 'action' => 'deleteConsumerSecret'),
          array('id' => '\d+', 'sf_method' => array('get', 'post')),
          array('model' => 'Application', 'type' => 'object')
        ),

        'gadgets_js' => new sfRoute(
          '/gadgets/js/*',
          array('module' => 'gadgets', 'action' => 'js')
        ),
        'gadgets_proxy' => new sfRoute(
          '/gadgets/proxy/*',
          array('module' => 'gadgets', 'action' => 'proxy')
        ),
        'gadgets_makeRequest' => new sfRoute(
          '/gadgets/makeRequest/*',
          array('module' => 'gadgets', 'action' => 'makeRequest')
        ),
        'gadgets_ifr' => new sfRoute(
          '/gadgets/ifr/*',
          array('module' => 'gadgets', 'action' => 'ifr')
        ),
        'gadgets_metadata' => new sfRoute(
          '/gadgets/metadata/*',
          array('module' => 'gadgets', 'action' => 'metadata')
        ),

        'opensocial_certificates' => new sfRoute(
          '/opensocial/certificates',
          array('module' => 'opensocial', 'action' => 'certificates')
        ),
      );
  }

  protected function generateNoDefaults()
  {
    return parent::generateNoDefaults() +
      array(
        'gadgets_nodefaults' => new sfRoute(
          '/gadgets/*',
          array('module' => 'default', 'action' => 'error')
        ),
        'opensocial_nodefault' => new sfRoute(
          '/opensocial/*',
          array('module' => 'default', 'action' => 'error')
        )
      );
  }
}
