<?php

class OpenPNEOAuthLookupService extends OAuthLookupService
{
  public function getSecurityToken($oauthRequest, $appUrl, $userId, $contentType)
  {
    $appId = 0;

    $consumer = new OAuthConsumer(OpenPNEServiceConfig::OAUTH_CONSUMER_KEY, OpenPNEServiceConfig::OAUTH_CONSUMER_SECRET);
    $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
    $oauthSignature  = $oauthRequest->get_parameter('oauth_signature');
    if (!$signatureMethod->check_signature($oauthRequest, $consumer, null, $oauthSignature))
    {
      return null;
    }

    return new OAuthSecurityToken($userId, $appUrl, $appId, 'openpne');
  }
}
