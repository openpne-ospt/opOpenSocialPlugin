<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialExecuteLifecycleEventTask
 *
 * @package    opOpenSocialPlugin
 * @subpackage task
 * @author     ShogoKawahara <kawahara@tejimaya.com>
 */
class opOpenSocialExecuteLifecycleEventTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace = 'opOpenSocial';
    $this->name      = 'execute-lifecycle-event';

    $this->addOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', true);
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
    $this->addOption('consumer-key', null, sfCommandOption::PARAMETER_OPTIONAL, 'The consumer key for signing by OAuth', null);

    $this->briefDescription = 'Execute lifecycle event';
    $this->detailedDescription = <<<EOF
Execute lifecycle event
Call it with:

 [./symfony opOpenSocial:execute-lifecycle-event|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    require_once realpath(dirname(__FILE__).'/../../../../lib/vendor/OAuth/OAuth.php');

    new sfDatabaseManager($this->configuration);
    sfContext::createInstance($this->createConfiguration('pc_frontend', 'prod'), 'pc_frontend');

    if (isset($options['consumer-key']) && $options['consumer-key'])
    {
      $consumerKey = $options['consumer-key'];
    }
    else
    {
      $this->configuration->loadHelpers(array('opUtil'));
      $baseUrl = sfConfig::get('op_base_url');
      if ('/' === substr($baseUrl, -1))
      {
        $baseUrl = substr($baseUrl, 0, strlen($baseUrl) - 1);
      }
      $consumerKey = $baseUrl.app_url_for('pc_frontend', '@opensocial_certificates');
    }
    $consumer = new OAuthConsumer($consumerKey, null, null);
    $signatureMethod = new OAuthSignatureMethod_RSA_SHA1_opOpenSocialPlugin();
    $proxyUrl = Shindig_Config::get('proxy');
    $httpOptions = array();
    if (!empty($proxyUrl))
    {
      $httpOptions['adapter'] = 'Zend_Http_Client_Adapter_Proxy';
      $proxy = parse_url($proxyUrl);
      if (isset($proxy['host']))
      {
        $httpOptions['proxy_host'] = $proxy['host'];
      }

      if (isset($proxy['port']))
      {
        $httpOptions['proxy_port'] = $proxy['port'];
      }

      if (isset($proxy['user']))
      {
        $httpOptions['proxy_user'] = $proxy['user'];
      }

      if (isset($proxy['pass']))
      {
        $httpOptions['proxy_pass'] = $proxy['pass'];
      }
    }
    $httpOptions['timeout'] = Shindig_Config::get('curl_connection_timeout');

    $queueGroups = Doctrine::getTable('ApplicationLifecycleEventQueue')->getQueueGroups();
    foreach ($queueGroups as $group)
    {
      $application = Doctrine::getTable('Application')->find($group[0]);
      $links = $application->getLinks();
      $href = null;
      $method = null;
      foreach ($links as $link)
      {
        if (isset($link['rel']) && $link['rel'] === $group[1] && isset($link['href']))
        {
          $href = $link['href'];
          $method = isset($link['method']) ? strtolower($link['method']) : '';
          $method = ('post' !== $method) ? 'get' : 'post';
          break;
        }
      }

      $queues = Doctrine::getTable('ApplicationLifecycleEventQueue')
        ->findByApplicationIdAndName($group[0], $group[1]);
      if ($href && $method)
      {
        $params = array();
        foreach ($queues as $queue)
        {
          $params = $this->arrayMergeForParameter($params, $queue->getParams());
        }
        $oauthRequest = OAuthRequest::from_consumer_and_token($consumer, null, $method, $href, $params);
        $oauthRequest->sign_request($signatureMethod, $consumer, null);

        $client = new Zend_Http_Client();
        if ('post' !== $method)
        {
          $method = 'get';
          $client->setMethod(Zend_Http_Client::GET);
          $href .= '?'.$oauthRequest->to_postdata();
        }
        else
        {
          $client->setMethod(Zend_Http_Client::POST);
          $client->setHeaders(Zend_Http_Client::CONTENT_TYPE, Zend_Http_Client::ENC_URLENCODED);
          $client->setRawData($oauthRequest->to_postdata());
        }
        $client->setConfig($httpOptions);
        $client->setUri($href);
        $client->setHeaders($oauthRequest->to_header());
        $response = $client->request();
        if ($response->isSuccessful())
        {
          $queues->delete();
        }
      }
      else
      {
        $queues->delete();
      }
      $application->free(true);
      $queues->free(true);
    }
  }

  protected function arrayMergeForParameter($array1, $array2)
  {
    foreach ($array2 as $key => $e)
    {
      if (is_numeric($key))
      {
        $array1[] = $e;
      }
      else
      {
        if (isset($array1[$key]))
        {
          if (is_array($array1[$key]))
          {
            $array1[$key][] = $e;
          }
          elseif ($array1[$key] !== $e)
          {
            $array1[$key] = array($array1[$key], $e);
          }
        }
        else
        {
          $array1[$key] = $e;
        }
      }
    }
    return $array1;
  }
}
