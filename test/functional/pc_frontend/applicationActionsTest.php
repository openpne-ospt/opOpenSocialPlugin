<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';

$member1      = Doctrine::getTable('Member')->findOneByName('A');

$xssMember    = Doctrine::getTable('Member')->findOneByName("<&\"'>Member.name ESCAPING HTML TEST DATA");
$xssApp       = Doctrine::getTable('Application')->findOneByUrl('http://example.com/dummy4.xml');
$xssMemberApp = Doctrine::getTable('MemberApplication')->findOneByMemberIdAndApplicationId($xssMember->id, $xssApp->id);

$connection = Doctrine::getTable('Application')->getConnection();
$connection->beginTransaction();

$browser = new opBrowser();
$user = new opTestFunctional($browser, new lime_test(43, new lime_output_color()));

$user
  ->info('application/add')
  // XSS
  ->login('sns@example.com', 'password')
  ->get('application/add/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('application/add/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('application/canvas')
  // XSS
  ->login('sns-xss@example.com', 'password')
  ->get('application/canvas/'.$xssMemberApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$user
  ->info('application/delete')
  // XSS
  ->get('application/delete/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('application/delete/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('application/deleteConsumerSecret')
  // XSS
  ->get('application/deleteConsumerSecret/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('application/deleteConsumerSecret/'.$xssApp->id, array())
  ->checkCSRF();

$user
  ->info('application/gallery')
  // XSS
  ->get('application/gallery')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'description')
    ->isAllEscapedData('ApplicationTranslation', 'thumbnail')
    ->isAllEscapedData('ApplicationTranslation', 'author')
  ->end();

$user
  ->info('application/info')
  // XSS
  ->get('application/info/'.$xssApp->id)
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
  ->end();

$user
  ->info('application/install')
  //CSRF
  ->post('application/install', array())
  ->checkCSRF();

$user
  ->info('application/installedList')
  // XSS
  ->get('application/installedList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'description')
    ->isAllEscapedData('ApplicationTranslation', 'thumbnail')
    ->isAllEscapedData('ApplicationTranslation', 'author')
  ->end();

$mid = $xssApp->addToMember($member1);
$user
  ->login('sns@example.com', 'password')
  ->info('application/invite')
  // XSS
  ->get('application/invite/'.$mid)
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end();

$user
  ->info('application/inviteList')
  // XSS
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->get('application/inviteList/'.$mid)
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end();

$user
  ->info('application/invitePost')
  // CSRF
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('application/invitePost/'.$mid, array())
  ->checkCSRF();

$user
  ->info('application/list')
  // XSS
  ->login('sns-xss@example.com', 'password')
  ->get('application/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'description')
    ->isAllEscapedData('ApplicationTranslation', 'thumbnail')
    ->isAllEscapedData('ApplicationTranslation', 'author')
  ->end();

$user
  ->info('application/member')
  // XSS
  ->get('application/list')
  ->get('application/member/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end();

$user
  ->info('application/remove')
  // XSS
  ->get('application/remove/'.$xssMemberApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('application/remove/'.$xssMemberApp->id, array())
  ->checkCSRF();

$user
  ->info('application/setting')
  // XSS
  ->get('application/setting/'.$xssMemberApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'settings')
  ->end()
  // CSRF
  ->post('application/setting/'.$xssMemberApp->id)
  ->checkCSRF();

$user
  ->info('application/sort')
  // CSRF
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('application/sort', array())
  ->checkCSRF();

$user
  ->info('application/update')
  // CSRF
  ->post('application/update/'.$xssApp->id)
  ->checkCSRF();

$user
  ->info('application/updateConsumerSecret')
  // XSS
  ->get('application/updateConsumerSecret/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end()
  // CSRF
  ->post('application/updateConsumerSecret/'.$xssApp->id, array())
  ->checkCSRF();

$connection->rollback();
