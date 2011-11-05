<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$table = Doctrine::getTable('ApplicationLifecycleEventQueue');
$conn = $table->getConnection();
$conn->beginTransaction();

$task = new opOpenSocialExecuteLifecycleEventTask($configuration->getEventDispatcher(), new sfFormatter());

class Zend_Http_Client_Adapter_Mock implements Zend_Http_Client_Adapter_Interface
{
  protected
    $url = null;

  public function setConfig($config = array())
  {
  }

  public function connect($host, $port = 80, $secure = false)
  {
  }

  public function write($method, $url, $http_ver = '1.1', $headers = array(), $body = '')
  {
    $this->url = $url;
  }

  public function read()
  {
    if (0 === strpos($this->url->getUri(), 'http://example.com:80/addapp'))
    {
      return "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\nOK";
    }

    throw new Zend_Http_Client_Exception();
  }

  public function close()
  {
  }
}

$task->setHttpClientAdapter(new Zend_Http_Client_Adapter_Mock());

$t = new lime_test(6, new lime_output_color());

$t->diag('opOpenSocial:execute-lifecycle-event --limit-request=1');

$task->run(array(), array(
  'env' => 'test',
  'limit-request' => 1,
));

$t->is($table->findByApplicationId(1)->count(), 4, 'task only sends one request (application.id = 1)');

$t->diag('opOpenSocial:execute-lifecycle-event --limit-request-app=2');

$task->run(array(), array(
  'env' => 'test',
  'limit-request-app' => 2,
));

$t->is($table->findByApplicationId(1)->count(), 2, 'task only sends two requests (application.id = 1)');
$t->is($table->findByApplicationId(2)->count(), 3, 'task only sends two requests (application.id = 2)');
$t->is($table->findByApplicationId(3)->count(), 5, 'task does\'t delete queues if request failed (application.id = 3)');

$t->diag('opOpenSocial:execute-lifecycle-event --limit-request=3 --limit-request-app=2');

$task->run(array(), array(
  'env' => 'test',
  'limit-request' => 3,
  'limit-request-app' => 2,
));

$t->is($table->findByApplicationId(1)->count(), 0, 'task sends the remaining request (application.id = 1)');
$t->is($table->findByApplicationId(2)->count(), 2, 'task only sends one request (application.id = 2)');

$conn->rollback();

