<?php


abstract class BaseApplicationSetting extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $application_id;


	
	protected $member_id;


	
	protected $name;


	
	protected $value;

	
	protected $aApplication;

	
	protected $aMember;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getApplicationId()
	{

		return $this->application_id;
	}

	
	public function getMemberId()
	{

		return $this->member_id;
	}

	
	public function getName()
	{

		return $this->name;
	}

	
	public function getValue()
	{

		return $this->value;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ApplicationSettingPeer::ID;
		}

	} 
	
	public function setApplicationId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->application_id !== $v) {
			$this->application_id = $v;
			$this->modifiedColumns[] = ApplicationSettingPeer::APPLICATION_ID;
		}

		if ($this->aApplication !== null && $this->aApplication->getId() !== $v) {
			$this->aApplication = null;
		}

	} 
	
	public function setMemberId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id !== $v) {
			$this->member_id = $v;
			$this->modifiedColumns[] = ApplicationSettingPeer::MEMBER_ID;
		}

		if ($this->aMember !== null && $this->aMember->getId() !== $v) {
			$this->aMember = null;
		}

	} 
	
	public function setName($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = ApplicationSettingPeer::NAME;
		}

	} 
	
	public function setValue($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value !== $v) {
			$this->value = $v;
			$this->modifiedColumns[] = ApplicationSettingPeer::VALUE;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->application_id = $rs->getInt($startcol + 1);

			$this->member_id = $rs->getInt($startcol + 2);

			$this->name = $rs->getString($startcol + 3);

			$this->value = $rs->getString($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating ApplicationSetting object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationSettingPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ApplicationSettingPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ApplicationSettingPeer::DATABASE_NAME);
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

			if ($this->aMember !== null) {
				if ($this->aMember->isModified()) {
					$affectedRows += $this->aMember->save($con);
				}
				$this->setMember($this->aMember);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ApplicationSettingPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ApplicationSettingPeer::doUpdate($this, $con);
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

			if ($this->aMember !== null) {
				if (!$this->aMember->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMember->getValidationFailures());
				}
			}


			if (($retval = ApplicationSettingPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationSettingPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getApplicationId();
				break;
			case 2:
				return $this->getMemberId();
				break;
			case 3:
				return $this->getName();
				break;
			case 4:
				return $this->getValue();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationSettingPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getApplicationId(),
			$keys[2] => $this->getMemberId(),
			$keys[3] => $this->getName(),
			$keys[4] => $this->getValue(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationSettingPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setApplicationId($value);
				break;
			case 2:
				$this->setMemberId($value);
				break;
			case 3:
				$this->setName($value);
				break;
			case 4:
				$this->setValue($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationSettingPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setApplicationId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMemberId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setName($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setValue($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationSettingPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationSettingPeer::ID)) $criteria->add(ApplicationSettingPeer::ID, $this->id);
		if ($this->isColumnModified(ApplicationSettingPeer::APPLICATION_ID)) $criteria->add(ApplicationSettingPeer::APPLICATION_ID, $this->application_id);
		if ($this->isColumnModified(ApplicationSettingPeer::MEMBER_ID)) $criteria->add(ApplicationSettingPeer::MEMBER_ID, $this->member_id);
		if ($this->isColumnModified(ApplicationSettingPeer::NAME)) $criteria->add(ApplicationSettingPeer::NAME, $this->name);
		if ($this->isColumnModified(ApplicationSettingPeer::VALUE)) $criteria->add(ApplicationSettingPeer::VALUE, $this->value);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ApplicationSettingPeer::DATABASE_NAME);

		$criteria->add(ApplicationSettingPeer::ID, $this->id);

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

		$copyObj->setApplicationId($this->application_id);

		$copyObj->setMemberId($this->member_id);

		$copyObj->setName($this->name);

		$copyObj->setValue($this->value);


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
			self::$peer = new ApplicationSettingPeer();
		}
		return self::$peer;
	}

	
	public function setApplication($v)
	{


		if ($v === null) {
			$this->setApplicationId(NULL);
		} else {
			$this->setApplicationId($v->getId());
		}


		$this->aApplication = $v;
	}


	
	public function getApplication($con = null)
	{
		if ($this->aApplication === null && ($this->application_id !== null)) {
						$this->aApplication = ApplicationPeer::retrieveByPK($this->application_id, $con);

			
		}
		return $this->aApplication;
	}

	
	public function setMember($v)
	{


		if ($v === null) {
			$this->setMemberId(NULL);
		} else {
			$this->setMemberId($v->getId());
		}


		$this->aMember = $v;
	}


	
	public function getMember($con = null)
	{
		if ($this->aMember === null && ($this->member_id !== null)) {
						$this->aMember = MemberPeer::retrieveByPK($this->member_id, $con);

			
		}
		return $this->aMember;
	}

} 