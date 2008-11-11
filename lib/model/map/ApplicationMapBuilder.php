<?php



class ApplicationMapBuilder {

	
	const CLASS_NAME = 'plugins.opOpenSocialPlugin.lib.model.map.ApplicationMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('application');
		$tMap->setPhpName('Application');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('URL', 'Url', 'string', CreoleTypes::VARCHAR, true, 128);

		$tMap->addColumn('HEIGHT', 'Height', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('SCROLLING', 'Scrolling', 'int', CreoleTypes::INTEGER, true, null);

	} 
} 