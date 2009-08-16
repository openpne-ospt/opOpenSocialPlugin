<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opBasicOAuthLookupService
 *
 * @package    opOpenSocialPlugin
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opBasicOAuthLookupService extends OAuthLookupService
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
      $acceptedContentTypes = array('application/atom+xml', 'application/xml', 'application/json');
      if (isset($GLOBALS['HTTP_RAW_POST_DATA']) && ! empty($GLOBALS['HTTP_RAW_POST_DATA']))
      {
        if (!in_array($contentType, $acceptedContentTypes))
        {
          throw new Exception("Invalid Content-Type specified for this request, only 'application/atom+xml', 'application/xml' and 'application/json' are accepted");
        }
        else
        {
          if (isset($_GET['oauth_body_hash'])) {
            if (!$this->verifyBodyHash($GLOBALS['HTTP_RAW_POST_DATA'], $_GET['oauth_body_hash']))
            {
              return null;
            }
          }
        }
      }

      if (is_null($oauthRequest->get_parameter('oauth_token')))
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
    $consumer   = $this->dataStore->lookup_consumer($oauthRequest->get_parameter('oauth_consumer_key'));
    $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
    $signatureValid  = $signatureMethod->check_signature($oauthRequest, $consumer, null, $_GET['oauth_signature']);

    if (!$signatureValid)
    {
      return null;
    }

    if (!$this->isAdmin)
    {
      $consumerInformation = Doctrine::getTable('OAuthConsumerInformation')->findOneByKeyString($oauthRequest->get_parameter('oauth_consumer_key'));
      if (!$consumerInformation)
      {
        return null;
      }
      if ($consumerInformation->getMemberId() != $userId)
      {
        return null;
      }
    }

    return new OAuthSecurityToken($userId, $appUrl, 0, 'openpne');
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
      var_dump($e->getMessage()); exit;
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
}
