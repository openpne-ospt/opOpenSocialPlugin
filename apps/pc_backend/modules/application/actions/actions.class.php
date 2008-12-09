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
      $app = ApplicationPeer::addApplication($contact['application_url'], $this->getUser()->getCulture());      
    }
    catch (Exception $e)
    {
      //TODO : add error action
      return sfView::SUCCESS;
    }
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

    return sfView::SUCCESS;
  }
}
