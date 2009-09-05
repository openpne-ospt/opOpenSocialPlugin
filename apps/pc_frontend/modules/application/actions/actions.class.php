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

    $this->isAllowAddApplication = ($this->isOwner && Doctrine::getTable('SnsConfig')->get('is_allow_add_application', false));
    if ($request->isMethod(sfWebRequest::POST) && $this->isAllowAddApplication)
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
          $this->redirect('@application_add?id='.$application->getId());
        }
        catch (Exception $e)
        {
          $this->getUser()->setFlash('error', 'Failed in adding the application.');
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
    $this->forward404If($this->getUser()->getMemberId() != $this->memberApplication->getMember()->getId());
    $this->settingForm     = new ApplicationSettingForm(array(), array('member_application' => $this->memberApplication));
    $this->userSettingForm = new ApplicationUserSettingForm(array(), array('member_application' => $this->memberApplication));

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->settingForm->bind($request->getParameter('setting'));
      $this->userSettingForm->bind($request->getParameter('user_setting'));
      if ($this->settingForm->isValid() && $this->userSettingForm->isValid())
      {
        $this->settingForm->save();
        $this->userSettingForm->save();
        $this->isValid = true;
      }
    }
  }

  /**
   * Executes gallery action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeGallery(sfWebRequest $request)
  {
    $this->searchForm = new ApplicationSearchForm();
    $this->searchForm->bind($request->getParameter('application'));
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
    try 
    {
      $application = Doctrine::getTable('Application')->addApplication($this->application->getUrl());
      $this->application = $application;
    }
    catch (Exception $e)
    {
    }

    $memberApplication = $this->application->addToMember($this->member, array('is_view_home' => true, 'is_view_profile' => true));
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

    if ($request->isMethod(sfWebRequest::POST))
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
    $this->memberListPager = $this->application->getMemberListPager(1, 9, true); 
  }

  /**
   * Executes sort application
   * 
   * @param sfWebRequest $request A request object
   */
  public function executeSort(sfWebRequest $request)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $memberId = $this->getUser()->getMember()->getId();
      $order = $request->getParameter('order');
      foreach ($order as $key => $value)
      {
        $memberApplication = Doctrine::getTable('MemberApplication')->find($value);
        if ($memberApplication && $memberApplication->getMemberId() == $memberId)
        {
          $memberApplication->setSortOrder($key);
          $memberApplication->save();
        }
      }
    }
    return sfView::NONE;
  }
}
