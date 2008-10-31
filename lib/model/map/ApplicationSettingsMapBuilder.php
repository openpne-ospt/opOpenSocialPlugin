<?php



class ApplicationSettingsMapBuilder {

	
	const CLASS_NAME = 'plugins.opOpenSocialPlugin.lib.model.map.ApplicationSettingsMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('application_settings');
		$tMap->setPhpName('ApplicationSettings');

		$tMap->setUseIdGenerator(true);

		$tMap->addForeignKey('APPLICATIONS_ID', 'ApplicationsId', 'int', CreoleTypes::INTEGER, 'applications', 'ID', false, null);

		$tMap->addForeignKey('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, 'member', 'ID', false, null);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('VALUE', 'Value', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

	} 
} 