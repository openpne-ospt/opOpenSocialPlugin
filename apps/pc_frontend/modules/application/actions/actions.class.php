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
    $this->memberApp = MemberApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($this->memberApp);
    
    if ($this->getUser()->getMember()->getId() != $this->memberApp->getMemberId())
    {
      if (!$this->memberApp->getIsDispOther())
      {
        return sfView::ERROR;
      }
      sfConfig::set('sf_navi_type', 'friend');
      sfConfig::set('sf_navi_id', $this->memberApp->getMemberId());
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
    $memberId = $this->getUser()->getMemberId();
    $ownerId  = $request->hasParameter('id') ? $request->getParameter('id') : $memberId;

    $this->isOwner = false;
    if ($memberId == $ownerId)
    {
      $this->isOwner = true;
      $this->form = new AddApplicationForm();
    }

    $this->memberApps = MemberApplicationPeer::getMemberApplicationList($ownerId, $memberId);
    if (!$this->isOwner)
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    $this->isAddApplication = false;
    $snsConfig = SnsConfigPeer::retrieveByName('is_add_application');
    if ($snsConfig && $snsConfig->getValue())
    {
      $this->isAddApplication = true;
    }

    if ($request->isMethod(sfRequest::POST) && $this->isAddApplication)
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
      $memberApp = self::addApplicationToMember($application, $memberId);
      $this->redirect('application/canvas?id='.$memberApp->getId());
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
    $memberApp = MemberApplicationPeer::retrieveByPK($modId);
    $this->forward404Unless($memberApp);

    if ($memberApp->getMember()->getId() != $this->getUser()->getMember()->getId())
    {
      return sfView::ERROR;
    }

    if ($memberApp->getIsHomeWidget() && !$memberApp->getApplication()->hasSetting())
    {
      $this->forward404();
    }

    $this->appName = $memberApp->getApplication()->getTitle();

    $applicationSettingForm = new MemberApplicationSettingForm();
    $memberId = $this->getUser()->getMember()->getId();
    $applicationSettingForm->setConfigWidgets($memberId, $modId);

    $this->forms = array($applicationSettingForm);

    if (!$memberApp->getIsHomeWidget())
    {
      $memberApplicationForm = new MemberApplicationForm($memberApp);
      $this->forms[] = $memberApplicationForm;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $valid = true;
      if (!$memberApp->getIsHomeWidget())
      {
        $memberApplicationForm->bind($request->getParameter('member_app_setting'));
        if ($memberApplicationForm->isValid())
        {
          $memberApplicationForm->save();
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
        $this->isValid = true;
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

    $memberId = $this->getUser()->getMemberId();

    $memberApp = self::addApplicationToMember($application, $memberId);
    $this->redirect('application/canvas?id='.$memberApp->getId());
  }

  /**
   * Executes remove action
   *
   * @param sfRequest $request A request object
   */
  public function executeRemove($request)
  {
    $memberApp = MemberApplicationPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($memberApp);

    $memberId = $this->getUser()->getMember()->getId();
    if ($memberId != $memberApp->getMember()->getId())
    {
      return sfView::ERROR;
    }

    if ($memberApp->getIsHomeWidget())
    {
      return sfView::ERROR;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $memberApp->delete();
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
      $memberId = $this->getUser()->getMember()->getId();
      $order = $request->getParameter('order');
      foreach ($order as $key => $value)
      {
        $memberApp = MemberApplicationPeer::retrieveByPK($value);
        if ($memberApp && $memberApp->getMemberId() == $memberId)
        {
          $memberApp->setSortOrder($key);
          $memberApp->save();
        }
      }
    }
    return sfView::NONE;
  }
}
