<?php



class MemberApplicationsMapBuilder {

	
	const CLASS_NAME = 'plugins.opOpenSocialPlugin.lib.model.map.MemberApplicationsMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('member_applications');
		$tMap->setPhpName('MemberApplications');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, 'member', 'ID', false, null);

		$tMap->addForeignKey('APPLICATIONS_ID', 'ApplicationsId', 'int', CreoleTypes::INTEGER, 'applications', 'ID', false, null);

	} 
} 