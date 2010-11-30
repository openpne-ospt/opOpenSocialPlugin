<?php

$app = 'pc_backend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$xssApp       = Doctrine::getTable('Application')->findOneByUrl('http://example.com/dummy4.xml');

$connection = Doctrine::getTable('Application')->getConnection();
$connection->beginTransaction();

$browser = new opBrowser();
$user = new opTestFunctional($browser, new lime_test(25, new lime_output_color()));

$user
  ->info('login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )));

$user
  ->info('opOpenSocialPlugin')
  // CSRF
  ->post('opOpenSocialPlugin', array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/containerConfig')
  // CSRF
  ->post('opOpenSocialPlugin/containerConfig', array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/add')
  // CSRF
  ->post('opOpenSocialPlugin/add', array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/list')
  // XSS
  ->get('opOpenSocialPlugin/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$user
  ->info('opOpenSocialPlugin/info')
  // XSS
  ->get('opOpenSocialPlugin/info/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'description')
    ->isAllEscapedData('ApplicationTranslation', 'screenshot')
    ->isAllEscapedData('ApplicationTranslation', 'thumbnail')
    ->isAllEscapedData('ApplicationTranslation', 'author')
    ->isAllEscapedData('ApplicationTranslation', 'author_aboutme')
    ->isAllEscapedData('ApplicationTranslation', 'author_affiliation')
    ->isAllEscapedData('ApplicationTranslation', 'author_photo')
    ->isAllEscapedData('ApplicationTranslation', 'author_quote')
    ->isAllEscapedData('Member', 'name')
  ->end()
  // CSRF
  ->post('opOpenSocialPlugin/info/'.$xssApp->id, array('sf_method' => 'put'))
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/delete')
  ->get('opOpenSocialPlugin/delete/'.$xssApp->id)
  // XSS
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('opOpenSocialPlugin/delete/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/update')
  // CSRF
  ->post('opOpenSocialPlugin/update/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/active')
  // CSRF
  ->post('opOpenSocialPlugin/active/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('opOpenSocialPlugin/inactivate')
  // CSRF
  ->post('opOpenSocialPlugin/inactivate/'.$xssApp->id, array())
  ->checkCSRF();

$xssApp->setIsActive(false);
$xssApp->save();

$user
  ->info('opOpenSocialPlugin/inactiveList')
  // XSS
  ->get('opOpenSocialPlugin/inactiveList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$xssApp->setIsActive(true);
$xssApp->save();


$user
  ->info('opOpenSocialPlugin/updateConsumerSecret')
  // XSS
  ->get('opOpenSocialPlugin/updateConsumerSecret/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('opOpenSocialPlugin/updateConsumerSecret/'.$xssApp->id, array())
  ->checkCSRF();


$user
  ->info('opOpenSocialPlugin/deleteConsumerSecret')
  // XSS
  ->get('opOpenSocialPlugin/deleteConsumerSecret/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('opOpenSocialPlugin/deleteConsumerSecret/'.$xssApp->id, array())
  ->checkCSRF();

$connection->rollback();
