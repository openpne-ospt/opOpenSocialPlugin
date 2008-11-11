<?php



class ApplicationI18nMapBuilder {

	
	const CLASS_NAME = 'plugins.opOpenSocialPlugin.lib.model.map.ApplicationI18nMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('application_i18n');
		$tMap->setPhpName('ApplicationI18n');

		$tMap->setUseIdGenerator(false);

		$tMap->addColumn('TITLE', 'Title', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('DIRECTORY_TITLE', 'DirectoryTitle', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('SCREENSHOT', 'Screenshot', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('THUMBNAIL', 'Thumbnail', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('AUTHOR', 'Author', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('AUTHOR_EMAIL', 'AuthorEmail', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('DESCRIPTION', 'Description', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('SETTINGS', 'Settings', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('VIEWS', 'Views', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('VERSION', 'Version', 'string', CreoleTypes::VARCHAR, true, 64);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addForeignPrimaryKey('ID', 'Id', 'int' , CreoleTypes::INTEGER, 'application', 'ID', true, null);

		$tMap->addPrimaryKey('CULTURE', 'Culture', 'string', CreoleTypes::VARCHAR, true, 7);

	} 
} 