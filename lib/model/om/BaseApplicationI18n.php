<?php


abstract class BaseApplicationI18n extends BaseObject  implements Persistent {


	
	protected static $peer;


	
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


	
	protected $updated_at;


	
	protected $id;


	
	protected $culture;

	
	protected $aApplication;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
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

	
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
						$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getCulture()
	{

		return $this->culture;
	}

	
	public function setTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->title !== $v) {
			$this->title = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::TITLE;
		}

	} 
	
	public function setDirectoryTitle($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->directory_title !== $v) {
			$this->directory_title = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::DIRECTORY_TITLE;
		}

	} 
	
	public function setScreenshot($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->screenshot !== $v) {
			$this->screenshot = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::SCREENSHOT;
		}

	} 
	
	public function setThumbnail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->thumbnail !== $v) {
			$this->thumbnail = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::THUMBNAIL;
		}

	} 
	
	public function setAuthor($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author !== $v) {
			$this->author = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::AUTHOR;
		}

	} 
	
	public function setAuthorEmail($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->author_email !== $v) {
			$this->author_email = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::AUTHOR_EMAIL;
		}

	} 
	
	public function setDescription($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::DESCRIPTION;
		}

	} 
	
	public function setSettings($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->settings !== $v) {
			$this->settings = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::SETTINGS;
		}

	} 
	
	public function setViews($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->views !== $v) {
			$this->views = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::VIEWS;
		}

	} 
	
	public function setVersion($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->version !== $v) {
			$this->version = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::VERSION;
		}

	} 
	
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = ApplicationI18nPeer::UPDATED_AT;
		}

	} 
	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::ID;
		}

		if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
			$this->aApplication = null;
		}

	} 
	
	public function setCulture($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = ApplicationI18nPeer::CULTURE;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->title = $rs->getString($startcol + 0);

			$this->directory_title = $rs->getString($startcol + 1);

			$this->screenshot = $rs->getString($startcol + 2);

			$this->thumbnail = $rs->getString($startcol + 3);

			$this->author = $rs->getString($startcol + 4);

			$this->author_email = $rs->getString($startcol + 5);

			$this->description = $rs->getString($startcol + 6);

			$this->settings = $rs->getString($startcol + 7);

			$this->views = $rs->getString($startcol + 8);

			$this->version = $rs->getString($startcol + 9);

			$this->updated_at = $rs->getTimestamp($startcol + 10, null);

			$this->id = $rs->getInt($startcol + 11);

			$this->culture = $rs->getString($startcol + 12);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 13; 
		} catch (Exception $e) {
			throw new PropelException("Error populating ApplicationI18n object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationI18nPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ApplicationI18nPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
    if ($this->isModified() && !$this->isColumnModified(ApplicationI18nPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationI18nPeer::DATABASE_NAME);
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


												
			if ($this->aApplication !== null) {
				if ($this->aApplication->isModified() || ($this->aApplication->getCulture() && $this->aApplication->getCurrentApplicationI18n()->isModified())) {
					$affectedRows += $this->aApplication->save($con);
				}
				$this->setApplication($this->aApplication);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ApplicationI18nPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setNew(false);
				} else {
					$affectedRows += ApplicationI18nPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

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


												
			if ($this->aApplication !== null) {
				if (!$this->aApplication->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aApplication->getValidationFailures());
				}
			}


			if (($retval = ApplicationI18nPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationI18nPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getTitle();
				break;
			case 1:
				return $this->getDirectoryTitle();
				break;
			case 2:
				return $this->getScreenshot();
				break;
			case 3:
				return $this->getThumbnail();
				break;
			case 4:
				return $this->getAuthor();
				break;
			case 5:
				return $this->getAuthorEmail();
				break;
			case 6:
				return $this->getDescription();
				break;
			case 7:
				return $this->getSettings();
				break;
			case 8:
				return $this->getViews();
				break;
			case 9:
				return $this->getVersion();
				break;
			case 10:
				return $this->getUpdatedAt();
				break;
			case 11:
				return $this->getId();
				break;
			case 12:
				return $this->getCulture();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationI18nPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getTitle(),
			$keys[1] => $this->getDirectoryTitle(),
			$keys[2] => $this->getScreenshot(),
			$keys[3] => $this->getThumbnail(),
			$keys[4] => $this->getAuthor(),
			$keys[5] => $this->getAuthorEmail(),
			$keys[6] => $this->getDescription(),
			$keys[7] => $this->getSettings(),
			$keys[8] => $this->getViews(),
			$keys[9] => $this->getVersion(),
			$keys[10] => $this->getUpdatedAt(),
			$keys[11] => $this->getId(),
			$keys[12] => $this->getCulture(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationI18nPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setTitle($value);
				break;
			case 1:
				$this->setDirectoryTitle($value);
				break;
			case 2:
				$this->setScreenshot($value);
				break;
			case 3:
				$this->setThumbnail($value);
				break;
			case 4:
				$this->setAuthor($value);
				break;
			case 5:
				$this->setAuthorEmail($value);
				break;
			case 6:
				$this->setDescription($value);
				break;
			case 7:
				$this->setSettings($value);
				break;
			case 8:
				$this->setViews($value);
				break;
			case 9:
				$this->setVersion($value);
				break;
			case 10:
				$this->setUpdatedAt($value);
				break;
			case 11:
				$this->setId($value);
				break;
			case 12:
				$this->setCulture($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationI18nPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setTitle($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDirectoryTitle($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setScreenshot($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setThumbnail($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setAuthor($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setAuthorEmail($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDescription($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setSettings($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setViews($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setVersion($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setUpdatedAt($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setId($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCulture($arr[$keys[12]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationI18nPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationI18nPeer::TITLE)) $criteria->add(ApplicationI18nPeer::TITLE, $this->title);
		if ($this->isColumnModified(ApplicationI18nPeer::DIRECTORY_TITLE)) $criteria->add(ApplicationI18nPeer::DIRECTORY_TITLE, $this->directory_title);
		if ($this->isColumnModified(ApplicationI18nPeer::SCREENSHOT)) $criteria->add(ApplicationI18nPeer::SCREENSHOT, $this->screenshot);
		if ($this->isColumnModified(ApplicationI18nPeer::THUMBNAIL)) $criteria->add(ApplicationI18nPeer::THUMBNAIL, $this->thumbnail);
		if ($this->isColumnModified(ApplicationI18nPeer::AUTHOR)) $criteria->add(ApplicationI18nPeer::AUTHOR, $this->author);
		if ($this->isColumnModified(ApplicationI18nPeer::AUTHOR_EMAIL)) $criteria->add(ApplicationI18nPeer::AUTHOR_EMAIL, $this->author_email);
		if ($this->isColumnModified(ApplicationI18nPeer::DESCRIPTION)) $criteria->add(ApplicationI18nPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(ApplicationI18nPeer::SETTINGS)) $criteria->add(ApplicationI18nPeer::SETTINGS, $this->settings);
		if ($this->isColumnModified(ApplicationI18nPeer::VIEWS)) $criteria->add(ApplicationI18nPeer::VIEWS, $this->views);
		if ($this->isColumnModified(ApplicationI18nPeer::VERSION)) $criteria->add(ApplicationI18nPeer::VERSION, $this->version);
		if ($this->isColumnModified(ApplicationI18nPeer::UPDATED_AT)) $criteria->add(ApplicationI18nPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(ApplicationI18nPeer::ID)) $criteria->add(ApplicationI18nPeer::ID, $this->id);
		if ($this->isColumnModified(ApplicationI18nPeer::CULTURE)) $criteria->add(ApplicationI18nPeer::CULTURE, $this->culture);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ApplicationI18nPeer::DATABASE_NAME);

		$criteria->add(ApplicationI18nPeer::ID, $this->id);
		$criteria->add(ApplicationI18nPeer::CULTURE, $this->culture);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		$pks = array();

		$pks[0] = $this->getId();

		$pks[1] = $this->getCulture();

		return $pks;
	}

	
	public function setPrimaryKey($keys)
	{

		$this->setId($keys[0]);

		$this->setCulture($keys[1]);

	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

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

		$copyObj->setUpdatedAt($this->updated_at);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
		$copyObj->setCulture(NULL); 
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
			self::$peer = new ApplicationI18nPeer();
		}
		return self::$peer;
	}

	
	public function setApplication($v)
	{


		if ($v === null) {
			$this->setId(NULL);
		} else {
			$this->setId($v->getId());
		}


		$this->aApplication = $v;
	}


	
	public function getApplication($con = null)
	{
		if ($this->aApplication === null && ($this->id !== null)) {
						$this->aApplication = ApplicationPeer::retrieveByPK($this->id, $con);

			
		}
		return $this->aApplication;
	}

} 