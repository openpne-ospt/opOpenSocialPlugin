<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * application components
 *
 * @package    OpenPNE
 * @subpackage opOpenSocialPlugin
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class applicationComponents extends sfComponents
{
  public function executeGadget()
  {
    $culture = $this->getUser()->getCulture();
    $culture = split("_",$culture);
    $this->application = $this->memberApplication->getApplication();
    $this->height      = $this->application->getHeight() ? $this->application->getHeight() : 200;

    $viewerId = $this->getUser()->getMemberId();

    $this->isOwner = false;
    if ($this->memberApplication->getMemberId() == $viewerId)
    {
      $this->isOwner = true;
    }

    $isUseOuterShindig = Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig', false);

    $opOpenSocialContainerConfig = new opOpenSocialContainerConfig();
    $containerName = $opOpenSocialContainerConfig->getContainerName();

    $securityToken = opBasicSecurityToken::createFromValues(
      $this->memberApplication->getMemberId(),  // owner
      $viewerId,                                // viewer
      $this->application->getId(),              // app id
      $containerName,                           // domain key
      urlencode($this->application->getUrl()),  // app url
      $this->memberApplication->getId(),        // mod id
      1
    );

    $getParams = array(
      'synd'      => $containerName,
      'container' => $containerName,
      'owner'     => $this->memberApplication->getMemberId(),
      'viewer'    => $viewerId,
      'aid'       => $this->application->getId(),
      'mid'       => $this->memberApplication->getId(),
      'country'   => isset($culture[1]) ? $culture[1] : 'ALL',
      'lang'      => $culture[0],
      'view'      => $this->view,
      'parent'    => $this->getRequest()->getUri(),
      'st'        => base64_encode($securityToken->toSerialForm()),
      'url'       => $this->application->getUrl(),
    );

    $userprefParamPrefix = Shindig_Config::get('userpref_param_prefix','up_');
    foreach ($this->memberApplication->getUserSettings() as $name => $value)
    {
      $getParams[$userprefParamPrefix.$name] = $value;
    }
    if ($isUseOuterShindig)
    {
      $this->iframeUrl = SnsConfigPeer::get('shindig_url').'gadgets/ifr?'.http_build_query($getParams).'#rpctoken='.rand(0,getrandmax());
    }
    else
    {
      $this->iframeUrl = sfContext::getInstance()->getController()->genUrl('gadgets/ifr').'?'.http_build_query($getParams).'#rpctoken='.rand(0,getrandmax());
    }
  }

  public function executeRenderHomeApplications()
  {
  }

  public function executeRenderHomeGadgetApplication()
  {
    $url = $this->gadget->getConfig('url');
    if (!$url)
    {
      return null;
    }

    try
    {
      $application = ApplicationPeer::addApplication($url);
      if (!$application)
      {
        return null;
      }
    }
    catch (Exception $e)
    {
      return null;
    }

    $applicationId = $application->getId();
    $memberId      = $this->getUser()->getMember()->getId();
    $criteria = new Criteria();
    $criteria->add(MemberApplicationPeer::IS_GADGET, true);
    $memberApplication = MemberApplicationPeer::retrieveByApplicationIdAndMemberId($applicationId, $memberId, $criteria);

    if (!$memberApplication)
    {
      $memberApplication = new MemberApplication();
      $memberApplication->setApplication($application);
      $memberApplication->setMemberId($memberId);
      $memberApplication->setIsDispOther(true);
      $memberApplication->setIsDispHome(true);
      $memberApplication->setIsGadget(true);
      $memberApplication->save();
    }

    $this->memberApplication = $memberApplication;
  }
}
