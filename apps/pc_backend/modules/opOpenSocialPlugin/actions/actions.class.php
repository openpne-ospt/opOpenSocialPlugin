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
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara<kawahara@tejimaya.net>
 */
class opOpenSocialPluginActions extends sfActions
{
  public function preExecute()
  {
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof Application)
      {
        $this->application = $object;
      }
    }
  }

  /**
   * Executes applicationConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeApplicationConfig(sfWebRequest $request)
  {
    $this->applicationConfigForm = new ApplicationConfigForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->applicationConfigForm->bind($request->getParameter('application_config'));
      if ($this->applicationConfigForm->isValid())
      {
        $this->applicationConfigForm->save();
      }
    }
  }

  /**
   * Executes containerConfig action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeContainerConfig(sfWebRequest $request)
  {
    $this->containerConfigForm = new ContainerConfigForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->containerConfigForm->bind($request->getParameter('container_config'));
      if ($this->containerConfigForm->isValid())
      {
        $this->containerConfigForm->save();
      }
    }
  }

 /**
  * Executes add action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeAdd(sfWebRequest $request)
  {
    $this->form = new AddApplicationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
          $this->redirect('@op_opensocial_info?id='.$application->id);
        }
        catch (Exception $e)
        {
          if (!($e instanceof sfStopException))
          {
            $this->getUser()->setFlash('error', 'Failed in adding the App.');
          }
        }
        $this->redirect('@op_opensocial_add');
      }
    }
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application'));
    if ($this->searchForm->isValid())
    {
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1), 20, true);
    }
  }

  /**
   * Executes info action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInfo(sfWebRequest $request)
  {
    $views = $this->application->getViews();
    $isUseMobileApp =
      Doctrine::getTable('SnsConfig')->get('opensocial_is_enable_mobile', false) && isset($views['mobile']);
    if ($isUseMobileApp)
    {
      $this->mobileForm = new MobileApplicationConfigForm($this->application);
      if ($request->isMethod(sfWebRequest::PUT))
      {
        $this->mobileForm->bind($request->getParameter('application'));
        if ($this->mobileForm->isValid())
        {
          $this->mobileForm->save();
          $this->getUser()->setFlash('notice', 'Saved.');
          $this->redirect('@op_opensocial_info?id='.$this->application->getId());
        }
      }
    }
  }

  /**
   * Executes delete application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->application->delete();
      $this->redirect('@op_opensocial_list');
    }
  }

  /**
   * Executes update application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->application->updateApplication($this->getUser()->getCulture());
    $this->redirect('@op_opensocial_info?id='.$this->application->getId());
  }

  /**
   * Executes generate container config
   *
   * @param sfWebRequest $request A request object
   */
  public function executeGenerateContainerConfig(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $response = $this->getResponse();
    $response->setContentType('text/javascript');
    $response->setHttpHeader('Content-Disposition','attachment; filename="openpne.js');
    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig(false);
    $this->json = $opOpenSocialContainerConfig->generate();
  }

  /**
   * Executes activate application
   *
   * @param sfWebRequest $request A request object
   */
  public function executeActivate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->application->setIsActive(true);
    $this->application->save();
    $this->redirect('@op_opensocial_info?id='.$this->application->getId());
  }

  /**
   * Executes inactivate application
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInactivate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->application->setIsActive(false);
    $this->application->save();
    $this->redirect('@op_opensocial_info?id='.$this->application->getId());
  }

  /**
   * Executes inactive application list
   *
   * @param sfWebRequest $request A request object
   */
  public function executeInactiveList(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('Application')->getApplicationListPager($request->getParameter('page'), 20, null, false);
  }

 /**
  * Executes show consumer secret
  *
  * @param sfWebRequest $request
  */
  public function executeShowConsumerSecret(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
    $secret = $this->application->getConsumerSecret();
    if ($secret)
    {
      $this->renderText($secret);
    }

    return sfView::NONE;
  }

 /**
  * Executes update consumer secret
  *
  * @param sfWebRequest $request
  */
  public function executeUpdateConsumerSecret(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      if (!$this->application->getConsumerKey())
      {
        $this->application->setConsumerKey(opToolkit::generatePasswordString(16, false));
      }
      $this->application->setConsumerSecret(opToolkit::generatePasswordString(32));
      $this->application->save();
      $this->redirect('@op_opensocial_info?id='.$this->application->getId());
    }

    return sfView::INPUT;
  }

 /**
  * Executes delete consumer secret
  *
  * @param sfWebRequest $request
  */
  public function executeDeleteConsumerSecret(sfWebRequest $request)
  {
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->application->setConsumerSecret('');
      $this->application->save();
      $this->redirect('@op_opensocial_info?id='.$this->application->getId());
    }

    return sfView::INPUT;
  }
}
