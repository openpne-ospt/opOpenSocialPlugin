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
  public function preExecute()
  {
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof MemberApplication)
      {
        $this->memberApplication = $object;
        $this->application       = $this->memberApplication->getApplication();
        $this->member            = $this->memberApplication->getMember();
      }
      elseif ($object instanceof Application)
      {
        $this->application = $object;
      }
      elseif ($object instanceof Member)
      {
        $this->member = $object;
      }
    }

    if (empty($this->member))
    {
      $this->member = $this->getUser()->getMember();
    }
    elseif ($this->member->getId() != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $this->member->getMemberId());
    }
  }

  /**
   * Executes canvas action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCanvas(sfWebRequest $request)
  {
    $this->forward404Unless($this->memberApplication->isViewable());
  }

  /**
   * Executes list action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeList(sfWebRequest $request)
  {
    $memberId    = $this->getUser()->getMemberId();
    $owner       = $this->member;
    $ownerId     = $owner->getId();

    $this->isOwner = false;
    if ($memberId == $ownerId)
    {
      $this->isOwner = true;
      $this->form = new AddApplicationForm();
    }

    $this->memberApplications = Doctrine::getTable('MemberApplication')->getMemberApplications($ownerId);

    $this->isAddApplication = ($this->isOwner && Doctrine::getTable('SnsConfig')->get('allow_add_application', false));
    if ($request->isMethod(sfRequest::POST) && $this->isAddApplication)
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
          $memberApplication = $application->addToMember($owner);
        }
        catch (Exception $e)
        {
          $this->getUser()->setFlash('notice', 'Failed in adding the application.');
        }
        $this->redirect('@my_application_list');
      }
    }
  }

  /**
   * Executes setting action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeSetting(sfWebRequest $request)
  {
    $modId = $request->getParameter('id');
    $memberApp = MemberApplicationPeer::retrieveByPK($modId);
    $this->forward404Unless($memberApp);

    $this->forward404If($memberApp->getMember()->getId() != $this->getUser()->getMember()->getId());
    $this->forward404If($memberApp->getIsGadget() && !$memberApp->getApplication()->hasSetting());

    $this->appName = $memberApp->getApplication()->getTitle();

    $applicationSettingForm = new MemberApplicationSettingForm();
    $memberId = $this->getUser()->getMember()->getId();
    $applicationSettingForm->setConfigWidgets($memberId, $modId);

    $this->forms = array($applicationSettingForm);

    if (!$memberApp->getIsGadget())
    {
      $memberApplicationForm = new MemberApplicationForm($memberApp);
      $this->forms[] = $memberApplicationForm;
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $valid = true;
      if (!$memberApp->getIsGadget())
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
   * @param sfWebRequest $request A request object
   */
  public function executeGallery(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application', array('order_by' => 'desc_users')));
    if ($this->searchForm->isValid())
    {
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1));
    }
    return sfView::SUCCESS;
  }

  /**
   * Executes add action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeAdd(sfWebRequest $request)
  {
    $memberApplicaiton = $this->application->addToMember($this->member);
    $this->redirect('@application_canvas?id='.$memberApplication->getId());
  }

  /**
   * Executes remove action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeRemove(sfWebRequest $request)
  {
    $this->forward404If($this->getUser()->getMemberId() != $this->memberApplication->getMember()->getId());
    $this->forward404If($this->memberApplication->getIsGadget());

    if ($request->isMethod(sfRequest::POST))
    {
      $form = new sfForm();
      $form->bind(array('_csrf_token' => $request->getParameter('_csrf_token')));
      if ($form->isValid())
      {
        $this->memberApplication->delete();
        $this->getUser()->setFlash('notice', 'The application was removed successfully.');
        $this->redirect('@my_application_list');
      }
    }
    return sfView::SUCCESS;
  }

  public function executeMember(sfWebRequest $request)
  {
    $this->pager = $this->application->getMemberListPager($request->getParameter('page', 1));
  }

  /**
   * Executes info action
   * 
   * @param sfWebRequest $request A request object
   */ 
  public function executeInfo(sfWebRequest $request)
  {
  }

  /**
   * Executes js action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeJs(sfWebRequest $request)
  {
    $response = $this->getResponse();
    $response->setContentType('text/javascript');
    return sfView::SUCCESS;
  }

  /**
   * Executes sort application
   * 
   * @param sfWebRequest $request A request object
   */
  public function executeSort(sfWebRequest $request)
  {
    /*
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
     */
    return sfView::NONE;
  }
}
