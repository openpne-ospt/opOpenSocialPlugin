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
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class applicationActions extends opOpenSocialApplicationActions
{
  public function preExecute()
  {
    parent::preExecute();
    if ($this->member->getId() != $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $this->member->getId());
    }
  }

  /**
   * Executes canvas action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeCanvas(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->getIsPc());
    $this->forward404Unless($this->memberApplication->isViewable());
    $this->forward404Unless($this->application->isActive());
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

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($ownerId, $memberId);
    $this->forward404If($relation && $relation->getIsAccessBlock());

    $this->isOwner = false;
    if ($memberId == $ownerId)
    {
      $this->isOwner = true;
      $this->isInstalledApp = (bool)Doctrine::getTable('Application')->findOneByMemberId($memberId);
      $this->isInstallApp = !(
        (int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY) ===
        ApplicationTable::ADD_APPLICATION_DENY
      );
    }

    $this->memberApplications = Doctrine::getTable('MemberApplication')->getMemberApplications($ownerId, null, true, true);

    $this->form = new BaseForm();
  }

  /**
   * Executes setting action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeSetting(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->getIsPc());
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
      $this->pager = $this->searchForm->getPager($request->getParameter('page', 1), 20, true, true);
    }
  }

  /**
   * Executes add action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->getIsPc());
    $memberApplication = $this->processAdd($request);
    if ($memberApplication instanceof MemberApplication)
    {
      $this->redirect('@application_canvas?id='.$memberApplication->getId());
    }
  }

  /**
   * Executes remove action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeRemove(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->getIsPc());
    return parent::executeRemove($request);
  }

 /**
  * Executes view Application User List action
  *
  * @param sfWebRequest $request A request object
  */
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
    $this->forward404Unless(
      $this->application->getIsPc() ||
      $this->getUser()->getMemberId() == $this->application->getMemberId()
    );

    $this->memberListPager = $this->application->getMemberListPager(1, 9, true);
  }

 /**
  * Executes install action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInstall(sfWebRequest $request)
  {
    $this->rule = (int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY);
    $this->forward404If($this->rule == ApplicationTable::ADD_APPLICATION_DENY);

    $this->form = new AddApplicationForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('contact'));
      if ($this->form->isValid())
      {
        try
        {
          $application = Doctrine::getTable('Application')->findOneByUrl($this->form->getValue('application_url'));
          if ($application)
          {
            $this->getUser()->setFlash('notice', 'This app is already installed.');
          }
          else
          {
            $application = Doctrine::getTable('Application')->addApplication($this->form->getValue('application_url'));
            $application->setMemberId($this->getUser()->getMemberId());
            if (ApplicationTable::ADD_APPLICATION_NECESSARY_TO_PERMIT)
            if ((int)Doctrine::getTable('SnsConfig')->get('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY) ===
              ApplicationTable::ADD_APPLICATION_NECESSARY_TO_PERMIT)
            {
              $application->setIsActive(false);
            }
            $application->save();
          }
          $this->redirect('@application_info?id='.$application->getId());
        }
        catch (Exception $e)
        {
          if (!($e instanceof sfStopException))
          {
            $this->getUser()->setFlash('error', 'Failed in adding the App.');
          }
        }
        $this->redirect('@application_install');
      }
    }
  }

 /**
  * Executes installed list action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInstalledList(sfWebRequest $request)
  {
    $page = $request->getParameter('page', 1);
    $this->pager = Doctrine::getTable('Application')->getApplicationListPager($page, 20, $this->member->getId());
    $this->forward404Unless($this->pager->getNbResults());
  }

  /**
   * Executes update application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());
    $this->application->updateApplication($this->getUser()->getCulture());
    $this->redirect('@application_info?id='.$this->application->getId());
  }

  /**
   * Executes delete application action
   *
   * @param sfWebRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->application->delete();
      $this->redirect('@my_application_list');
    }
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
      $request->checkCSRFProtection();
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

 /**
  * Executes invite
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInvite(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->getIsPc());
    $fromMember = $this->getUser()->getMember();
    $this->forward404Unless($this->application->isHadByMember($fromMember->getId()));
    $this->pager = Doctrine::getTable('MemberRelationship')->getFriendListPager($fromMember->getId(), 1, 15);
    $this->installedFriends = Doctrine::getTable('MemberApplication')->getInstalledFriendIds($this->application, $fromMember);
    $this->form = new BaseForm();
  }

 /**
  * Executes invite list
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInviteList(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
    $this->forward404Unless($this->application->getIsPc());

    $fromMember = $this->getUser()->getMember();
    $this->forward404Unless($this->application->isHadByMember($fromMember->getId()));
    $this->pager = Doctrine::getTable('MemberRelationship')->getFriendListPager($fromMember->getId(), $request->getParameter('page'), 15);
    $this->installedFriends = Doctrine::getTable('MemberApplication')->getInstalledFriendIds($this->application, $fromMember);
  }

 /**
  * Executes invite post
  *
  * @param sfWebRequest $request A request object
  */
  public function executeInvitePost(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
    $request->checkCSRFProtection();
    $this->forward404Unless($this->application->getIsPc());

    $this->getResponse()->setContentType('application/json');

    $result = $this->processInvite($request);
    if ($result)
    {
      return $this->renderText(json_encode($resultIds));
    }
    else
    {
      return $this->renderText('false');
    }
  }

 /**
  * Executes show show consumer secret
  *
  * @param sfWebRequest $request
  */
  public function executeShowConsumerSecret(sfWebRequest $request)
  {
    $this->forward404Unless($this->getRequest()->isXmlHttpRequest());
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());

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
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      if (!$this->application->getConsumerKey())
      {
        $this->application->setConsumerKey(opToolkit::generatePasswordString(16, false));
      }
      $this->application->setConsumerSecret(opToolkit::generatePasswordString(32));
      $this->application->save();
      $this->redirect('@application_info?id='.$this->application->getId());
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
    $this->forward404Unless($this->member->getId() === $this->application->getMemberId());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->application->setConsumerSecret('');
      $this->application->save();
      $this->redirect('@application_info?id='.$this->application->getId());
    }

    return sfView::INPUT;
  }
}
