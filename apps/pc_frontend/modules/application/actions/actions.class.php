<?php

/**
 * application actions.
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationActions extends sfActions
{
 /**
  * add application to member
  *
  * @param Application  $application The application instalce
  * @param integer      $memberId    A member id
  */
  protected function addApplicationToMember(Application $application, $memberId)
  {
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::IS_HOME_WIDGET, false);
    $memberApp = MemberApplicationPeer::retrieveByApplicationIdAndMemberId($application->getId(), $memberId, $criteria);
    if ($memberApp)
    {
      return $memberApp;
    }

    $memberApp = new MemberApplication();
    $memberApp->setMemberId($memberId);
    $memberApp->setApplicationId($application->getId());
    $memberApp->setIsDispOther(true);
    $memberApp->setIsDispHome(true);
    $memberApp->save();
    return $memberApp;
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    $this->redirect('application/list');
  }

 /**
  * Executes canvas action
  *
  * @param sfRequest $request A request object
  */
  public function executeCanvas($request)
  {
    $this->member_app = MemberApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($this->member_app);
    
    if ($this->getUser()->getMember()->getId() != $this->member_app->getMemberId())
    {
      if (!$this->member_app->getIsDispOther())
      {
        return sfView::ERROR;
      }
      sfConfig::set('sf_navi_type', 'friend');
      sfConfig::set('sf_navi_id', $this->member_app->getMemberId());
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
    if ($member_id == $owner_id)
    {
      $this->is_owner = true;
      $this->form = new AddApplicationForm();
    }

    $this->member_apps = MemberApplicationPeer::getMemberApplicationList($owner_id, $member_id);
    if (!$this->is_owner)
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    $this->is_add_application = false;
    $snsConfig = SnsConfigPeer::retrieveByName('is_add_application');
    if ($snsConfig && $snsConfig->getValue())
    {
      $this->is_add_application = true;
    }

    if ($request->isMethod(sfRequest::POST) && $this->is_add_application)
    {
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
      $member_app = self::addApplicationToMember($application, $member_id);
      $this->redirect('application/canvas?id='.$member_app->getId());
    }
    
    return sfView::SUCCESS;
  }

 /**
  * Executes setting action
  *
  * @param sfRequest $request A request object
  */
  public function executeSetting($request)
  {
    $modId = $request->getParameter('id');
    $member_app = MemberApplicationPeer::retrieveByPK($modId);
    $this->forward404Unless($member_app);

    if ($member_app->getMember()->getId() != $this->getUser()->getMember()->getId())
    {
      return sfView::ERROR;
    }

    if ($member_app->getIsHomeWidget() && !$member_app->getApplication()->hasSetting())
    {
      $this->forward404();
    }

    $this->appName = $member_app->getApplication()->getTitle();

    $applicationSettingForm = new ApplicationSettingForm();
    $member_id = $this->getUser()->getMember()->getId();
    $applicationSettingForm->setConfigWidgets($member_id, $modId);

    $this->forms = array($applicationSettingForm);

    if (!$member_app->getIsHomeWidget())
    {
      $memberApplicationSettingForm = new MemberApplicationSettingForm($member_app);
      $this->forms[] = $memberApplicationSettingForm;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $valid = true;
      if (!$member_app->getIsHomeWidget())
      {
        $memberApplicationSettingForm->bind($request->getParameter('member_app_setting'));
        if ($memberApplicationSettingForm->isValid())
        {
          $memberApplicationSettingForm->save();
        }
        else
        {
          $valid = false;
        }
      }

      $applicationSettingForm->bind($request->getParameter('setting'));

      if ($valid && $applicationSettingForm->isValid())
      {
        $applicationSettingForm->save($modId);
        $this->redirect('application/canvas?id='.$modId);
      }

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
    $application = ApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($application);

    $member_id = $this->getUser()->getMemberId();

    $member_app = self::addApplicationToMember($application, $member_id);
    $this->redirect('application/canvas?id='.$member_app->getId());
  }

 /**
  * Executes remove action
  *
  * @param sfRequest $request A request object
  */
  public function executeRemove($request)
  {
    $member_app = MemberApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($member_app);

    $member_id = $this->getUser()->getMember()->getId();
    if ($member_id != $member_app->getMember()->getId())
    {
      return sfView::ERROR;
    }

    if ($member_app->getIsHomeWidget())
    {
      return sfView::ERROR;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $member_app->delete();
      $this->redirect('application/list');
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
    $this->application = ApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($this->application);
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
      foreach ($order as $key => $value)
      {
        $member_app = MemberApplicationPeer::retrieveByPK($value);
        if ($member_app && $member_app->getMemberId() == $member_id)
        {
          $member_app->setSortOrder($key);
          $member_app->save();
        }
      }
    }
    return sfView::NONE;
  }
}
