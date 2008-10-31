<?php



class ApplicationsMapBuilder {

	
	const CLASS_NAME = 'plugins.opOpenSocialPlugin.lib.model.map.ApplicationsMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('applications');
		$tMap->setPhpName('Applications');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('URL', 'Url', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('TITLE', 'Title', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('DIRECTORY_TITLE', 'DirectoryTitle', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('SCREENSHOT', 'Screenshot', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('THUMBNAIL', 'Thumbnail', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('AUTHOR', 'Author', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('AUTHOR_EMAIL', 'AuthorEmail', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('DESCRIPTION', 'Description', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('SETTINGS', 'Settings', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('VIEWS', 'Views', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('VERSION', 'Version', 'string', CreoleTypes::VARCHAR, false, 64);

		$tMap->addColumn('HEIGHT', 'Height', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('SCROLLING', 'Scrolling', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('MODIFIED', 'Modified', 'int', CreoleTypes::INTEGER, false, null);

	} 
} 