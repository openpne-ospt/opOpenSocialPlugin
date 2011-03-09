<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('api', 'test', true), 'api');

$conn = Doctrine_Manager::connection();
$conn->beginTransaction();

class myRequest
{
  public function getRelativeUrlRoot()
  {
    $result = parse_url(sfConfig::get('op_base_url'), PHP_URL_PATH);
    return $result === null ? '/' : $result;
  }

  public function isSecure()
  {
    $result = parse_url(sfConfig::get('op_base_url'), PHP_URL_SCHEME) === 'https';
    return $result === null ? '' : $result;
  }

  public function getHost()
  {
    $result = parse_url(sfConfig::get('op_base_url'), PHP_URL_HOST);
    return $result === null ? '' : $result;
  }

  public function getUriPrefix()
  {
    return sfConfig::get('op_base_url');
  }
}

sfConfig::set('op_base_url', 'http://example.com/sns');
sfContext::getInstance()->set('request', new myRequest());

$export = new opOpenSocialProfileExport();
$export->setViewer(Doctrine::getTable('Member')->find(1));
$export->member = Doctrine::getTable('Member')->find(2);

$t = new lime_test();

$t->diag('opOpenSocialProfileExport::getData()');

$t->is($export->getData(), array(
  'displayName' => 'B',
  'thumbnailUrl' => 'http://example.com/sns/cache/img/png/w_h/dummy.png',
  'profileUrl' => 'http://example.com/sns/pc_frontend_test.php/member/2',
), 'get profiles');

$t->is($export->getData(array('nickname', 'addresses', 'aboutMe', 'gender', 'age', 'phoneNumbers', 'birthday', 'languagesSpoken')), array(
  'displayName' => 'B',
  'nickname' => 'B',
  'thumbnailUrl' => 'http://example.com/sns/cache/img/png/w_h/dummy.png',
  'profileUrl' => 'http://example.com/sns/pc_frontend_test.php/member/2',
  'addresses' => array(array(
    'region' => 'Tokyo',
    'country' => 'JP',
    'postalCode' => 'xxx-xxxx',
    'formatted' => 'Tokyo,JP',
  )),
  'aboutMe' => 'dummy',
  'gender' => 'male',
  'age' => 100,
  'phoneNumbers' => array(array('value' => 'xxx-xxx-xxxx')),
  'birthday' => date('Y/m/d', strtotime('-100 years')),
  'languagesSpoken' => 'ja',
), 'get all profiles');

