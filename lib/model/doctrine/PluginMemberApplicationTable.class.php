<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginMemberApplicationTable
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class PluginMemberApplicationTable extends Doctrine_Table
{
  /**
   * find one by application and member
   *
   * @param Application $application
   * @param Member      $member
   * @return MemberApplication
   */
  public function findOneByApplicationAndMember($application, $member)
  {
    return $this->createQuery()
      ->where('application_id = ?', $application->getId())
      ->andWhere('member_id =?', $member->getId())
      ->fetchOne();
  }

 /**
  * get member applications query
  *
  * @param integer $memberId
  * @param integer $viewerId
  * @param boolean $isCheckActive
  * @param boolean $isPc
  * @param boolean $isMobile
  * @return Doctrine_Query
  */
  protected function getMemberApplicationsQuery($memberId = null, $viewerId = null, $isCheckActive = true, $isPc = null, $isMobile = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    if (null === $viewerId)
    {
      $viewerId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $q = $this->createQuery('ma')
      ->where('ma.member_id = ?', $memberId);
    if ($memberId != $viewerId)
    {
      $dql = 'ma.public_flag = ?';
      $dqlParams = array('public');

      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $viewerId);
      if ($relation && $relation->isFriend())
      {
        $dql .= ' OR ma.public_flag = ?';
        $dqlParams[] = 'friends';
      }
      $q->andWhere('('.$dql.')', $dqlParams);
    }

    if ($isCheckActive || null !== $isPc || null !== $isMobile)
    {
      $q->innerJoin('ma.Application a');
    }

    if ($isCheckActive)
    {
      $q->andWhere('a.is_active = ?', true);
    }

    if (null !== $isPc)
    {
      $q->andWhere('a.is_pc = ?', $isPc);
    }

    if (null !== $isMobile)
    {
      $q->andWhere('a.is_mobile = ?', $isMobile);
    }

    $q->orderBy('ma.sort_order');
    return $q;
  }

 /**
  * get member applications
  *
  * @param integer $memberId
  * @param integer $viewerId
  * @param boolean $isCheckActive
  * @param boolean $isPc
  * @param boolean $isMobile
  * @return Doctrine_Collection
  */
  public function getMemberApplications($memberId = null, $viewerId = null, $isCheckActive = true, $isPc = null, $isMobile = null)
  {
    return $this->getMemberApplicationsQuery($memberId, $viewerId, $isCheckActive, $isPc, $isMobile)->execute();
  }

 /**
  * get member application list pager
  *
  * @param integer $page
  * @param integer $size
  * @param integer $memberId
  * @param integer $viewerId
  * @param boolean $isCheckActive
  * @param boolean $isPc
  * @param boolean $isMobile
  * @return sfDoctrinePager
  */
  public function getMemberApplicationListPager($page = 1, $size = 20, $memberId = null, $viewerId = null, $isCheckActive = true, $isPc = null, $isMobile = null)
  {
    $pager = new sfDoctrinePager('MemberApplication', $size);
    $q = $this->getMemberApplicationsQuery($memberId, $viewerId, $isCheckActive, $isPc, $isMobile);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();
    return $pager;
  }

 /**
  * get installed friend ids
  *
  * @param Application $application
  * @param Member $member
  * @return array
  */
  public function getInstalledFriendIds($application, $member = null)
  {
    if (null === $member)
    {
      $member = sfContext::getInstance()->getUser()->getMember();
    }

    $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($member->getId());
    if (!$friendIds)
    {
      return array();
    }

    $q = $this->createQuery()
      ->where('application_id = ?', $application->id)
      ->andWhereIn('member_id', $friendIds)
      ->andWhere('(public_flag = ? OR public_flag = ?)', array('public', 'friends'));

    return $q->execute()->toKeyValueArray('id', 'member_id');
  }
}
