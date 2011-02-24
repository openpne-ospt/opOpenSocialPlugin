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
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
    $this->addOption('consumer-key', null, sfCommandOption::PARAMETER_OPTIONAL, 'The consumer key for signing by OAuth', null);
    $this->addOption('limit-request', null, sfCommandOption::PARAMETER_OPTIONAL, 'Limit of request', 0);
    $this->addOption('limit-request-app', null, sfCommandOption::PARAMETER_OPTIONAL, 'Limit of request par an application', 0);
    $this->addOption('limit-failure', null, sfCommandOption::PARAMETER_OPTIONAL, 'Limit of failed request', -1);

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
    sfContext::createInstance($this->configuration);

    $consumerKey = (isset($options['consumer-key']) && $options['consumer-key']) ?
      $options['consumer-key'] :
      opOpenSocialToolKit::getOAuthConsumerKey();
    $consumer = new OAuthConsumer($consumerKey, null, null);
    $signatureMethod = new OAuthSignatureMethod_RSA_SHA1_opOpenSocialPlugin();

    $client = new Zend_Http_Client();
    $httpOptions = opOpenSocialToolKit::getHttpOptions();
    $client->setConfig($httpOptions);

    $queueGroups = Doctrine::getTable('ApplicationLifecycleEventQueue')->getQueueGroups();

    $limitRequest = (int)$options['limit-request'];
    $limitRequestApp = (int)$options['limit-request-app'];
    $limitFailure = (int)$options['limit-failure'];

    $allRequest = 0;
    foreach ($queueGroups as $group)
    {
      $application = Doctrine::getTable('Application')->find($group[0]);
      $links = $application->getLinks();
      $linkHash = array();
      foreach ($links as $link)
      {
        if (isset($link['rel']) && isset($link['href']))
        {
          $method = isset($link['method']) ? strtolower($link['method']) : '';
          $method = ('post' !== $method) ? 'get' : 'post';
          $linkHash[$link['rel']] = array('href' => $link['href'], 'method' => $method);
        }
      }

      $queues = Doctrine::getTable('ApplicationLifecycleEventQueue')
        ->getQueuesByApplicationId($group[0], $limitRequestApp);

      $failCount = 0;
      foreach ($queues as $queue)
      {
        if (!isset($linkHash[$queue->getName()]))
        {
          $queue->delete();
          continue;
        }
        $href = $linkHash[$queue->getName()]['href'];
        $method = $linkHash[$queue->getName()]['method'];

        $oauthRequest = OAuthRequest::from_consumer_and_token($consumer, null, $method, $href, $queue->getParams());
        $oauthRequest->sign_request($signatureMethod, $consumer, null);

        $client->resetParameters();
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
        $client->setUri($href);
        $client->setHeaders($oauthRequest->to_header());

        try
        {
          $response = $client->request(); // throws Zend_Http_Client_Exception if failed
          if ($response->isSuccessful())
          {
            $queue->delete();
          }
          elseif (floor($response->getStatus() / 100) == 4)
          {
            // Client error 4xx (e.g. Not Found)
            break;
          }
          else
          {
            // Server error 5xx and others
            throw new Exception();
          }
        }
        catch (Exception $e)
        {
          if ($limitFailure !== -1 && ++$failCount >= $limitFailure)
          {
            break;
          }
        }

        $allRequest++;
        if ($limitRequest && $limitRequest <= $allRequest)
        {
          break 2;
        }
      }
      $application->free(true);
      $queues->free(true);
    }
  }
}
