<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginApplication
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    opOpenSocialPlugin
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
abstract class PluginApplication extends BaseApplication
{
 /**
  * add this application to member
  *
  * @param Member $member
  * @return MemberApplication
  */
  public function addToMember(Member $member, $applicationSettings = array())
  {
    $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($this, $member);

    if (!$memberApplication)
    {
      $memberApplication = new MemberApplication();
      $memberApplication->setApplication($this);
      $memberApplication->setMember($member);
      $memberApplication->save();
    }

    foreach ($applicationSettings as $name => $value)
    {
      $memberApplication->setApplicationSetting($name, $value);
    }

    return $memberApplication;
  }

 /**
  * this application is had by the member
  *
  * @param integer $memberId
  * @return boolean
  */
  public function isHadByMember($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $result = Doctrine::getTable('MemberApplication')->createQuery()
      ->where('application_id = ?', $this->getId())
      ->andWhere('member_id = ?', $memberId)
      ->fetchOne();

    return $result ? true : false;
  }

 /**
  * get pager of members that has this application
  *
  * @param integer $page
  * @param integer $size
  */
  public function getMemberListPager($page = 1, $size = 50, $isRandom = false)
  {
    $query = Doctrine::getTable('Member')->createQuery('m')
      ->innerJoin('m.Applications a')
      ->where('a.id = ?', $this->getId());

    if ($isRandom)
    {
      $expr = new Doctrine_Expression('RANDOM()');
      $query->orderBy($expr);
    }

    $pager = new sfDoctrinePager('Member', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

 /**
  * get a persistent data
  *
  * @param integer $memberId
  * @param integer $name
  * @return ApplicationPersistentData
  */
  public function getPersistentData($memberId, $name)
  {
    return Doctrine::getTable('ApplicationPersistentData')->createQuery()
      ->where('application_id = ?', $this->getId())
      ->andWhere('member_id = ?', $memberId)
      ->andWhere('name = ?', $name)
      ->fetchOne();
  }

 /**
  * get persistent datas
  *
  * @param mixed $memberId
  * @param mixed $name
  * @return Doctrine_Collection of ApplicationPersistentData
  */
  public function getPersistentDatas($memberId, $name)
  {
    if (!is_array($memberId))
    {
      $memberId = array($memberId);
    }

    if (!is_array($name))
    {
      $name = array($name);
    }

    if (!count($memberId))
    {
      return null;
    }

    $query = Doctrine::getTable('ApplicationPersistentData')->createQuery()
      ->where('application_id = ?', $this->getId());

    if (1 === count($memberId)) $query->andWhere('member_id = ?', $memberId);
    else $query->andWhereIn('member_id', $memberId);

    if (count($name))
    {
      if (1 === count($name)) $query->addWhere('name = ?', current($name));
      else $query->andWhereIn('name', array_values($name));
    }

    return $query->execute();
  }

 /**
  * update application
  *
  * @param string $culture
  * @return Application
  */
  public function updateApplication($culture = null)
  {
    return $this->getTable()->addApplication($this->getUrl(), true, $culture);
  }

 /**
  * is active
  *
  * @return boolean
  */
  public function isActive()
  {
    return (bool)$this->getIsActive();
  }

 /**
  * count application members
  *
  * @return integer
  */
  public function countMembers()
  {
    return Doctrine::getTable('MemberApplication')->createQuery()
      ->where('application_id = ?', $this->getId())
      ->count();
  }
}
