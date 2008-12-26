<?php

/**
 * opBasicRemoteContent
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 *
 */
class opBasicRemoteContent extends RemoteContent {

	public function fetch($request, $context)
	{
		$cache = Config::get('data_cache');
		$cache = new $cache();
		$remoteContentFetcher = new opBasicRemoteContentFetcher();
		if (! ($request instanceof RemoteContentRequest)) {
			throw new RemoteContentException("Invalid request type in remoteContent");
		}
		// determine which requests we can load from cache, and which we have to actually fetch
		if (! $context->getIgnoreCache() && ! $request->isPost() && ($cachedRequest = $cache->get($request->toHash(), $context->getRefreshInterval())) !== false) {
			$ret = $cachedRequest;
		} else {
			$ret = $remoteContentFetcher->fetchRequest($request);
			// only cache requests that returned a 200 OK and is not a POST
			if ($request->getHttpCode() == '200' && ! $request->isPost()) {
				$cache->set($request->toHash(), $request);
			}
		}
		return $ret;
	}

	public function multiFetch(Array $requests, Array $contexts)
	{
		$cache = Config::get('data_cache');
		$cache = new $cache();
		$remoteContentFetcher = new opBasicRemoteContentFetcher();
		
		$rets = array();
		$requestsToProc = array();
		
		foreach ($requests as $request) {
			list(, $context) = each($contexts);
			if (! ($request instanceof RemoteContentRequest)) {
				throw new RemoteContentException("Invalid request type in remoteContent");
			}
			// determine which requests we can load from cache, and which we have to actually fetch
			if (! $context->getIgnoreCache() && ! $request->isPost() && ($cachedRequest = $cache->get($request->toHash(), $context->getRefreshInterval())) !== false) {
				$rets[] = $cachedRequest;
			} else {
				$requestsToProc[] = $request;
			}
		}
		
		$newRets = $remoteContentFetcher->multiFetchRequest($requestsToProc);
		
		foreach ($newRets as $request) {
			// only cache requests that returned a 200 OK and is not a POST
			if ($request->getHttpCode() == '200' && ! $request->isPost()) {
				$cache->set($request->toHash(), $request);
			}
			$rets[] = $request;
		}
		
		return $rets;
	}
}
