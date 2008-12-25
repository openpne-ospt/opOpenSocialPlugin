<?php

/**
 * application actions.
 *
 * @package    OpenPNE
 * @subpackage saOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationActions extends sfActions
{
 /**
  * add application to member
  *
  * @param number      $member_id   A member id
  * @param Application $application A application object
  */
  protected function addApplicationToMember($member_id, $application)
  {
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::MEMBER_ID, $member_id);
    $criteria->add(MemberApplicationPeer::APPLICATION_ID, $application->getId());
    $member_app = MemberApplicationPeer::doSelectOne($criteria);
    if (!empty($member_app))
    {
      return $member_app;
    }

    $member_app = new MemberApplication();
    $member_app->setMemberId($member_id);
    $member_app->setApplicationId($application->getId());
    $member_app->setIsDispOther(true);
    $member_app->setIsDispHome(true);
    $member_app->save();
    return $member_app;
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    return $this->redirect('application/list');
  }

 /**
  * Executes canvas action
  *
  * @param sfRequest $request A request object
  */
  public function executeCanvas($request)
  {
    if (!$request->hasParameter('mid'))
    {
      return sfView::ERROR;
    }
    $this->member_app = MemberApplicationPeer::retrieveByPK($request->getParameter('mid'));
    if (empty($this->member_app))
    {
      return sfView::ERROR; 
    }
    if ($this->getUser()->getMember()->getId() != $this->member_app->getMemberId())
    {
      if (!$this->member_app->getIsDispOther())
      {
        return sfView::ERROR;
      }
      $request->setParameter('id', $this->member_app->getMemberId());
      sfConfig::set('sf_navi_type', 'friend');
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
    $member_id = $this->getUser()->getMemberId();
    $owner_id  = $request->hasParameter('id') ? $request->getParameter('id') : $member_id;

    $this->is_owner = false;
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::MEMBER_ID, $owner_id);
    $criteria->addAscendingOrderByColumn(MemberApplicationPeer::SORT_ORDER);

    if ($member_id == $owner_id)
    {
      $this->is_owner = true;
      $this->form = new AddApplicationForm();
    }
    else
    {
      $criteria->add(MemberApplicationPeer::IS_DISP_OTHER, true);
    }

    $this->member_apps = MemberApplicationPeer::doSelect($criteria);

    if (!empty($this->member_apps) && $member_id != $owner_id)
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    $this->is_add_application = false;
    $snsConfig = SnsConfigPeer::retrieveByName('is_add_application');
    if ($snsConfig && $snsConfig->getValue())
    {
      $this->is_add_application = true;
    }

    if (!$request->isMethod('post') || !$this->is_add_application)
    {
      return sfView::SUCCESS;
    }

    $contact = $request->getParameter('contact');
    $this->form->bind($contact);
    if (!$this->form->isValid())
    {
      return sfView::SUCCESS;
    }
    $contact = $this->form->getValues();
    try
    {
      $application = ApplicationPeer::addApplication($contact['application_url'],$this->getUser()->getCulture());
    }
    catch (Exception $e)
    {
      return sfView::ERROR;
    }
    $member_app = self::addApplicationToMember($member_id, $application);
    return $this->redirect('application/canvas?mid='.$member_app->getId());
  }

 /**
  * Executes setting action
  *
  * @param sfRequest $request A request object
  */
  public function executeSetting($request)
  {
    if (!$request->hasParameter('mid'))
    {
      return sfView::ERROR;
    }

    $modId = $request->getParameter('mid');
    $member_app = MemberApplicationPeer::retrieveByPK($modId);

    if ($member_app->getMember()->getId() != $this->getUser()->getMember()->getId())
    {
      return sfView::ERROR;
    }
    
    $this->appName = $member_app->getApplication()->getTitle();

    $this->applicationSettingForm = new ApplicationSettingForm();
    $member_id = $this->getUser()->getMember()->getId();
    $this->applicationSettingForm->setConfigWidgets($member_id, $modId);

    $this->memberApplicationSettingForm = new MemberApplicationSettingForm($member_app);

    if (!$request->isMethod('post'))
    {
      return sfView::SUCCESS;
    }

    $this->memberApplicationSettingForm->bind($request->getParameter('member_app_setting'));
    $this->applicationSettingForm->bind($request->getParameter('setting'));

    if ($this->applicationSettingForm->isValid() && $this->memberApplicationSettingForm->isValid())
    {
      $this->applicationSettingForm->save($modId);
      $this->memberApplicationSettingForm->save();
      $this->redirect('application/canvas?mid='.$modId);
    }
    return sfView::SUCCESS;
  }

 /**
  * Executes gallery action
  *
  * @param sfRequest $request A request object
  */
  public function executeGallery($request)
  {
    $this->filters = new ApplicationI18nFormFilter();
    $this->filters->bind($request->getParameter('application',array()));

    $criteria = $this->filters->getCriteria();
    $criteria->setDistinct();
    $criteria->addJoin(ApplicationPeer::ID, ApplicationI18nPeer::ID);
    $criteria->addDescendingOrderByColumn(ApplicationPeer::ID);
    
    $this->pager = new sfPropelPager('Application', 10);
    $this->pager->setCriteria($criteria);
    $this->pager->setPage($request->getParameter('page',1));
    $this->pager->init();

    return sfView::SUCCESS;
  }

 /**
  * Executes add action
  *
  * @param sfRequest $request A request object
  */
  public function executeAdd($request)
  {
    $app_id = $request->getParameter('id');
    $application = ApplicationPeer::retrieveByPK($app_id);
    if (!$application)
    {
      return sfView::ERROR;
    }

    $member_id = $this->getUser()->getMemberId();

    $member_app = self::addApplicationToMember($member_id, $application);
    return $this->redirect('application/canvas?mid='.$member_app->getId());
  }

 /**
  * Executes remove action
  *
  * @param sfRequest $request A request object
  */
  public function executeRemove($request)
  {
    $mod_id = $request->getParameter('mid');
    $member_app = MemberApplicationPeer::retrieveByPK($mod_id);
    if (!$member_app)
    {
      return sfView::ERROR;
    }

    $member_id = $this->getUser()->getMember()->getId();
    if ($member_id != $member_app->getMember()->getId())
    {
      return sfView::ERROR;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $member_app->delete();
      return $this->redirect('application/list');
    }
    return sfView::SUCCESS;
  }

 /**
  * Executes info action
  * 
  * @param sfRequest $request A request object
  */ 
  public function executeInfo($request)
  {
    $app_id = $request->getParameter('id',false);
    $this->application = ApplicationPeer::retrieveByPK($app_id);
    if (!$this->application)
    {
      return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

 /**
  * Executes js action
  *
  * @param sfRequest $request A request object
  */
  public function executeJs($request)
  {
    $response = $this->getResponse();
    $response->setContentType('text/javascript');
    return sfView::SUCCESS;
  }

 /**
  * Executes sort application
  * 
  * @param sfRequest $request A request object
  */
  public function executeSortApplication($request)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $member_id = $this->getUser()->getMember()->getId();
      $order = $request->getParameter('order');
      for ($i = 0; $i < count($order); $i++)
      {
        $member_app = MemberApplicationPeer::retrieveByPK($order[$i]);
        if ($member_app && $member_app->getMemberId() == $member_id)
        {
          $member_app->setSortOrder($i);
          $member_app->save();
        }
      }
    }
    return sfView::NONE;
  }
}
