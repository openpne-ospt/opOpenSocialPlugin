<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application actions.
 *
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class applicationActions extends opOpenSocialApplicationActions
{
  public function preExecute()
  {
    $this->forward404Unless(Doctrine::getTable('SnsConfig')->get('opensocial_is_enable_mobile', false));
    parent::preExecute();
    if (isset($this->application))
    {
      $this->forward404Unless($this->application->getIsMobile());
    }
  }

 /**
  * Executes list action
  *
  * @param sfWebRequest $request
  */
  public function executeList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('MemberApplication')->getMemberApplicationListPager(
      $request->getParameter('page', 1), 10, null, null, true, null, true
    );
  }

 /**
  * Executes gallery action
  *
  * @param sfWebRequest $request
  */
  public function executeGallery(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application'));
    if ($this->searchForm->isValid())
    {
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1), 10, true, null, true);
    }
  }

 /**
  * Executes add action
  *
  * @param sfWebRequest $request
  */
  public function executeAdd(sfWebRequest $request)
  {
    $memberApplication = $this->processAdd($request);
    if ($memberApplication instanceof MemberApplication)
    {
      $this->redirect('@application_render?id='.$this->application->getId());
    }
  }

 /**
  * Executes info action
  *
  * @param sfWebRequest $request
  */
  public function executeInfo(sfWebRequest $request)
  {
    $this->memberApplication =
      Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember(
        $this->application, $this->getUser()->getMember()
      );
  }

 /**
  * Executes invite action
  *
  * @param sfWebRequest $request
  */
  public function executeInvite(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      if ($request->hasParameter('invite'))
      {
        $result = $this->processInvite($request);
        $callback = '@application_render?id='.$this->application->getId();
        if ($request->hasParameter('callback'))
        {
          $callback .= '&url='.urlencode($request->getParameter('callback'));
        }
        $this->redirect($callback);
      }
    }

    $fromMember = $this->getUser()->getMember();
    $this->nowpage = (int)$request->getParameter('nowpage', 1);
    if ($request->hasParameter('previous'))
    {
      $this->nowpage--;
    }
    else if ($request->hasParameter('next'))
    {
      $this->nowpage++;
    }

    $this->ids = $request->getParameter('ids', array());

    $this->forward404Unless($this->application->isHadByMember($fromMember->getId()));
    $this->pager = Doctrine::getTable('MemberRelationship')->getFriendListPager($fromMember->getId(), $this->nowpage, 15);
    $this->installedFriends = Doctrine::getTable('MemberApplication')->getInstalledFriendIds($this->application, $fromMember);
    $this->form = new BaseForm();
  }

 /**
  * Executes render action
  *
  * @param sfWebRequest $request
  */
  public function executeRender(sfWebRequest $request)
  {
    include_once sfConfig::get('sf_lib_dir').'/vendor/OAuth/OAuth.php';

    $this->memberApplication = Doctrine::getTable('MemberApplication')
      ->findOneByApplicationAndMember($this->application, $this->member);
    $this->redirectUnless($this->memberApplication, '@application_info?id='.$this->application->getId());

    $views = $this->application->getViews();
    $this->forward404Unless(
      isset($views['mobile']) &&
      isset($views['mobile']['type']) &&
      isset($views['mobile']['href']) &&
      'URL' === strtoupper($views['mobile']['type'])
    );

    $method = $request->isMethod(sfWebRequest::POST) ? 'POST' : 'GET';

    if (!isset($this->redirectCount))
    {
      $this->redirectCount = 0;
    }

    if (isset($this->redirectUrl))
    {
      $url = $this->redirectUrl;
      $method = 'GET';
    }
    else
    {
      $url = $request->getParameter('url', $views['mobile']['href']);
    }
    $zendUri = Zend_Uri_Http::fromString($url);
    $queryString = $zendUri->getQuery();
    $zendUri->setQuery('');
    $zendUri->setFragment('');
    $url = $zendUri->getUri();
    $query = array();
    parse_str($queryString, $query);

    $params = array(
      'opensocial_app_id'    => $this->application->getId(),
      'opensocial_owner_id'  => $this->member->getId()
    );
    $params = array_merge($query, $params);

    unset($params['lat']);
    unset($params['lon']);
    unset($params['geo']);
    if ($request->hasParameter('l') && $this->getUser()->hasFlash('op_opensocial_location'))
    {
      $method = ('p' == $request->getParameter('l')) ? 'POST' : 'GET';
      $location = unserialize($this->getUser()->getFlash('op_opensocial_location'));
      if (isset($location['lat']) && isset($location['lon']) && isset($location['geo']))
      {
        $params['lat'] = $location['lat'];
        $params['lon'] = $location['lon'];
        $params['geo'] = $location['geo'];
      }
    }

    $consumer = new OAuthConsumer(opOpenSocialToolKit::getOAuthConsumerKey(), null, null);
    $signatureMethod = new OAuthSignatureMethod_RSA_SHA1_opOpenSocialPlugin();
    $httpOptions = opOpenSocialToolKit::getHttpOptions();

    $client = new Zend_Http_Client();
    if ('POST' !== $method)
    {
      $client->setMethod(Zend_Http_Client::GET);
      $url .= '?'.OAuthUtil::build_http_query($params);
    }
    else
    {
      $params = array_merge($params, $request->getPostParameters());
      $client->setMethod(Zend_Http_Client::POST);
      $client->setHeaders(Zend_Http_Client::CONTENT_TYPE, Zend_Http_Client::ENC_URLENCODED);
      $client->setRawData(OAuthUtil::build_http_query($params));
    }
    $oauthRequest = OAuthRequest::from_consumer_and_token($consumer, null, $method, $url, $params);
    $oauthRequest->sign_request($signatureMethod, $consumer, null);

    $client->setConfig($httpOptions);
    $client->setUri($url);
    $client->setHeaders($oauthRequest->to_header());
    $client->setHeaders(opOpenSocialToolKit::getProxyHeaders($request, sfConfig::get('op_opensocial_is_strip_uid', true)));

    try
    {
      $response = $client->request();
    }
    catch (Zend_Http_Client_Exception $e)
    {
      $this->logMessage($e->getMessage(), 'err');
      return sfView::ERROR;
    }

    if ($response->isSuccessful())
    {
      $contentType = $response->getHeader('Content-Type');

      if (preg_match('#^(text/html|application/xhtml\+xml|application/xml|text/xml)#', $contentType, $match))
      {
        $this->response->setContentType($match[0].'; charset=Shift_JIS');
        $rewriter = new opOpenSocialMobileRewriter($this);
        $this->response->setContent($rewriter->rewrite($response->getBody()));
      }
      else
      {
        $this->response->setContentType($response->getHeader('Content-Type'));
        $this->response->setContent($response->getBody());
      }

      if ('test' === $this->context->getConfiguration()->getEnvironment())
      {
        return sfView::NONE;
      }

      $this->response->send();
      exit;
    }
    elseif (
      $response->isRedirect() && ($location = $response->getHeader('location')) &&
      $this->redirectCount < sfConfig::get('op_opensocial_maxredirects', 5)
    )
    {
      if (Zend_Uri_Http::check($location))
      {
        $this->redirectUrl = $location;
      }
      else
      {
        $uri = $client->getUri();
        if (strpos($location, '?') !== false)
        {
          list($location, $query) = explode('?', $location, 2);
        }
        else
        {
          $query = '';
        }
        $uri->setQuery($query);

        if(strpos($location, '/') === 0)
        {
          $uri->setPath($location);
        }
        else
        {
          $path = $uri->getPath();
          $path = rtrim(substr($path, 0, strrpos($path, '/')), "/");
          $uri->setPath($path . '/' . $location);
        }

        $this->redirectUrl = $uri->getUri();
      }
      $this->redirectCount++;

      return $this->executeRender($request);
    }

    return sfView::ERROR;
  }

  protected function processLocation(sfWebRequest $request)
  {
    $this->memberApplication = Doctrine::getTable('MemberApplication')
      ->findOneByApplicationAndMember($this->application, $this->member);
    $this->redirectUnless($this->memberApplication, '@application_info?id='.$this->application->getId());
    $this->location = opOpenSocialLocation::createInstance($request, $this->getUser());
  }


 /**
  * Executes location action
  *
  * @param sfWebRequest $request
  */
  public function executeLocation(sfWebRequest $request)
  {
    try
    {
      $this->processLocation($request);
    }
    catch (LogicException $e)
    {
      return sfView::ERROR;
    }

    $this->forward404Unless(in_array($request->getParameter('type'), array('cell', 'gps')));
    $params = array();
    if ($request->hasParameter('callback'))
    {
      $params['callback'] = $request->getParameter('callback');
    }
    $params['method'] = $request->isMethod(sfWebRequest::GET) ? 'GET' : 'POST';
    $this->tk = opToolkit::getRandom('12');
    $t        = opToolkit::getRandom();
    $this->getUser()->setFlash('op_opensocial_location_t_'.$this->tk, $t);
    $params['t'] = $t;
    $this->location->setParameters($params);
  }

 /**
  * Executes accept location action
  *
  * @param sfWebRequest $request
  */
  public function executeAcceptLocation(sfWebRequest $request)
  {
    try
    {
      $this->processLocation($request);
    }
    catch (LogicException $e)
    {
      $this->redirect404();
    }

    $t = $this->location->getParameter('t');
    if ($t == $this->getUser()->getFlash('op_opensocial_location_t_'.$request->getParameter('tk')))
    {
      $this->getUser()->setFlash('op_opensocial_location', serialize($this->location->fetchLocation()));
    }
    $callback = $this->location->getParameter('callback');
    $uri = '@application_render?id='.$this->application->id;
    if ($callback)
    {
      $uri .= '&url='.$callback;
    }
    $uri .= '&l='.('GET' == $this->location->getParameter('method') ? 'g' : 'p');
    $this->redirect($uri);
  }
}
