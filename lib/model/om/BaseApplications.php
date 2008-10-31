<?php


abstract class BaseApplications extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $url;


	
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

	
	protected $collApplicationSettingss;

	
	protected $lastApplicationSettingsCriteria = null;

	
	protected $collMemberApplicationss;

	
	protected $lastMemberApplicationsCriteria = null;

	
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
			$this->modifiedColumns[] = ApplicationsPeer::ID;
		}

	} 
	
	public function setUrl($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->url !== $v) {
			$this->url = $v;
			$this->modifiedColumns[] = ApplicationsPeer::URL;
		}

	} 
	
	public function setTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = ApplicationsPeer::TITLE;
		}

	} 
	
	public function setDirectoryTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->directory_title !== $v) {
			$this->directory_title = $v;
			$this->modifiedColumns[] = ApplicationsPeer::DIRECTORY_TITLE;
		}

	} 
	
	public function setScreenshot($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->screenshot !== $v) {
			$this->screenshot = $v;
			$this->modifiedColumns[] = ApplicationsPeer::SCREENSHOT;
		}

	} 
	
	public function setThumbnail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->thumbnail !== $v) {
			$this->thumbnail = $v;
			$this->modifiedColumns[] = ApplicationsPeer::THUMBNAIL;
		}

	} 
	
	public function setAuthor($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author !== $v) {
			$this->author = $v;
			$this->modifiedColumns[] = ApplicationsPeer::AUTHOR;
		}

	} 
	
	public function setAuthorEmail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author_email !== $v) {
			$this->author_email = $v;
			$this->modifiedColumns[] = ApplicationsPeer::AUTHOR_EMAIL;
		}

	} 
	
	public function setDescription($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = ApplicationsPeer::DESCRIPTION;
		}

	} 
	
	public function setSettings($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->settings !== $v) {
			$this->settings = $v;
			$this->modifiedColumns[] = ApplicationsPeer::SETTINGS;
		}

	} 
	
	public function setViews($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->views !== $v) {
			$this->views = $v;
			$this->modifiedColumns[] = ApplicationsPeer::VIEWS;
		}

	} 
	
	public function setVersion($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->version !== $v) {
			$this->version = $v;
			$this->modifiedColumns[] = ApplicationsPeer::VERSION;
		}

	} 
	
	public function setHeight($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->height !== $v) {
			$this->height = $v;
			$this->modifiedColumns[] = ApplicationsPeer::HEIGHT;
		}

	} 
	
	public function setScrolling($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->scrolling !== $v) {
			$this->scrolling = $v;
			$this->modifiedColumns[] = ApplicationsPeer::SCROLLING;
		}

	} 
	
	public function setModified($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->modified !== $v) {
			$this->modified = $v;
			$this->modifiedColumns[] = ApplicationsPeer::MODIFIED;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->url = $rs->getString($startcol + 1);

			$this->title = $rs->getString($startcol + 2);

			$this->directory_title = $rs->getString($startcol + 3);

			$this->screenshot = $rs->getString($startcol + 4);

			$this->thumbnail = $rs->getString($startcol + 5);

			$this->author = $rs->getString($startcol + 6);

			$this->author_email = $rs->getString($startcol + 7);

			$this->description = $rs->getString($startcol + 8);

			$this->settings = $rs->getString($startcol + 9);

			$this->views = $rs->getString($startcol + 10);

			$this->version = $rs->getString($startcol + 11);

			$this->height = $rs->getInt($startcol + 12);

			$this->scrolling = $rs->getInt($startcol + 13);

			$this->modified = $rs->getInt($startcol + 14);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 15; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Applications object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ApplicationsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ApplicationsPeer::DATABASE_NAME);
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
					$pk = ApplicationsPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ApplicationsPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collApplicationSettingss !== null) {
				foreach($this->collApplicationSettingss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMemberApplicationss !== null) {
				foreach($this->collMemberApplicationss as $referrerFK) {
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


			if (($retval = ApplicationsPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collApplicationSettingss !== null) {
					foreach($this->collApplicationSettingss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMemberApplicationss !== null) {
					foreach($this->collMemberApplicationss as $referrerFK) {
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
		$pos = ApplicationsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getTitle();
				break;
			case 3:
				return $this->getDirectoryTitle();
				break;
			case 4:
				return $this->getScreenshot();
				break;
			case 5:
				return $this->getThumbnail();
				break;
			case 6:
				return $this->getAuthor();
				break;
			case 7:
				return $this->getAuthorEmail();
				break;
			case 8:
				return $this->getDescription();
				break;
			case 9:
				return $this->getSettings();
				break;
			case 10:
				return $this->getViews();
				break;
			case 11:
				return $this->getVersion();
				break;
			case 12:
				return $this->getHeight();
				break;
			case 13:
				return $this->getScrolling();
				break;
			case 14:
				return $this->getModified();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUrl(),
			$keys[2] => $this->getTitle(),
			$keys[3] => $this->getDirectoryTitle(),
			$keys[4] => $this->getScreenshot(),
			$keys[5] => $this->getThumbnail(),
			$keys[6] => $this->getAuthor(),
			$keys[7] => $this->getAuthorEmail(),
			$keys[8] => $this->getDescription(),
			$keys[9] => $this->getSettings(),
			$keys[10] => $this->getViews(),
			$keys[11] => $this->getVersion(),
			$keys[12] => $this->getHeight(),
			$keys[13] => $this->getScrolling(),
			$keys[14] => $this->getModified(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setTitle($value);
				break;
			case 3:
				$this->setDirectoryTitle($value);
				break;
			case 4:
				$this->setScreenshot($value);
				break;
			case 5:
				$this->setThumbnail($value);
				break;
			case 6:
				$this->setAuthor($value);
				break;
			case 7:
				$this->setAuthorEmail($value);
				break;
			case 8:
				$this->setDescription($value);
				break;
			case 9:
				$this->setSettings($value);
				break;
			case 10:
				$this->setViews($value);
				break;
			case 11:
				$this->setVersion($value);
				break;
			case 12:
				$this->setHeight($value);
				break;
			case 13:
				$this->setScrolling($value);
				break;
			case 14:
				$this->setModified($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUrl($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDirectoryTitle($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setScreenshot($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setThumbnail($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAuthor($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setAuthorEmail($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setDescription($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setSettings($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setViews($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setVersion($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setHeight($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setScrolling($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setModified($arr[$keys[14]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationsPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationsPeer::ID)) $criteria->add(ApplicationsPeer::ID, $this->id);
		if ($this->isColumnModified(ApplicationsPeer::URL)) $criteria->add(ApplicationsPeer::URL, $this->url);
		if ($this->isColumnModified(ApplicationsPeer::TITLE)) $criteria->add(ApplicationsPeer::TITLE, $this->title);
		if ($this->isColumnModified(ApplicationsPeer::DIRECTORY_TITLE)) $criteria->add(ApplicationsPeer::DIRECTORY_TITLE, $this->directory_title);
		if ($this->isColumnModified(ApplicationsPeer::SCREENSHOT)) $criteria->add(ApplicationsPeer::SCREENSHOT, $this->screenshot);
		if ($this->isColumnModified(ApplicationsPeer::THUMBNAIL)) $criteria->add(ApplicationsPeer::THUMBNAIL, $this->thumbnail);
		if ($this->isColumnModified(ApplicationsPeer::AUTHOR)) $criteria->add(ApplicationsPeer::AUTHOR, $this->author);
		if ($this->isColumnModified(ApplicationsPeer::AUTHOR_EMAIL)) $criteria->add(ApplicationsPeer::AUTHOR_EMAIL, $this->author_email);
		if ($this->isColumnModified(ApplicationsPeer::DESCRIPTION)) $criteria->add(ApplicationsPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(ApplicationsPeer::SETTINGS)) $criteria->add(ApplicationsPeer::SETTINGS, $this->settings);
		if ($this->isColumnModified(ApplicationsPeer::VIEWS)) $criteria->add(ApplicationsPeer::VIEWS, $this->views);
		if ($this->isColumnModified(ApplicationsPeer::VERSION)) $criteria->add(ApplicationsPeer::VERSION, $this->version);
		if ($this->isColumnModified(ApplicationsPeer::HEIGHT)) $criteria->add(ApplicationsPeer::HEIGHT, $this->height);
		if ($this->isColumnModified(ApplicationsPeer::SCROLLING)) $criteria->add(ApplicationsPeer::SCROLLING, $this->scrolling);
		if ($this->isColumnModified(ApplicationsPeer::MODIFIED)) $criteria->add(ApplicationsPeer::MODIFIED, $this->modified);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ApplicationsPeer::DATABASE_NAME);

		$criteria->add(ApplicationsPeer::ID, $this->id);

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

			foreach($this->getApplicationSettingss() as $relObj) {
				$copyObj->addApplicationSettings($relObj->copy($deepCopy));
			}

			foreach($this->getMemberApplicationss() as $relObj) {
				$copyObj->addMemberApplications($relObj->copy($deepCopy));
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
			self::$peer = new ApplicationsPeer();
		}
		return self::$peer;
	}

	
	public function initApplicationSettingss()
	{
		if ($this->collApplicationSettingss === null) {
			$this->collApplicationSettingss = array();
		}
	}

	
	public function getApplicationSettingss($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collApplicationSettingss === null) {
			if ($this->isNew()) {
			   $this->collApplicationSettingss = array();
			} else {

				$criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->getId());

				ApplicationSettingsPeer::addSelectColumns($criteria);
				$this->collApplicationSettingss = ApplicationSettingsPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->getId());

				ApplicationSettingsPeer::addSelectColumns($criteria);
				if (!isset($this->lastApplicationSettingsCriteria) || !$this->lastApplicationSettingsCriteria->equals($criteria)) {
					$this->collApplicationSettingss = ApplicationSettingsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastApplicationSettingsCriteria = $criteria;
		return $this->collApplicationSettingss;
	}

	
	public function countApplicationSettingss($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->getId());

		return ApplicationSettingsPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addApplicationSettings(ApplicationSettings $l)
	{
		$this->collApplicationSettingss[] = $l;
		$l->setApplications($this);
	}


	
	public function getApplicationSettingssJoinMember($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collApplicationSettingss === null) {
			if ($this->isNew()) {
				$this->collApplicationSettingss = array();
			} else {

				$criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->getId());

				$this->collApplicationSettingss = ApplicationSettingsPeer::doSelectJoinMember($criteria, $con);
			}
		} else {
									
			$criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->getId());

			if (!isset($this->lastApplicationSettingsCriteria) || !$this->lastApplicationSettingsCriteria->equals($criteria)) {
				$this->collApplicationSettingss = ApplicationSettingsPeer::doSelectJoinMember($criteria, $con);
			}
		}
		$this->lastApplicationSettingsCriteria = $criteria;

		return $this->collApplicationSettingss;
	}

	
	public function initMemberApplicationss()
	{
		if ($this->collMemberApplicationss === null) {
			$this->collMemberApplicationss = array();
		}
	}

	
	public function getMemberApplicationss($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberApplicationss === null) {
			if ($this->isNew()) {
			   $this->collMemberApplicationss = array();
			} else {

				$criteria->add(MemberApplicationsPeer::APPLICATIONS_ID, $this->getId());

				MemberApplicationsPeer::addSelectColumns($criteria);
				$this->collMemberApplicationss = MemberApplicationsPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(MemberApplicationsPeer::APPLICATIONS_ID, $this->getId());

				MemberApplicationsPeer::addSelectColumns($criteria);
				if (!isset($this->lastMemberApplicationsCriteria) || !$this->lastMemberApplicationsCriteria->equals($criteria)) {
					$this->collMemberApplicationss = MemberApplicationsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMemberApplicationsCriteria = $criteria;
		return $this->collMemberApplicationss;
	}

	
	public function countMemberApplicationss($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MemberApplicationsPeer::APPLICATIONS_ID, $this->getId());

		return MemberApplicationsPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addMemberApplications(MemberApplications $l)
	{
		$this->collMemberApplicationss[] = $l;
		$l->setApplications($this);
	}


	
	public function getMemberApplicationssJoinMember($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberApplicationss === null) {
			if ($this->isNew()) {
				$this->collMemberApplicationss = array();
			} else {

				$criteria->add(MemberApplicationsPeer::APPLICATIONS_ID, $this->getId());

				$this->collMemberApplicationss = MemberApplicationsPeer::doSelectJoinMember($criteria, $con);
			}
		} else {
									
			$criteria->add(MemberApplicationsPeer::APPLICATIONS_ID, $this->getId());

			if (!isset($this->lastMemberApplicationsCriteria) || !$this->lastMemberApplicationsCriteria->equals($criteria)) {
				$this->collMemberApplicationss = MemberApplicationsPeer::doSelectJoinMember($criteria, $con);
			}
		}
		$this->lastMemberApplicationsCriteria = $criteria;

		return $this->collMemberApplicationss;
	}

} 