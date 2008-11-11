<?php


abstract class BaseApplication extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $url;


	
	protected $height;


	
	protected $scrolling;

	
	protected $collApplicationI18ns;

	
	protected $lastApplicationI18nCriteria = null;

	
	protected $collApplicationSettings;

	
	protected $lastApplicationSettingCriteria = null;

	
	protected $collMemberApplications;

	
	protected $lastMemberApplicationCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

  
  protected $culture;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getUrl()
	{

		return $this->url;
	}

	
	public function getHeight()
	{

		return $this->height;
	}

	
	public function getScrolling()
	{

		return $this->scrolling;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ApplicationPeer::ID;
		}

	} 
	
	public function setUrl($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = ApplicationPeer::URL;
		}

	} 
	
	public function setHeight($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->height !== $v) {
			$this->height = $v;
			$this->modifiedColumns[] = ApplicationPeer::HEIGHT;
		}

	} 
	
	public function setScrolling($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->scrolling !== $v) {
			$this->scrolling = $v;
			$this->modifiedColumns[] = ApplicationPeer::SCROLLING;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->url = $rs->getString($startcol + 1);

			$this->height = $rs->getInt($startcol + 2);

			$this->scrolling = $rs->getInt($startcol + 3);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 4; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Application object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ApplicationPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ApplicationPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ApplicationPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collApplicationI18ns !== null) {
				foreach($this->collApplicationI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collApplicationSettings !== null) {
				foreach($this->collApplicationSettings as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMemberApplications !== null) {
				foreach($this->collMemberApplications as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = ApplicationPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collApplicationI18ns !== null) {
					foreach($this->collApplicationI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collApplicationSettings !== null) {
					foreach($this->collApplicationSettings as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMemberApplications !== null) {
					foreach($this->collMemberApplications as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getUrl();
				break;
			case 2:
				return $this->getHeight();
				break;
			case 3:
				return $this->getScrolling();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUrl(),
			$keys[2] => $this->getHeight(),
			$keys[3] => $this->getScrolling(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setUrl($value);
				break;
			case 2:
				$this->setHeight($value);
				break;
			case 3:
				$this->setScrolling($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUrl($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setHeight($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setScrolling($arr[$keys[3]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationPeer::ID)) $criteria->add(ApplicationPeer::ID, $this->id);
		if ($this->isColumnModified(ApplicationPeer::URL)) $criteria->add(ApplicationPeer::URL, $this->url);
		if ($this->isColumnModified(ApplicationPeer::HEIGHT)) $criteria->add(ApplicationPeer::HEIGHT, $this->height);
		if ($this->isColumnModified(ApplicationPeer::SCROLLING)) $criteria->add(ApplicationPeer::SCROLLING, $this->scrolling);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

		$criteria->add(ApplicationPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUrl($this->url);

		$copyObj->setHeight($this->height);

		$copyObj->setScrolling($this->scrolling);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getApplicationI18ns() as $relObj) {
				$copyObj->addApplicationI18n($relObj->copy($deepCopy));
			}

			foreach($this->getApplicationSettings() as $relObj) {
				$copyObj->addApplicationSetting($relObj->copy($deepCopy));
			}

			foreach($this->getMemberApplications() as $relObj) {
				$copyObj->addMemberApplication($relObj->copy($deepCopy));
			}

		} 

		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ApplicationPeer();
		}
		return self::$peer;
	}

	
	public function initApplicationI18ns()
	{
		if ($this->collApplicationI18ns === null) {
			$this->collApplicationI18ns = array();
		}
	}

	
	public function getApplicationI18ns($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collApplicationI18ns === null) {
			if ($this->isNew()) {
			   $this->collApplicationI18ns = array();
			} else {

				$criteria->add(ApplicationI18nPeer::ID, $this->getId());

				ApplicationI18nPeer::addSelectColumns($criteria);
				$this->collApplicationI18ns = ApplicationI18nPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ApplicationI18nPeer::ID, $this->getId());

				ApplicationI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastApplicationI18nCriteria) || !$this->lastApplicationI18nCriteria->equals($criteria)) {
					$this->collApplicationI18ns = ApplicationI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastApplicationI18nCriteria = $criteria;
		return $this->collApplicationI18ns;
	}

	
	public function countApplicationI18ns($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ApplicationI18nPeer::ID, $this->getId());

		return ApplicationI18nPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addApplicationI18n(ApplicationI18n $l)
	{
		$this->collApplicationI18ns[] = $l;
		$l->setApplication($this);
	}

	
	public function initApplicationSettings()
	{
		if ($this->collApplicationSettings === null) {
			$this->collApplicationSettings = array();
		}
	}

	
	public function getApplicationSettings($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collApplicationSettings === null) {
			if ($this->isNew()) {
			   $this->collApplicationSettings = array();
			} else {

				$criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->getId());

				ApplicationSettingPeer::addSelectColumns($criteria);
				$this->collApplicationSettings = ApplicationSettingPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->getId());

				ApplicationSettingPeer::addSelectColumns($criteria);
				if (!isset($this->lastApplicationSettingCriteria) || !$this->lastApplicationSettingCriteria->equals($criteria)) {
					$this->collApplicationSettings = ApplicationSettingPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastApplicationSettingCriteria = $criteria;
		return $this->collApplicationSettings;
	}

	
	public function countApplicationSettings($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->getId());

		return ApplicationSettingPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addApplicationSetting(ApplicationSetting $l)
	{
		$this->collApplicationSettings[] = $l;
		$l->setApplication($this);
	}


	
	public function getApplicationSettingsJoinMember($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collApplicationSettings === null) {
			if ($this->isNew()) {
				$this->collApplicationSettings = array();
			} else {

				$criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->getId());

				$this->collApplicationSettings = ApplicationSettingPeer::doSelectJoinMember($criteria, $con);
			}
		} else {
									
			$criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->getId());

			if (!isset($this->lastApplicationSettingCriteria) || !$this->lastApplicationSettingCriteria->equals($criteria)) {
				$this->collApplicationSettings = ApplicationSettingPeer::doSelectJoinMember($criteria, $con);
			}
		}
		$this->lastApplicationSettingCriteria = $criteria;

		return $this->collApplicationSettings;
	}

	
	public function initMemberApplications()
	{
		if ($this->collMemberApplications === null) {
			$this->collMemberApplications = array();
		}
	}

	
	public function getMemberApplications($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberApplications === null) {
			if ($this->isNew()) {
			   $this->collMemberApplications = array();
			} else {

				$criteria->add(MemberApplicationPeer::APPLICATION_ID, $this->getId());

				MemberApplicationPeer::addSelectColumns($criteria);
				$this->collMemberApplications = MemberApplicationPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(MemberApplicationPeer::APPLICATION_ID, $this->getId());

				MemberApplicationPeer::addSelectColumns($criteria);
				if (!isset($this->lastMemberApplicationCriteria) || !$this->lastMemberApplicationCriteria->equals($criteria)) {
					$this->collMemberApplications = MemberApplicationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMemberApplicationCriteria = $criteria;
		return $this->collMemberApplications;
	}

	
	public function countMemberApplications($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MemberApplicationPeer::APPLICATION_ID, $this->getId());

		return MemberApplicationPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addMemberApplication(MemberApplication $l)
	{
		$this->collMemberApplications[] = $l;
		$l->setApplication($this);
	}


	
	public function getMemberApplicationsJoinMember($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberApplications === null) {
			if ($this->isNew()) {
				$this->collMemberApplications = array();
			} else {

				$criteria->add(MemberApplicationPeer::APPLICATION_ID, $this->getId());

				$this->collMemberApplications = MemberApplicationPeer::doSelectJoinMember($criteria, $con);
			}
		} else {
									
			$criteria->add(MemberApplicationPeer::APPLICATION_ID, $this->getId());

			if (!isset($this->lastMemberApplicationCriteria) || !$this->lastMemberApplicationCriteria->equals($criteria)) {
				$this->collMemberApplications = MemberApplicationPeer::doSelectJoinMember($criteria, $con);
			}
		}
		$this->lastMemberApplicationCriteria = $criteria;

		return $this->collMemberApplications;
	}

  public function getCulture()
  {
    return $this->culture;
  }

  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  public function getTitle($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getTitle();
  }

  public function setTitle($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setTitle($value);
  }

  public function getDirectoryTitle($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getDirectoryTitle();
  }

  public function setDirectoryTitle($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setDirectoryTitle($value);
  }

  public function getScreenshot($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getScreenshot();
  }

  public function setScreenshot($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setScreenshot($value);
  }

  public function getThumbnail($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getThumbnail();
  }

  public function setThumbnail($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setThumbnail($value);
  }

  public function getAuthor($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getAuthor();
  }

  public function setAuthor($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setAuthor($value);
  }

  public function getAuthorEmail($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getAuthorEmail();
  }

  public function setAuthorEmail($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setAuthorEmail($value);
  }

  public function getDescription($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getDescription();
  }

  public function setDescription($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setDescription($value);
  }

  public function getSettings($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getSettings();
  }

  public function setSettings($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setSettings($value);
  }

  public function getViews($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getViews();
  }

  public function setViews($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setViews($value);
  }

  public function getVersion($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getVersion();
  }

  public function setVersion($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setVersion($value);
  }

  public function getUpdatedAt($culture = null)
  {
    return $this->getCurrentApplicationI18n($culture)->getUpdatedAt();
  }

  public function setUpdatedAt($value, $culture = null)
  {
    $this->getCurrentApplicationI18n($culture)->setUpdatedAt($value);
  }

  protected $current_i18n = array();

  public function getCurrentApplicationI18n($culture = null)
  {
    if (is_null($culture))
    {
      $culture = is_null($this->culture) ? sfPropel::getDefaultCulture() : $this->culture;
    }

    if (!isset($this->current_i18n[$culture]))
    {
      $obj = ApplicationI18nPeer::retrieveByPK($this->getId(), $culture);
      if ($obj)
      {
        $this->setApplicationI18nForCulture($obj, $culture);
      }
      else
      {
        $this->setApplicationI18nForCulture(new ApplicationI18n(), $culture);
        $this->current_i18n[$culture]->setCulture($culture);
      }
    }

    return $this->current_i18n[$culture];
  }

  public function setApplicationI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addApplicationI18n($object);
  }

} 