<?php

class OpenPNEInvalidateService implements InvalidateService
{
  protected
    $invalidationEntry,
    $cache;

  protected static
    $marker = null,
    $makerCache = null,
    $TOKEN_PREFIX = 'INV_TOK_';

  public function __construct(Cache $cache)
  {
    $this->cache = $cache;
    $this->invalidationEntry = Cache::createCache(Config::get('data_cache'), 'InvalidationEntry');
    if (self::$makerCache == null) {
      self::$makerCache = Cache::createCache(Config::get('data_cache'), 'MarkerCache');
      $value = self::$makerCache->expiredGet('marker');
      if ($value['found'])
      {
        self::$marker = $value['data'];
      }
      else
      {
        self::$marker = 0;
        self::$makerCache->set('marker', self::$marker);
      }
    }
  }

  public function invalidateApplicationResources(array $uris, SecurityToken $token)
  {
    foreach ($uris as $uri)
    {
      $request = new RemoteContentRequest($uri);
      $this->cache->invalidate($request->toHash());

      // GET
      $request = new RemoteContentRequest($uri);
      $request->createRemoteContentRequestWithUri($uri);
      $this->cache->invalidate($request->toHash());

      // GET & SIGNED
      $request = new RemoteContentRequest($uri);
      $request->setAuthType(RemoteContentRequest::$AUTH_SIGNED);
      $request->setNotSignedUri($uri);
      $this->cache->invalidate($request->toHash());
    }
  }

  public function invalidateUserResources(array $opensocialIds, SecurityToken $token)
  {
    foreach ($opensocialIds as $opensocialId)
    {
      ++self::$marker;
      self::$makerCache->set('marker', self::$marker);
      $this->invalidationEntry->set($this->getKey($opensocialId, $token), self::$marker);
    }
  }

  public function isValid(RemoteContentRequest $request)
  {
    if ($request->getAuthType() == RemoteContentRequest::$AUTH_NONE)
    {
      return true;
    }

    return $request->getInvalidation() == $this->getInvalidationMark($request);
  }

  public function markResponse(RemoteContentRequest $request)
  {
    $mark = $this->getInvalidationMark($request);
    if ($mark)
    {
      $request->setInvalidation($mark);
    }
  }

  protected function getKey($userId, SecurityToken $token)
  {
    $pos = strrpos($userId, ':');
    if ($pos !== false)
    {
      $userId = substr($userId, $pos + 1);
    }

    if ($token->getAppId())
    {

      return self::$TOKEN_PREFIX . $token->getAppId() . '_' . $userId;
    }

    return self::$TOKEN_PREFIX . $token->getAppUrl() . '_' . $userId;
  }

  protected function getInvalidationMark(RemoteContentRequest $request)
  {
    $token = $request->getToken();
    if (!$token)
    {
      return null;
    }

    $currentInvalidation = '';
    if ($token->getOwnerId())
    {
      $ownerKey = $this->getKey($token->getOwnerId(), $token);
      $cached = $this->invalidationEntry->expiredGet($ownerKey);
      $ownerStamp = $cached['found'] ? $cached['data'] : false;
    }
    if ($token->getViewerId())
    {
      $viewerKey = $this->getKey($token->getViewerId(), $token);
      $cached = $this->invalidationEntry->expiredGet($viewerKey);
      $viewerStamp = $cached['found'] ? $cached['data'] : false;
    }
    if (isset($ownerStamp))
    {
      $currentInvalidation = $currentInvalidation . 'o=' . $ownerStamp . ';';
    }
    if (isset($viewerStamp))
    {
      $currentInvalidation = $currentInvalidation . 'v=' . $viewerStamp . ';';
    }

    return $currentInvalidation;
  }
}
