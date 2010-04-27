<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opShindigOAuthLookupService
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opShindigOAuthLookupService extends OAuthLookupService
{
  protected
    $dataStore = null,
    $isAdmin = false,
    $tokenTable = null;

  public function __construct()
  {
    $tokenType = sfContext::getInstance()->getRequest()->getParameter('token_type', 'member');
    $this->dataStore = new opOAuthDataStore();
    $tokenTableName = 'OAuth'.ucfirst($tokenType).'Token';
    $this->dataStore->setTokenModelName($tokenTableName);
    $this->tokenTable = Doctrine::getTable($tokenTableName);
    if ($tokenType === 'admin')
    {
      $this->isAdmin = true;
    }
  }

  public function getSecurityToken($oauthRequest, $appUrl, $userId, $contentType)
  {
    try
    {
      if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
        $tmp = file_get_contents('php://input');
        if (!empty($tmp)) {
          $GLOBALS['HTTP_RAW_POST_DATA'] = $tmp;
        }
      }
      if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
        $postBody = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (get_magic_quotes_gpc()) {
          $postBody = stripslashes($postBody);
        }
      }

      $acceptedContentTypes = array('application/atom+xml', 'application/xml', 'application/json');
      if (!empty($postBody))
      {
        if (!in_array($contentType, $acceptedContentTypes))
        {
          throw new Exception("Invalid Content-Type specified for this request, only 'application/atom+xml', 'application/xml' and 'application/json' are accepted");
        }
        else
        {
          if (isset($_GET['oauth_body_hash'])) {
            if (!$this->verifyBodyHash($postBody, $_GET['oauth_body_hash']))
            {
              return null;
            }
          }
        }
      }
      if ($oauthRequest->get_parameter('oauth_token') === null)
      {
        return $this->verify2LeggedOAuth($oauthRequest, $userId, $appUrl);
      }
      else
      {
        return $this->verify3LeggedOAuth($oauthRequest, $userId, $appUrl);
      }
    }
    catch (OAuthException $e)
    {
    }

    return null;
  }

  protected function verify2LeggedOAuth($oauthRequest, $userId, $appUrl)
  {
    $appId = 0;
    $consumerKey = $oauthRequest->get_parameter('oauth_consumer_key');

    $application = Doctrine::getTable('Application')->findOneByConsumerKey($consumerKey);
    if ($application)
    {
      if (!($application->getConsumerSecret() && $application->isHadByMember($userId)))
      {
        return null;
      }

      $appId = $application->getId();
      $consumer = new OAuthConsumer($application->getConsumerKey(), $application->getConsumerSecret());
    }
    else
    {
      $consumer = $this->dataStore->lookup_consumer($consumerKey);

      if (!($consumerInformation = $this->getConsumerInformation($consumer)))
      {
        return null;
      }

      if (!$this->isAdmin)
      {
        if ($consumerInformation->getMemberId() != $userId)
        {
          return null;
        }
      }
    }

    $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
    $oauthSignature  = $oauthRequest->get_parameter('oauth_signature');
    $signatureValid  = $signatureMethod->check_signature($oauthRequest, $consumer, null, $oauthSignature);

    if (!$signatureValid)
    {
      return null;
    }

    return new OAuthSecurityToken($userId, $appUrl, $appId, 'openpne');
  }

  protected function verify3LeggedOAuth($oauthRequest, $userId, $appUrl)
  {
    $server = new opOAuthServer($this->dataStore);
    try
    {
      list($consumer, $token) = $server->verify_request($oauthRequest);
    }
    catch(Exception $e)
    {
      return null;
    }

    if (!$this->getConsumerInformation($consumer))
    {
      return null;
    }

    if (!$this->isAdmin)
    {
      $memberToken = $this->tokenTable->findOneByKeyString($token->key);
      if (!$memberToken)
      {
        return null;
      }

      $userId = $memberToken->getMemberId();
    }


    return new OAuthSecurityToken($userId, $appUrl, 0, 'openpne');
  }

  protected function getConsumerInformation(OAuthConsumer $consumer)
  {
    $consumerInformation = Doctrine::getTable('OAuthConsumerInformation')->findOneByKeyString($consumer->key);

    if (!$consumerInformation)
    {
      return null;
    }

    if (!in_array('opensocial', $consumerInformation->getUsingApis()))
    {
      return null;
    }

    return $consumerInformation;
  }

  protected function verifyBodyHash($postBody, $oauthBodyHash)
  {
    return base64_encode(sha1($postBody, true)) == $oauthBodyHash;
  }
}
