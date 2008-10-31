<?php


abstract class BaseApplicationSettings extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $applications_id;


	
	protected $member_id;


	
	protected $name;


	
	protected $value;


	
	protected $id;

	
	protected $aApplications;

	
	protected $aMember;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getApplicationsId()
	{

		return $this->applications_id;
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

	
	public function getId()
	{

		return $this->id;
	}

	
	public function setApplicationsId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->applications_id !== $v) {
			$this->applications_id = $v;
			$this->modifiedColumns[] = ApplicationSettingsPeer::APPLICATIONS_ID;
		}

		if ($this->aApplications !== null && $this->aApplications->getId() !== $v) {
			$this->aApplications = null;
		}

	} 
	
	public function setMemberId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id !== $v) {
			$this->member_id = $v;
			$this->modifiedColumns[] = ApplicationSettingsPeer::MEMBER_ID;
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
			$this->modifiedColumns[] = ApplicationSettingsPeer::NAME;
		}

	} 
	
	public function setValue($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value !== $v) {
			$this->value = $v;
			$this->modifiedColumns[] = ApplicationSettingsPeer::VALUE;
		}

	} 
	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ApplicationSettingsPeer::ID;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->applications_id = $rs->getInt($startcol + 0);

			$this->member_id = $rs->getInt($startcol + 1);

			$this->name = $rs->getString($startcol + 2);

			$this->value = $rs->getString($startcol + 3);

			$this->id = $rs->getInt($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating ApplicationSettings object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ApplicationSettingsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ApplicationSettingsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ApplicationSettingsPeer::DATABASE_NAME);
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


												
			if ($this->aApplications !== null) {
				if ($this->aApplications->isModified()) {
					$affectedRows += $this->aApplications->save($con);
				}
				$this->setApplications($this->aApplications);
			}

			if ($this->aMember !== null) {
				if ($this->aMember->isModified()) {
					$affectedRows += $this->aMember->save($con);
				}
				$this->setMember($this->aMember);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ApplicationSettingsPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ApplicationSettingsPeer::doUpdate($this, $con);
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


												
			if ($this->aApplications !== null) {
				if (!$this->aApplications->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aApplications->getValidationFailures());
				}
			}

			if ($this->aMember !== null) {
				if (!$this->aMember->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMember->getValidationFailures());
				}
			}


			if (($retval = ApplicationSettingsPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationSettingsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getApplicationsId();
				break;
			case 1:
				return $this->getMemberId();
				break;
			case 2:
				return $this->getName();
				break;
			case 3:
				return $this->getValue();
				break;
			case 4:
				return $this->getId();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationSettingsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getApplicationsId(),
			$keys[1] => $this->getMemberId(),
			$keys[2] => $this->getName(),
			$keys[3] => $this->getValue(),
			$keys[4] => $this->getId(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ApplicationSettingsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setApplicationsId($value);
				break;
			case 1:
				$this->setMemberId($value);
				break;
			case 2:
				$this->setName($value);
				break;
			case 3:
				$this->setValue($value);
				break;
			case 4:
				$this->setId($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ApplicationSettingsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setApplicationsId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMemberId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setValue($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setId($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ApplicationSettingsPeer::DATABASE_NAME);

		if ($this->isColumnModified(ApplicationSettingsPeer::APPLICATIONS_ID)) $criteria->add(ApplicationSettingsPeer::APPLICATIONS_ID, $this->applications_id);
		if ($this->isColumnModified(ApplicationSettingsPeer::MEMBER_ID)) $criteria->add(ApplicationSettingsPeer::MEMBER_ID, $this->member_id);
		if ($this->isColumnModified(ApplicationSettingsPeer::NAME)) $criteria->add(ApplicationSettingsPeer::NAME, $this->name);
		if ($this->isColumnModified(ApplicationSettingsPeer::VALUE)) $criteria->add(ApplicationSettingsPeer::VALUE, $this->value);
		if ($this->isColumnModified(ApplicationSettingsPeer::ID)) $criteria->add(ApplicationSettingsPeer::ID, $this->id);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ApplicationSettingsPeer::DATABASE_NAME);

		$criteria->add(ApplicationSettingsPeer::ID, $this->id);

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

		$copyObj->setApplicationsId($this->applications_id);

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
			self::$peer = new ApplicationSettingsPeer();
		}
		return self::$peer;
	}

	
	public function setApplications($v)
	{


		if ($v === null) {
			$this->setApplicationsId(NULL);
		} else {
			$this->setApplicationsId($v->getId());
		}


		$this->aApplications = $v;
	}


	
	public function getApplications($con = null)
	{
		if ($this->aApplications === null && ($this->applications_id !== null)) {
						$this->aApplications = ApplicationsPeer::retrieveByPK($this->applications_id, $con);

			
		}
		return $this->aApplications;
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