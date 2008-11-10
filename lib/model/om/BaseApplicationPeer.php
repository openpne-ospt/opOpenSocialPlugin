<?php


abstract class BaseApplicationPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'application';

	
	const CLASS_DEFAULT = 'plugins.opOpenSocialPlugin.lib.model.Application';

	
	const NUM_COLUMNS = 16;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'application.ID';

	
	const URL = 'application.URL';

	
	const CULTURE = 'application.CULTURE';

	
	const TITLE = 'application.TITLE';

	
	const DIRECTORY_TITLE = 'application.DIRECTORY_TITLE';

	
	const SCREENSHOT = 'application.SCREENSHOT';

	
	const THUMBNAIL = 'application.THUMBNAIL';

	
	const AUTHOR = 'application.AUTHOR';

	
	const AUTHOR_EMAIL = 'application.AUTHOR_EMAIL';

	
	const DESCRIPTION = 'application.DESCRIPTION';

	
	const SETTINGS = 'application.SETTINGS';

	
	const VIEWS = 'application.VIEWS';

	
	const VERSION = 'application.VERSION';

	
	const HEIGHT = 'application.HEIGHT';

	
	const SCROLLING = 'application.SCROLLING';

	
	const UPDATED_AT = 'application.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Url', 'Culture', 'Title', 'DirectoryTitle', 'Screenshot', 'Thumbnail', 'Author', 'AuthorEmail', 'Description', 'Settings', 'Views', 'Version', 'Height', 'Scrolling', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (ApplicationPeer::ID, ApplicationPeer::URL, ApplicationPeer::CULTURE, ApplicationPeer::TITLE, ApplicationPeer::DIRECTORY_TITLE, ApplicationPeer::SCREENSHOT, ApplicationPeer::THUMBNAIL, ApplicationPeer::AUTHOR, ApplicationPeer::AUTHOR_EMAIL, ApplicationPeer::DESCRIPTION, ApplicationPeer::SETTINGS, ApplicationPeer::VIEWS, ApplicationPeer::VERSION, ApplicationPeer::HEIGHT, ApplicationPeer::SCROLLING, ApplicationPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'url', 'culture', 'title', 'directory_title', 'screenshot', 'thumbnail', 'author', 'author_email', 'description', 'settings', 'views', 'version', 'height', 'scrolling', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Url' => 1, 'Culture' => 2, 'Title' => 3, 'DirectoryTitle' => 4, 'Screenshot' => 5, 'Thumbnail' => 6, 'Author' => 7, 'AuthorEmail' => 8, 'Description' => 9, 'Settings' => 10, 'Views' => 11, 'Version' => 12, 'Height' => 13, 'Scrolling' => 14, 'UpdatedAt' => 15, ),
		BasePeer::TYPE_COLNAME => array (ApplicationPeer::ID => 0, ApplicationPeer::URL => 1, ApplicationPeer::CULTURE => 2, ApplicationPeer::TITLE => 3, ApplicationPeer::DIRECTORY_TITLE => 4, ApplicationPeer::SCREENSHOT => 5, ApplicationPeer::THUMBNAIL => 6, ApplicationPeer::AUTHOR => 7, ApplicationPeer::AUTHOR_EMAIL => 8, ApplicationPeer::DESCRIPTION => 9, ApplicationPeer::SETTINGS => 10, ApplicationPeer::VIEWS => 11, ApplicationPeer::VERSION => 12, ApplicationPeer::HEIGHT => 13, ApplicationPeer::SCROLLING => 14, ApplicationPeer::UPDATED_AT => 15, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'url' => 1, 'culture' => 2, 'title' => 3, 'directory_title' => 4, 'screenshot' => 5, 'thumbnail' => 6, 'author' => 7, 'author_email' => 8, 'description' => 9, 'settings' => 10, 'views' => 11, 'version' => 12, 'height' => 13, 'scrolling' => 14, 'updated_at' => 15, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = ApplicationPeer::getTableMap();
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
		return str_replace(ApplicationPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(ApplicationPeer::ID);

		$criteria->addSelectColumn(ApplicationPeer::URL);

		$criteria->addSelectColumn(ApplicationPeer::CULTURE);

		$criteria->addSelectColumn(ApplicationPeer::TITLE);

		$criteria->addSelectColumn(ApplicationPeer::DIRECTORY_TITLE);

		$criteria->addSelectColumn(ApplicationPeer::SCREENSHOT);

		$criteria->addSelectColumn(ApplicationPeer::THUMBNAIL);

		$criteria->addSelectColumn(ApplicationPeer::AUTHOR);

		$criteria->addSelectColumn(ApplicationPeer::AUTHOR_EMAIL);

		$criteria->addSelectColumn(ApplicationPeer::DESCRIPTION);

		$criteria->addSelectColumn(ApplicationPeer::SETTINGS);

		$criteria->addSelectColumn(ApplicationPeer::VIEWS);

		$criteria->addSelectColumn(ApplicationPeer::VERSION);

		$criteria->addSelectColumn(ApplicationPeer::HEIGHT);

		$criteria->addSelectColumn(ApplicationPeer::SCROLLING);

		$criteria->addSelectColumn(ApplicationPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(application.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT application.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ApplicationPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ApplicationPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = ApplicationPeer::doSelectRS($criteria, $con);
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
		$objects = ApplicationPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return ApplicationPeer::populateObjects(ApplicationPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			ApplicationPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = ApplicationPeer::getOMClass();
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
		return ApplicationPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(ApplicationPeer::ID); 

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
			$comparison = $criteria->getComparison(ApplicationPeer::ID);
			$selectCriteria->add(ApplicationPeer::ID, $criteria->remove(ApplicationPeer::ID), $comparison);

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
			$affectedRows += BasePeer::doDeleteAll(ApplicationPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(ApplicationPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Application) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ApplicationPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(Application $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ApplicationPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ApplicationPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(ApplicationPeer::DATABASE_NAME, ApplicationPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = ApplicationPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(ApplicationPeer::DATABASE_NAME);

		$criteria->add(ApplicationPeer::ID, $pk);


		$v = ApplicationPeer::doSelect($criteria, $con);

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
			$criteria->add(ApplicationPeer::ID, $pks, Criteria::IN);
			$objs = ApplicationPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseApplicationPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('plugins.opOpenSocialPlugin.lib.model.map.ApplicationMapBuilder');
}
