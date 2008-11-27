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
    if($this->getUser()->getMemberId() != $this->member_app->getMemberId())
    {
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
    $memberId = $this->getUser()->getMemberId();
    $ownerId  = $request->hasParameter('id') ? $request->getParameter('id') : $memberId;

    if ($memberId == $ownerId)
    {
      $this->form = new AddApplicationForm();
    }
    else
    {
      sfConfig::set('sf_navi_type', 'friend');
    }

    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::MEMBER_ID, $ownerId);
    $this->pager = new sfPropelPager('MemberApplication',20);
    $this->pager->setCriteria($criteria);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    if (!$request->isMethod('post'))
    {
      return $this->pager->getNbResults() ? sfView::SUCCESS : sfView::ERROR;
    }

    $contact = $request->getParameter('contact');
    $this->form->bind($contact);
    if (!$this->form->isValid())
    {
      return $this->pager->getNbResults() ? sfView::SUCCESS : sfView::ERROR;
    }
    $contact = $this->form->getValues();
    try
    {
      $app = ApplicationPeer::addApplication($contact['application_url'],$this->getUser()->getCulture());
    }
    catch (Exception $e)
    {
      //TODO : add error action
      return $this->pager->getNbResults() ? sfView::SUCCESS : sfView::ERROR;
    }
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::MEMBER_ID,$memberId);
    $criteria->add(MemberApplicationPeer::APPLICATION_ID,$app->getId());
    $member_app = MemberApplicationPeer::doSelectOne($criteria);
    if (!empty($member_app))
    {
      //TODO : redirect application page
      return sfView::SUCCESS;
    }
    $member_app = new MemberApplication();
    $member_app->setMemberId($memberId);
    $member_app->setApplicationId($app->getId());
    $member_app->save();
    //TODO : redirect application page
    return sfView::SUCCESS;
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
    $this->appsettingForm = new ApplicationSettingForm();
    if (!$this->appsettingForm->setConfigWidgets($request->getParameter('mid')))
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
    return sfView::SUCCESS;
  }
}
