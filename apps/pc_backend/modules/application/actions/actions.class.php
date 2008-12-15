<?php

/**
 * application actions.
 *
 * @package    OpenPNE
 * @subpackage application
 * @author     Shogo Kawahara<kawahara@tejimaya.net>
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class applicationActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    return $this->redirect('application/applicationConfig');
  }

 /**
  * Executes applicationConfig action
  *
  * @param sfRequest $request A request object
  */
  public function executeApplicationConfig($request)
  {
    $this->applicationConfigForm = new ApplicationConfigForm();

    if (!$request->isMethod('post'))
    {
      return sfView::SUCCESS;
    }

    $this->applicationConfigForm->bind($request->getParameter('application_config'));
    if ($this->applicationConfigForm->isValid())
    {
      $this->applicationConfigForm->save();
    }
    return sfView::SUCCESS;
  }

 /**
  * Executes list action
  *
  * @param sfRequest $request A request object
  */
  public function executeList($request)
  {
    $criteria = new Criteria();
    $criteria->addDescendingOrderByColumn(ApplicationPeer::ID);
    $this->pager = new sfPropelPager('Application', 20);
    $this->pager->setCriteria($criteria);
    $this->pager->setPage($request->getParameter('page',1));
    $this->pager->init();

    $this->addform = new AddApplicationForm();
    if (!$request->isMethod('post'))
    {
      return sfView::SUCCESS;
    }

    $this->addform->bind($request->getParameter('contact'));
    if (!$this->addform->isValid())
    {
      return sfView::SUCCESS;
    }

    $contact = $this->addform->getValues();
    try
    {
      $application = ApplicationPeer::addApplication($contact['application_url'], $this->getUser()->getCulture(),true);      
    }
    catch (Exception $e)
    {
      return sfView::ERROR;
    }
    return $this->redirect('application/info?id='.$application->getId());
  }

 /**
  * Execute info action
  *
  * @param sfRequest $request A request object
  */
  public function executeInfo($request)
  {
    $application_id = $request->getParameter('id',false);
    if (!$application_id)
    {
      return sfView::ERROR;
    }

    $application = ApplicationPeer::retrieveByPk($application_id);
    if (!$application)
    {
      return sfView::ERROR;
    }

    $this->application = $application;
    return sfView::SUCCESS;
  }

 /**
  * Execute profileSetting action
  *
  * @param sfRequest $request A request object
  */
  public function executeProfileSetting($request)
  {
    $this->profileConfigForm = new OpenSocialPersonFieldConfigForm();
    return sfView::SUCCESS;
  }
}
