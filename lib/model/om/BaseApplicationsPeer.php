<?php


abstract class BaseApplicationsPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'applications';

	
	const CLASS_DEFAULT = 'plugins.opOpenSocialPlugin.lib.model.Applications';

	
	const NUM_COLUMNS = 15;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'applications.ID';

	
	const URL = 'applications.URL';

	
	const TITLE = 'applications.TITLE';

	
	const DIRECTORY_TITLE = 'applications.DIRECTORY_TITLE';

	
	const SCREENSHOT = 'applications.SCREENSHOT';

	
	const THUMBNAIL = 'applications.THUMBNAIL';

	
	const AUTHOR = 'applications.AUTHOR';

	
	const AUTHOR_EMAIL = 'applications.AUTHOR_EMAIL';

	
	const DESCRIPTION = 'applications.DESCRIPTION';

	
	const SETTINGS = 'applications.SETTINGS';

	
	const VIEWS = 'applications.VIEWS';

	
	const VERSION = 'applications.VERSION';

	
	const HEIGHT = 'applications.HEIGHT';

	
	const SCROLLING = 'applications.SCROLLING';

	
	const MODIFIED = 'applications.MODIFIED';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Url', 'Title', 'DirectoryTitle', 'Screenshot', 'Thumbnail', 'Author', 'AuthorEmail', 'Description', 'Settings', 'Views', 'Version', 'Height', 'Scrolling', 'Modified', ),
		BasePeer::TYPE_COLNAME => array (ApplicationsPeer::ID, ApplicationsPeer::URL, ApplicationsPeer::TITLE, ApplicationsPeer::DIRECTORY_TITLE, ApplicationsPeer::SCREENSHOT, ApplicationsPeer::THUMBNAIL, ApplicationsPeer::AUTHOR, ApplicationsPeer::AUTHOR_EMAIL, ApplicationsPeer::DESCRIPTION, ApplicationsPeer::SETTINGS, ApplicationsPeer::VIEWS, ApplicationsPeer::VERSION, ApplicationsPeer::HEIGHT, ApplicationsPeer::SCROLLING, ApplicationsPeer::MODIFIED, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'url', 'title', 'directory_title', 'screenshot', 'thumbnail', 'author', 'author_email', 'description', 'settings', 'views', 'version', 'height', 'scrolling', 'modified', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Url' => 1, 'Title' => 2, 'DirectoryTitle' => 3, 'Screenshot' => 4, 'Thumbnail' => 5, 'Author' => 6, 'AuthorEmail' => 7, 'Description' => 8, 'Settings' => 9, 'Views' => 10, 'Version' => 11, 'Height' => 12, 'Scrolling' => 13, 'Modified' => 14, ),
		BasePeer::TYPE_COLNAME => array (ApplicationsPeer::ID => 0, ApplicationsPeer::URL => 1, ApplicationsPeer::TITLE => 2, ApplicationsPeer::DIRECTORY_TITLE => 3, ApplicationsPeer::SCREENSHOT => 4, ApplicationsPeer::THUMBNAIL => 5, ApplicationsPeer::AUTHOR => 6, ApplicationsPeer::AUTHOR_EMAIL => 7, ApplicationsPeer::DESCRIPTION => 8, ApplicationsPeer::SETTINGS => 9, ApplicationsPeer::VIEWS => 10, ApplicationsPeer::VERSION => 11, ApplicationsPeer::HEIGHT => 12, ApplicationsPeer::SCROLLING => 13, ApplicationsPeer::MODIFIED => 14, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'url' => 1, 'title' => 2, 'directory_title' => 3, 'screenshot' => 4, 'thumbnail' => 5, 'author' => 6, 'author_email' => 7, 'description' => 8, 'settings' => 9, 'views' => 10, 'version' => 11, 'height' => 12, 'scrolling' => 13, 'modified' => 14, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationsMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = ApplicationsPeer::getTableMap();
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
		return str_replace(ApplicationsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(ApplicationsPeer::ID);

		$criteria->addSelectColumn(ApplicationsPeer::URL);

		$criteria->addSelectColumn(ApplicationsPeer::TITLE);

		$criteria->addSelectColumn(ApplicationsPeer::DIRECTORY_TITLE);

		$criteria->addSelectColumn(ApplicationsPeer::SCREENSHOT);

		$criteria->addSelectColumn(ApplicationsPeer::THUMBNAIL);

		$criteria->addSelectColumn(ApplicationsPeer::AUTHOR);

		$criteria->addSelectColumn(ApplicationsPeer::AUTHOR_EMAIL);

		$criteria->addSelectColumn(ApplicationsPeer::DESCRIPTION);

		$criteria->addSelectColumn(ApplicationsPeer::SETTINGS);

		$criteria->addSelectColumn(ApplicationsPeer::VIEWS);

		$criteria->addSelectColumn(ApplicationsPeer::VERSION);

		$criteria->addSelectColumn(ApplicationsPeer::HEIGHT);

		$criteria->addSelectColumn(ApplicationsPeer::SCROLLING);

		$criteria->addSelectColumn(ApplicationsPeer::MODIFIED);

	}

	const COUNT = 'COUNT(applications.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT applications.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationsPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationsPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = ApplicationsPeer::doSelectRS($criteria, $con);
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
		$objects = ApplicationsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return ApplicationsPeer::populateObjects(ApplicationsPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			ApplicationsPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = ApplicationsPeer::getOMClass();
		$cls = sfPropel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
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
		return ApplicationsPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(ApplicationsPeer::ID); 

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
			$comparison = $criteria->getComparison(ApplicationsPeer::ID);
			$selectCriteria->add(ApplicationsPeer::ID, $criteria->remove(ApplicationsPeer::ID), $comparison);

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
			$affectedRows += BasePeer::doDeleteAll(ApplicationsPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(ApplicationsPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Applications) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ApplicationsPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(Applications $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ApplicationsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ApplicationsPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(ApplicationsPeer::DATABASE_NAME, ApplicationsPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = ApplicationsPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(ApplicationsPeer::DATABASE_NAME);

		$criteria->add(ApplicationsPeer::ID, $pk);


		$v = ApplicationsPeer::doSelect($criteria, $con);

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
			$criteria->add(ApplicationsPeer::ID, $pks, Criteria::IN);
			$objs = ApplicationsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseApplicationsPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationsMapBuilder');
}
