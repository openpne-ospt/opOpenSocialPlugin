<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Base application actions class.
 *
 * @package    opOpenSocialPlugin
 * @subpackage action
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
abstract class opOpenSocialApplicationActions extends sfActions
{
 /**
  * preExecute
  */
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
  }

 /**
  * process add
  *
  * @param sfWebRequest $request
  */
  protected function processAdd(sfWebRequest $request)
  {
    $this->forward404Unless($this->application->isActive());

    $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($this->application, $this->member);
    if ($memberApplication)
    {
      return $memberApplication;
    }

    if ($request->hasParameter('invite'))
    {
      $invite = Doctrine::getTable('ApplicationInvite')->find($request->getParameter('invite'));
      if ($invite)
      {
        $this->forward404Unless($invite->getToMemberId() == $this->getUser()->getMemberId());
      }
    }

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      try
      {
        $application = Doctrine::getTable('Application')->addApplication($this->application->getUrl());
        $this->application = $application;
      }
      catch (Exception $e)
      {
      }

      if (isset($invite) && ($invite instanceof ApplicationInvite))
      {
        return $invite->accept();
      }
      else
      {
        return $this->application->addToMember($this->member, array('is_view_home' => true, 'is_view_profile' => true));
      }
    }
  }

 /**
  * process invite
  *
  * @param sfWebRequest $request
  */
  protected function processInvite(sfWebRequest $request)
  {
    $fromMember = $this->getUser()->getMember();
    $this->forward404Unless($this->application->isHadByMember($fromMember->getId()));

    $ids = $request->getParameter('ids', array());
    foreach ($ids as $id)
    {
      $memberRelationship = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($fromMember->getId(), $id);
      if ($memberRelationship && !$memberRelationship->isFriend())
      {
        return false;
      }
    }

    $resultIds = array();
    foreach ($ids as $id)
    {
      $applicationInvite = Doctrine::getTable('ApplicationInvite')->findOneByApplicationIdAndToMemberId($this->application->getId(), $id);
      if (!$applicationInvite)
      {
        $applicationInvite = new ApplicationInvite();
        $applicationInvite->setApplication($this->application);
        $applicationInvite->setToMemberId($id);
        $applicationInvite->setFromMemberId($fromMember->getId());
        $applicationInvite->save();
        $resultIds[] = $id;
      }
    }
    return $resultIds;
  }

  abstract public function executeList(sfWebRequest $request);

  abstract public function executeGallery(sfWebRequest $request);

  abstract public function executeAdd(sfWebRequest $request);

  abstract public function executeInfo(sfWebRequest $request);

  abstract public function executeInvite(sfWebRequest $request);

 /**
  * Executes remove
  *
  * @param sfWebRequest $request
  */
  public function executeRemove(sfWebRequest $request)
  {
    $this->forward404If($this->getUser()->getMemberId() != $this->memberApplication->getMember()->getId());

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();
      $this->memberApplication->delete();

      // to use Lifecycle Event (event.removeapp)
      $params = array(
        'memberApplication' => $this->memberApplication,
      );
      $event = new sfEvent($this, 'op_opensocial.removeapp', $params);
      sfContext::getInstance()->getEventDispatcher()->notify($event);

      $this->getUser()->setFlash('notice', 'The App was removed successfully.');
      $this->redirect('@my_application_list');
    }
  }
}
