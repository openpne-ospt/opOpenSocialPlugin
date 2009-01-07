<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opBasicRemoteContentFetcher
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 *
 */
class opBasicRemoteContentFetcher extends RemoteContentFetcher {

  public function fetchRequest($request)
  {
    $outHeaders = array();
    if ($request->hasHeaders())
    {
      $headers = explode("\n", $request->getHeaders());
      foreach ($headers as $header)
      {
        if (strpos($header, ':'))
        {
          $key = trim(substr($header, 0, strpos($header, ':')));
          $val = trim(substr($header, strpos($header, ':') + 1));
          if (strcmp($key, "User-Agent") != 0 && 
            strcasecmp($key, "Transfer-Encoding") != 0 && 
            strcasecmp($key, "Cache-Control") != 0 && 
            strcasecmp($key, "Expries") != 0 && 
            strcasecmp($key, "Content-Length") != 0)
          {
            $outHeaders[$key] = $val;
          }
        }
      }
    }
    $outHeaders['User-Agent'] = "Shindig PHP";
    $options = array();
    $options['Timeout'] = Config::get('curl_connection_timeout', 15);
    $proxy   = Config::get('proxy',null);
    if (!empty($proxy))
    {
      $options['Proxy'] = $proxy;
    }
    $browser = new sfWebBrowser($outHeaders, null, $options);
    if ($request->isPost())
    {
      $outPostBody = array();
      $postBodys = explode('&',$request->getPostBody());
      foreach ($postBodys as $postBody)
      {
        $pb = explode("=",urldecode($postBody));
        if (count($pb) == 2)
        {
          $outPostBody[$pb[0]] = $pb[1];
        }
      }
      $browser->post($request->getUrl(), $outPostBody);
    }
    else
    {
      $browser->get($request->getUrl());
    }
    $request->setHttpCode($browser->getResponseCode());
    $request->setContentType($browser->getResponseHeader('Content-Type'));
    $responseHeaders = $browser->getResponseHeaders();
    $resHeaders = array();
    foreach ($responseHeaders as $key => $val)
    {
      if (strcasecmp($key, 'Content-Encoding') != 0)
      {
        $resHeaders[] = $key.": ".$val;
      }
    }
    $request->setResponseHeaders(implode("\n", $resHeaders));
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
