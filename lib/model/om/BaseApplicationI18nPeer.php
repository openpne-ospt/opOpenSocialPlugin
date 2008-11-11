<?php


abstract class BaseApplicationI18nPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'application_i18n';

	
	const CLASS_DEFAULT = 'plugins.opOpenSocialPlugin.lib.model.ApplicationI18n';

	
	const NUM_COLUMNS = 13;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const TITLE = 'application_i18n.TITLE';

	
	const DIRECTORY_TITLE = 'application_i18n.DIRECTORY_TITLE';

	
	const SCREENSHOT = 'application_i18n.SCREENSHOT';

	
	const THUMBNAIL = 'application_i18n.THUMBNAIL';

	
	const AUTHOR = 'application_i18n.AUTHOR';

	
	const AUTHOR_EMAIL = 'application_i18n.AUTHOR_EMAIL';

	
	const DESCRIPTION = 'application_i18n.DESCRIPTION';

	
	const SETTINGS = 'application_i18n.SETTINGS';

	
	const VIEWS = 'application_i18n.VIEWS';

	
	const VERSION = 'application_i18n.VERSION';

	
	const UPDATED_AT = 'application_i18n.UPDATED_AT';

	
	const ID = 'application_i18n.ID';

	
	const CULTURE = 'application_i18n.CULTURE';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Title', 'DirectoryTitle', 'Screenshot', 'Thumbnail', 'Author', 'AuthorEmail', 'Description', 'Settings', 'Views', 'Version', 'UpdatedAt', 'Id', 'Culture', ),
		BasePeer::TYPE_COLNAME => array (ApplicationI18nPeer::TITLE, ApplicationI18nPeer::DIRECTORY_TITLE, ApplicationI18nPeer::SCREENSHOT, ApplicationI18nPeer::THUMBNAIL, ApplicationI18nPeer::AUTHOR, ApplicationI18nPeer::AUTHOR_EMAIL, ApplicationI18nPeer::DESCRIPTION, ApplicationI18nPeer::SETTINGS, ApplicationI18nPeer::VIEWS, ApplicationI18nPeer::VERSION, ApplicationI18nPeer::UPDATED_AT, ApplicationI18nPeer::ID, ApplicationI18nPeer::CULTURE, ),
		BasePeer::TYPE_FIELDNAME => array ('title', 'directory_title', 'screenshot', 'thumbnail', 'author', 'author_email', 'description', 'settings', 'views', 'version', 'updated_at', 'id', 'culture', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Title' => 0, 'DirectoryTitle' => 1, 'Screenshot' => 2, 'Thumbnail' => 3, 'Author' => 4, 'AuthorEmail' => 5, 'Description' => 6, 'Settings' => 7, 'Views' => 8, 'Version' => 9, 'UpdatedAt' => 10, 'Id' => 11, 'Culture' => 12, ),
		BasePeer::TYPE_COLNAME => array (ApplicationI18nPeer::TITLE => 0, ApplicationI18nPeer::DIRECTORY_TITLE => 1, ApplicationI18nPeer::SCREENSHOT => 2, ApplicationI18nPeer::THUMBNAIL => 3, ApplicationI18nPeer::AUTHOR => 4, ApplicationI18nPeer::AUTHOR_EMAIL => 5, ApplicationI18nPeer::DESCRIPTION => 6, ApplicationI18nPeer::SETTINGS => 7, ApplicationI18nPeer::VIEWS => 8, ApplicationI18nPeer::VERSION => 9, ApplicationI18nPeer::UPDATED_AT => 10, ApplicationI18nPeer::ID => 11, ApplicationI18nPeer::CULTURE => 12, ),
		BasePeer::TYPE_FIELDNAME => array ('title' => 0, 'directory_title' => 1, 'screenshot' => 2, 'thumbnail' => 3, 'author' => 4, 'author_email' => 5, 'description' => 6, 'settings' => 7, 'views' => 8, 'version' => 9, 'updated_at' => 10, 'id' => 11, 'culture' => 12, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationI18nMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = ApplicationI18nPeer::getTableMap();
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
		return str_replace(ApplicationI18nPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(ApplicationI18nPeer::TITLE);

		$criteria->addSelectColumn(ApplicationI18nPeer::DIRECTORY_TITLE);

		$criteria->addSelectColumn(ApplicationI18nPeer::SCREENSHOT);

		$criteria->addSelectColumn(ApplicationI18nPeer::THUMBNAIL);

		$criteria->addSelectColumn(ApplicationI18nPeer::AUTHOR);

		$criteria->addSelectColumn(ApplicationI18nPeer::AUTHOR_EMAIL);

		$criteria->addSelectColumn(ApplicationI18nPeer::DESCRIPTION);

		$criteria->addSelectColumn(ApplicationI18nPeer::SETTINGS);

		$criteria->addSelectColumn(ApplicationI18nPeer::VIEWS);

		$criteria->addSelectColumn(ApplicationI18nPeer::VERSION);

		$criteria->addSelectColumn(ApplicationI18nPeer::UPDATED_AT);

		$criteria->addSelectColumn(ApplicationI18nPeer::ID);

		$criteria->addSelectColumn(ApplicationI18nPeer::CULTURE);

	}

	const COUNT = 'COUNT(application_i18n.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT application_i18n.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = ApplicationI18nPeer::doSelectRS($criteria, $con);
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
		$objects = ApplicationI18nPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return ApplicationI18nPeer::populateObjects(ApplicationI18nPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			ApplicationI18nPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = ApplicationI18nPeer::getOMClass();
		$cls = sfPropel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinApplication(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationI18nPeer::ID, ApplicationPeer::ID);

		$rs = ApplicationI18nPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinApplication(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		ApplicationI18nPeer::addSelectColumns($c);
		$startcol = (ApplicationI18nPeer::NUM_COLUMNS - ApplicationI18nPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		ApplicationPeer::addSelectColumns($c);

		$c->addJoin(ApplicationI18nPeer::ID, ApplicationPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationI18nPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = ApplicationPeer::getOMClass();

			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getApplication(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addApplicationI18n($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initApplicationI18ns();
				$obj2->addApplicationI18n($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationI18nPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(ApplicationI18nPeer::ID, ApplicationPeer::ID);

		$rs = ApplicationI18nPeer::doSelectRS($criteria, $con);
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

		ApplicationI18nPeer::addSelectColumns($c);
		$startcol2 = (ApplicationI18nPeer::NUM_COLUMNS - ApplicationI18nPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		ApplicationPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + ApplicationPeer::NUM_COLUMNS;

		$c->addJoin(ApplicationI18nPeer::ID, ApplicationPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = ApplicationI18nPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = ApplicationPeer::getOMClass();


			$cls = sfPropel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getApplication(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addApplicationI18n($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initApplicationI18ns();
				$obj2->addApplicationI18n($obj1);
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
		return ApplicationI18nPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}


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
			$comparison = $criteria->getComparison(ApplicationI18nPeer::ID);
			$selectCriteria->add(ApplicationI18nPeer::ID, $criteria->remove(ApplicationI18nPeer::ID), $comparison);

			$comparison = $criteria->getComparison(ApplicationI18nPeer::CULTURE);
			$selectCriteria->add(ApplicationI18nPeer::CULTURE, $criteria->remove(ApplicationI18nPeer::CULTURE), $comparison);

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
			$affectedRows += BasePeer::doDeleteAll(ApplicationI18nPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(ApplicationI18nPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof ApplicationI18n) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
												if(count($values) == count($values, COUNT_RECURSIVE))
			{
								$values = array($values);
			}
			$vals = array();
			foreach($values as $value)
			{

				$vals[0][] = $value[0];
				$vals[1][] = $value[1];
			}

			$criteria->add(ApplicationI18nPeer::ID, $vals[0], Criteria::IN);
			$criteria->add(ApplicationI18nPeer::CULTURE, $vals[1], Criteria::IN);
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

	
	public static function doValidate(ApplicationI18n $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ApplicationI18nPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ApplicationI18nPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(ApplicationI18nPeer::DATABASE_NAME, ApplicationI18nPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = ApplicationI18nPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK( $id, $culture, $con = null) {
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$criteria = new Criteria();
		$criteria->add(ApplicationI18nPeer::ID, $id);
		$criteria->add(ApplicationI18nPeer::CULTURE, $culture);
		$v = ApplicationI18nPeer::doSelect($criteria, $con);

		return !empty($v) ? $v[0] : null;
	}
} 
if (Propel::isInit()) {
			try {
		BaseApplicationI18nPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationI18nMapBuilder');
}
