<?php
/**
 * Basic Remote Content Fetcher
 *
 */
class BasicRemoteContentFetcher extends RemoteContentFetcher {

  public function fetchRequest($request)
  {
    $headers = array();
    if ($request->hasHeaders())
    {
      $headers = $request->getHeaders();
    }
    $headers[] = "User-Agent: Shindig PHP";
    $options = array();
    $options['Timeout'] = Config::get('curl_connection_timeout', 15);
    $proxy   = Config::get('proxy',null);
    if (!empty($proxy))
    {
      $options['Proxy'] = $proxy;
    }
    $browser = new sfWebBrowser($headers, null, $options);
    if ($request->isPost())
    {
      $browser->post($request->getUrl(), $request->getPostBody());
    }
    else
    {
      $browser->get($request->getUrl());
    }
    $request->setHttpCode($browser->getResponseCode());
    $request->setContentType($browser->getResponseHeader('Content-Type'));
    $request->setResponseHeaders($browser->getResponseHeaders());
    $request->setResponseContent($browser->getResponseText());
    $request->setResponseSize(strlen($browser->getResponseText()));
    return $request;
  }

  public function multiFetchRequest(Array $requests)
  {
    foreach($requests as $request)
    {
      $request = $this->fetchRequest($request);
    }
    return $requests;
  }
}
