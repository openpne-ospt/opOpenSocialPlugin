<?php


abstract class BaseMemberApplicationsPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'member_applications';

	
	const CLASS_DEFAULT = 'plugins.opOpenSocialPlugin.lib.model.MemberApplications';

	
	const NUM_COLUMNS = 3;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'member_applications.ID';

	
	const MEMBER_ID = 'member_applications.MEMBER_ID';

	
	const APPLICATIONS_ID = 'member_applications.APPLICATIONS_ID';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'MemberId', 'ApplicationsId', ),
		BasePeer::TYPE_COLNAME => array (MemberApplicationsPeer::ID, MemberApplicationsPeer::MEMBER_ID, MemberApplicationsPeer::APPLICATIONS_ID, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'member_id', 'applications_id', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'MemberId' => 1, 'ApplicationsId' => 2, ),
		BasePeer::TYPE_COLNAME => array (MemberApplicationsPeer::ID => 0, MemberApplicationsPeer::MEMBER_ID => 1, MemberApplicationsPeer::APPLICATIONS_ID => 2, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'member_id' => 1, 'applications_id' => 2, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.MemberApplicationsMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = MemberApplicationsPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(MemberApplicationsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(MemberApplicationsPeer::ID);

		$criteria->addSelectColumn(MemberApplicationsPeer::MEMBER_ID);

		$criteria->addSelectColumn(MemberApplicationsPeer::APPLICATIONS_ID);

	}

	const COUNT = 'COUNT(member_applications.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT member_applications.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}
	
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = MemberApplicationsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return MemberApplicationsPeer::populateObjects(MemberApplicationsPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			MemberApplicationsPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = MemberApplicationsPeer::getOMClass();
		$cls = sfPropel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinMember(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doCountJoinApplications(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinMember(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		MemberApplicationsPeer::addSelectColumns($c);
		$startcol = (MemberApplicationsPeer::NUM_COLUMNS - MemberApplicationsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		MemberPeer::addSelectColumns($c);

		$c->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = MemberApplicationsPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = MemberPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getMember(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addMemberApplications($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initMemberApplicationss();
				$obj2->addMemberApplications($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doSelectJoinApplications(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		MemberApplicationsPeer::addSelectColumns($c);
		$startcol = (MemberApplicationsPeer::NUM_COLUMNS - MemberApplicationsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		ApplicationsPeer::addSelectColumns($c);

		$c->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = MemberApplicationsPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = ApplicationsPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getApplications(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addMemberApplications($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initMemberApplicationss();
				$obj2->addMemberApplications($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);

		$criteria->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		MemberApplicationsPeer::addSelectColumns($c);
		$startcol2 = (MemberApplicationsPeer::NUM_COLUMNS - MemberApplicationsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		MemberPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + MemberPeer::NUM_COLUMNS;

		ApplicationsPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + ApplicationsPeer::NUM_COLUMNS;

		$c->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);

		$c->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = MemberApplicationsPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = MemberPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getMember(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addMemberApplications($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initMemberApplicationss();
				$obj2->addMemberApplications($obj1);
			}


					
			$omClass = ApplicationsPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj3 = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getApplications(); 				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addMemberApplications($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj3->initMemberApplicationss();
				$obj3->addMemberApplications($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAllExceptMember(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doCountJoinAllExceptApplications(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = MemberApplicationsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAllExceptMember(Criteria $c, $con = null)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		MemberApplicationsPeer::addSelectColumns($c);
		$startcol2 = (MemberApplicationsPeer::NUM_COLUMNS - MemberApplicationsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		ApplicationsPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + ApplicationsPeer::NUM_COLUMNS;

		$c->addJoin(MemberApplicationsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = MemberApplicationsPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = ApplicationsPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getApplications(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addMemberApplications($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initMemberApplicationss();
				$obj2->addMemberApplications($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doSelectJoinAllExceptApplications(Criteria $c, $con = null)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		MemberApplicationsPeer::addSelectColumns($c);
		$startcol2 = (MemberApplicationsPeer::NUM_COLUMNS - MemberApplicationsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		MemberPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + MemberPeer::NUM_COLUMNS;

		$c->addJoin(MemberApplicationsPeer::MEMBER_ID, MemberPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = MemberApplicationsPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = MemberPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj2  = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getMember(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addMemberApplications($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initMemberApplicationss();
				$obj2->addMemberApplications($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


  static public function getUniqueColumnNames()
  {
    return array();
  }
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return MemberApplicationsPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(MemberApplicationsPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(MemberApplicationsPeer::ID);
			$selectCriteria->add(MemberApplicationsPeer::ID, $criteria->remove(MemberApplicationsPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; 		try {
									$con->begin();
			$affectedRows += BasePeer::doDeleteAll(MemberApplicationsPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(MemberApplicationsPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof MemberApplications) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(MemberApplicationsPeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public static function doValidate(MemberApplications $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(MemberApplicationsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(MemberApplicationsPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(MemberApplicationsPeer::DATABASE_NAME, MemberApplicationsPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = MemberApplicationsPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(MemberApplicationsPeer::DATABASE_NAME);

		$criteria->add(MemberApplicationsPeer::ID, $pk);


		$v = MemberApplicationsPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(MemberApplicationsPeer::ID, $pks, Criteria::IN);
			$objs = MemberApplicationsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseMemberApplicationsPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.MemberApplicationsMapBuilder');
}
