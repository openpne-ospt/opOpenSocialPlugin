<?php


abstract class BaseApplicationSettingsPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'application_settings';

	
	const CLASS_DEFAULT = 'plugins.opOpenSocialPlugin.lib.model.ApplicationSettings';

	
	const NUM_COLUMNS = 5;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const APPLICATIONS_ID = 'application_settings.APPLICATIONS_ID';

	
	const MEMBER_ID = 'application_settings.MEMBER_ID';

	
	const NAME = 'application_settings.NAME';

	
	const VALUE = 'application_settings.VALUE';

	
	const ID = 'application_settings.ID';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('ApplicationsId', 'MemberId', 'Name', 'Value', 'Id', ),
		BasePeer::TYPE_COLNAME => array (ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationSettingsPeer::MEMBER_ID, ApplicationSettingsPeer::NAME, ApplicationSettingsPeer::VALUE, ApplicationSettingsPeer::ID, ),
		BasePeer::TYPE_FIELDNAME => array ('applications_id', 'member_id', 'name', 'value', 'id', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('ApplicationsId' => 0, 'MemberId' => 1, 'Name' => 2, 'Value' => 3, 'Id' => 4, ),
		BasePeer::TYPE_COLNAME => array (ApplicationSettingsPeer::APPLICATIONS_ID => 0, ApplicationSettingsPeer::MEMBER_ID => 1, ApplicationSettingsPeer::NAME => 2, ApplicationSettingsPeer::VALUE => 3, ApplicationSettingsPeer::ID => 4, ),
		BasePeer::TYPE_FIELDNAME => array ('applications_id' => 0, 'member_id' => 1, 'name' => 2, 'value' => 3, 'id' => 4, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationSettingsMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = ApplicationSettingsPeer::getTableMap();
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
		return str_replace(ApplicationSettingsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(ApplicationSettingsPeer::APPLICATIONS_ID);

		$criteria->addSelectColumn(ApplicationSettingsPeer::MEMBER_ID);

		$criteria->addSelectColumn(ApplicationSettingsPeer::NAME);

		$criteria->addSelectColumn(ApplicationSettingsPeer::VALUE);

		$criteria->addSelectColumn(ApplicationSettingsPeer::ID);

	}

	const COUNT = 'COUNT(application_settings.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT application_settings.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
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
		$objects = ApplicationSettingsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return ApplicationSettingsPeer::populateObjects(ApplicationSettingsPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			ApplicationSettingsPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = ApplicationSettingsPeer::getOMClass();
		$cls = sfPropel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinApplications(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doCountJoinMember(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinApplications(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		ApplicationSettingsPeer::addSelectColumns($c);
		$startcol = (ApplicationSettingsPeer::NUM_COLUMNS - ApplicationSettingsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		ApplicationsPeer::addSelectColumns($c);

		$c->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationSettingsPeer::getOMClass();

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
										$temp_obj2->addApplicationSettings($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initApplicationSettingss();
				$obj2->addApplicationSettings($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doSelectJoinMember(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		ApplicationSettingsPeer::addSelectColumns($c);
		$startcol = (ApplicationSettingsPeer::NUM_COLUMNS - ApplicationSettingsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		MemberPeer::addSelectColumns($c);

		$c->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationSettingsPeer::getOMClass();

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
										$temp_obj2->addApplicationSettings($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initApplicationSettingss();
				$obj2->addApplicationSettings($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$criteria->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
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

		ApplicationSettingsPeer::addSelectColumns($c);
		$startcol2 = (ApplicationSettingsPeer::NUM_COLUMNS - ApplicationSettingsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		ApplicationsPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + ApplicationsPeer::NUM_COLUMNS;

		MemberPeer::addSelectColumns($c);
		$startcol4 = $startcol3 + MemberPeer::NUM_COLUMNS;

		$c->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$c->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationSettingsPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = ApplicationsPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getApplications(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addApplicationSettings($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initApplicationSettingss();
				$obj2->addApplicationSettings($obj1);
			}


					
			$omClass = MemberPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj3 = new $cls();
			$obj3->hydrate($rs, $startcol3);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj3 = $temp_obj1->getMember(); 				if ($temp_obj3->getPrimaryKey() === $obj3->getPrimaryKey()) {
					$newObject = false;
					$temp_obj3->addApplicationSettings($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj3->initApplicationSettingss();
				$obj3->addApplicationSettings($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAllExceptApplications(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doCountJoinAllExceptMember(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationSettingsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);

		$rs = ApplicationSettingsPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAllExceptApplications(Criteria $c, $con = null)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		ApplicationSettingsPeer::addSelectColumns($c);
		$startcol2 = (ApplicationSettingsPeer::NUM_COLUMNS - ApplicationSettingsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		MemberPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + MemberPeer::NUM_COLUMNS;

		$c->addJoin(ApplicationSettingsPeer::MEMBER_ID, MemberPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationSettingsPeer::getOMClass();

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
					$temp_obj2->addApplicationSettings($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initApplicationSettingss();
				$obj2->addApplicationSettings($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doSelectJoinAllExceptMember(Criteria $c, $con = null)
	{
		$c = clone $c;

								if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		ApplicationSettingsPeer::addSelectColumns($c);
		$startcol2 = (ApplicationSettingsPeer::NUM_COLUMNS - ApplicationSettingsPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		ApplicationsPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + ApplicationsPeer::NUM_COLUMNS;

		$c->addJoin(ApplicationSettingsPeer::APPLICATIONS_ID, ApplicationsPeer::ID);


		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationSettingsPeer::getOMClass();

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
					$temp_obj2->addApplicationSettings($obj1);
					break;
				}
			}

			if ($newObject) {
				$obj2->initApplicationSettingss();
				$obj2->addApplicationSettings($obj1);
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
		return ApplicationSettingsPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(ApplicationSettingsPeer::ID); 

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
			$comparison = $criteria->getComparison(ApplicationSettingsPeer::ID);
			$selectCriteria->add(ApplicationSettingsPeer::ID, $criteria->remove(ApplicationSettingsPeer::ID), $comparison);

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
			$affectedRows += BasePeer::doDeleteAll(ApplicationSettingsPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(ApplicationSettingsPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof ApplicationSettings) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ApplicationSettingsPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(ApplicationSettings $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ApplicationSettingsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ApplicationSettingsPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(ApplicationSettingsPeer::DATABASE_NAME, ApplicationSettingsPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = ApplicationSettingsPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(ApplicationSettingsPeer::DATABASE_NAME);

		$criteria->add(ApplicationSettingsPeer::ID, $pk);


		$v = ApplicationSettingsPeer::doSelect($criteria, $con);

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
			$criteria->add(ApplicationSettingsPeer::ID, $pks, Criteria::IN);
			$objs = ApplicationSettingsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseApplicationSettingsPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationSettingsMapBuilder');
}
