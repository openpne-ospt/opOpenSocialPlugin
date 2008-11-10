<?php


abstract class BaseApplication extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $url;


	
	protected $culture;


	
	protected $title;


	
	protected $directory_title;


	
	protected $screenshot;


	
	protected $thumbnail;


	
	protected $author;


	
	protected $author_email;


	
	protected $description;


	
	protected $settings;


	
	protected $views;


	
	protected $version;


	
	protected $height;


	
	protected $scrolling;


	
	protected $modified;

	
	protected $collApplicationSettings;

	
	protected $lastApplicationSettingCriteria = null;

	
	protected $collMemberApplications;

	
	protected $lastMemberApplicationCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getUrl()
	{

		return $this->url;
	}

	
	public function getCulture()
	{

		return $this->culture;
	}

	
	public function getTitle()
	{

		return $this->title;
	}

	
	public function getDirectoryTitle()
	{

		return $this->directory_title;
	}

	
	public function getScreenshot()
	{

		return $this->screenshot;
	}

	
	public function getThumbnail()
	{

		return $this->thumbnail;
	}

	
	public function getAuthor()
	{

		return $this->author;
	}

	
	public function getAuthorEmail()
	{

		return $this->author_email;
	}

	
	public function getDescription()
	{

		return $this->description;
	}

	
	public function getSettings()
	{

		return $this->settings;
	}

	
	public function getViews()
	{

		return $this->views;
	}

	
	public function getVersion()
	{

		return $this->version;
	}

	
	public function getHeight()
	{

		return $this->height;
	}

	
	public function getScrolling()
	{

		return $this->scrolling;
	}

	
	public function getModified()
	{

		return $this->modified;
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
	
	public function setCulture($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = ApplicationPeer::CULTURE;
		}

	} 
	
	public function setTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = ApplicationPeer::TITLE;
		}

	} 
	
	public function setDirectoryTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->directory_title !== $v) {
			$this->directory_title = $v;
			$this->modifiedColumns[] = ApplicationPeer::DIRECTORY_TITLE;
		}

	} 
	
	public function setScreenshot($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->screenshot !== $v) {
			$this->screenshot = $v;
			$this->modifiedColumns[] = ApplicationPeer::SCREENSHOT;
		}

	} 
	
	public function setThumbnail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->thumbnail !== $v) {
			$this->thumbnail = $v;
			$this->modifiedColumns[] = ApplicationPeer::THUMBNAIL;
		}

	} 
	
	public function setAuthor($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author !== $v) {
			$this->author = $v;
			$this->modifiedColumns[] = ApplicationPeer::AUTHOR;
		}

	} 
	
	public function setAuthorEmail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author_email !== $v) {
			$this->author_email = $v;
			$this->modifiedColumns[] = ApplicationPeer::AUTHOR_EMAIL;
		}

	} 
	
	public function setDescription($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = ApplicationPeer::DESCRIPTION;
		}

	} 
	
	public function setSettings($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->settings !== $v) {
			$this->settings = $v;
			$this->modifiedColumns[] = ApplicationPeer::SETTINGS;
		}

	} 
	
	public function setViews($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->views !== $v) {
			$this->views = $v;
			$this->modifiedColumns[] = ApplicationPeer::VIEWS;
		}

	} 
	
	public function setVersion($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->version !== $v) {
			$this->version = $v;
			$this->modifiedColumns[] = ApplicationPeer::VERSION;
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
	
	public function setModified($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->modified !== $v) {
			$this->modified = $v;
			$this->modifiedColumns[] = ApplicationPeer::MODIFIED;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->url = $rs->getString($startcol + 1);

			$this->culture = $rs->getString($startcol + 2);

			$this->title = $rs->getString($startcol + 3);

			$this->directory_title = $rs->getString($startcol + 4);

			$this->screenshot = $rs->getString($startcol + 5);

			$this->thumbnail = $rs->getString($startcol + 6);

			$this->author = $rs->getString($startcol + 7);

			$this->author_email = $rs->getString($startcol + 8);

			$this->description = $rs->getString($startcol + 9);

			$this->settings = $rs->getString($startcol + 10);

			$this->views = $rs->getString($startcol + 11);

			$this->version = $rs->getString($startcol + 12);

			$this->height = $rs->getInt($startcol + 13);

			$this->scrolling = $rs->getInt($startcol + 14);

			$this->modified = $rs->getInt($startcol + 15);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 16; 
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
				return $this->getCulture();
				break;
			case 3:
				return $this->getTitle();
				break;
			case 4:
				return $this->getDirectoryTitle();
				break;
			case 5:
				return $this->getScreenshot();
				break;
			case 6:
				return $this->getThumbnail();
				break;
			case 7:
				return $this->getAuthor();
				break;
			case 8:
				return $this->getAuthorEmail();
				break;
			case 9:
				return $this->getDescription();
				break;
			case 10:
				return $this->getSettings();
				break;
			case 11:
				return $this->getViews();
				break;
			case 12:
				return $this->getVersion();
				break;
			case 13:
				return $this->getHeight();
				break;
			case 14:
				return $this->getScrolling();
				break;
			case 15:
				return $this->getModified();
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
			$keys[2] => $this->getCulture(),
			$keys[3] => $this->getTitle(),
			$keys[4] => $this->getDirectoryTitle(),
			$keys[5] => $this->getScreenshot(),
			$keys[6] => $this->getThumbnail(),
			$keys[7] => $this->getAuthor(),
			$keys[8] => $this->getAuthorEmail(),
			$keys[9] => $this->getDescription(),
			$keys[10] => $this->getSettings(),
			$keys[11] => $this->getViews(),
			$keys[12] => $this->getVersion(),
			$keys[13] => $this->getHeight(),
			$keys[14] => $this->getScrolling(),
			$keys[15] => $this->getModified(),
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
				$this->setCulture($value);
				break;
			case 3:
				$this->setTitle($value);
				break;
			case 4:
				$this->setDirectoryTitle($value);
				break;
			case 5:
				$this->setScreenshot($value);
				break;
			case 6:
				$this->setThumbnail($value);
				break;
			case 7:
				$this->setAuthor($value);
				break;
			case 8:
				$this->setAuthorEmail($value);
				break;
			case 9:
				$this->setDescription($value);
				break;
			case 10:
				$this->setSettings($value);
				break;
			case 11:
				$this->setViews($value);
				break;
			case 12:
				$this->setVersion($value);
				break;
			case 13:
				$this->setHeight($value);
				break;
			case 14:
				$this->setScrolling($value);
				break;
			case 15:
				$this->setModified($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUrl($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCulture($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setTitle($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDirectoryTitle($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setScreenshot($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setThumbnail($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setAuthor($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setAuthorEmail($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setDescription($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setSettings($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setViews($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setVersion($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setHeight($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setScrolling($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setModified($arr[$keys[15]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationPeer::ID)) $criteria->add(ApplicationPeer::ID, $this->id);
		if ($this->isColumnModified(ApplicationPeer::URL)) $criteria->add(ApplicationPeer::URL, $this->url);
		if ($this->isColumnModified(ApplicationPeer::CULTURE)) $criteria->add(ApplicationPeer::CULTURE, $this->culture);
		if ($this->isColumnModified(ApplicationPeer::TITLE)) $criteria->add(ApplicationPeer::TITLE, $this->title);
		if ($this->isColumnModified(ApplicationPeer::DIRECTORY_TITLE)) $criteria->add(ApplicationPeer::DIRECTORY_TITLE, $this->directory_title);
		if ($this->isColumnModified(ApplicationPeer::SCREENSHOT)) $criteria->add(ApplicationPeer::SCREENSHOT, $this->screenshot);
		if ($this->isColumnModified(ApplicationPeer::THUMBNAIL)) $criteria->add(ApplicationPeer::THUMBNAIL, $this->thumbnail);
		if ($this->isColumnModified(ApplicationPeer::AUTHOR)) $criteria->add(ApplicationPeer::AUTHOR, $this->author);
		if ($this->isColumnModified(ApplicationPeer::AUTHOR_EMAIL)) $criteria->add(ApplicationPeer::AUTHOR_EMAIL, $this->author_email);
		if ($this->isColumnModified(ApplicationPeer::DESCRIPTION)) $criteria->add(ApplicationPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(ApplicationPeer::SETTINGS)) $criteria->add(ApplicationPeer::SETTINGS, $this->settings);
		if ($this->isColumnModified(ApplicationPeer::VIEWS)) $criteria->add(ApplicationPeer::VIEWS, $this->views);
		if ($this->isColumnModified(ApplicationPeer::VERSION)) $criteria->add(ApplicationPeer::VERSION, $this->version);
		if ($this->isColumnModified(ApplicationPeer::HEIGHT)) $criteria->add(ApplicationPeer::HEIGHT, $this->height);
		if ($this->isColumnModified(ApplicationPeer::SCROLLING)) $criteria->add(ApplicationPeer::SCROLLING, $this->scrolling);
		if ($this->isColumnModified(ApplicationPeer::MODIFIED)) $criteria->add(ApplicationPeer::MODIFIED, $this->modified);

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

		$copyObj->setCulture($this->culture);

		$copyObj->setTitle($this->title);

		$copyObj->setDirectoryTitle($this->directory_title);

		$copyObj->setScreenshot($this->screenshot);

		$copyObj->setThumbnail($this->thumbnail);

		$copyObj->setAuthor($this->author);

		$copyObj->setAuthorEmail($this->author_email);

		$copyObj->setDescription($this->description);

		$copyObj->setSettings($this->settings);

		$copyObj->setViews($this->views);

		$copyObj->setVersion($this->version);

		$copyObj->setHeight($this->height);

		$copyObj->setScrolling($this->scrolling);

		$copyObj->setModified($this->modified);


		if ($deepCopy) {
									$copyObj->setNew(false);

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

} 