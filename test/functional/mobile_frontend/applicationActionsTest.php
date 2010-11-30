<?php

$app = 'mobile_frontend';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$member1      = Doctrine::getTable('Member')->findOneByName('A');

$xssMember    = Doctrine::getTable('Member')->findOneByName("<&\"'>Member.name ESCAPING HTML TEST DATA");
$xssApp       = Doctrine::getTable('Application')->findOneByUrl('http://example.com/dummy4.xml');
$xssMemberApp = Doctrine::getTable('MemberApplication')->findOneByMemberIdAndApplicationId($xssMember->id, $xssApp->id);

$connection = Doctrine::getTable('Application')->getConnection();
$connection->beginTransaction();

$browser = new opBrowser();
$user = new opTestFunctional($browser, new lime_test(14, new lime_output_color()));
$user->setMobile();

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
  ->info('application/gallery')
  // XSS
  ->get('application/gallery')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$user
  ->info('application/info')
  // XSS
  ->get('application/info/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
    ->isAllEscapedData('ApplicationTranslation', 'description')
    ->isAllEscapedData('ApplicationTranslation', 'thumbnail')
    ->isAllEscapedData('ApplicationTranslation', 'author')
  ->end();

$mid = $xssApp->addToMember($member1);
$user
  ->info('application/invite')
  // XSS
  ->get('application/invite/'.$mid)
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()
  // CSRF
  ->post('application/invite/'.$mid, array())
  ->checkCSRF();

$user
  ->info('application/list')
  // XSS
  ->login('sns-xss@example.com', 'password')
  ->get('application/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
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
  ->info('application')
  // XSS
  ->get('application/'.$xssApp->id)
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$user
  ->info('application/location')
  // XSS
  ->get('application/'.$xssApp->id.'?type=cell')
  ->with('html_escape')->begin()
    ->isAllEscapedData('ApplicationTranslation', 'title')
  ->end();

$connection->rollback();
