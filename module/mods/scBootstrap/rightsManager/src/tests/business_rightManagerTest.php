<?php

require_once(__DIR__ . '/../autoload_unitaire.php');

//fake i18n class
require_once(__DIR__ . '/plugin/plugin_i18nFake.php');

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class business_VARmoduleRightsManagerENDVARTest extends PHPUnit_Framework_TestCase {

	public function run(PHPUnit_Framework_TestResult $result = NULL) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	public function test_loadForUserShouldFinishOk(){

		$oModel=$this->createMock('VARinterfaceModelRightmanagerENDVAR');
		$oACL=$this->createMock('VARinterfaceACLENDVAR');
		$oValid=$this->createMock('interface_valid');

		$oPermission=new stdclass;
		$oPermission->actionName='write';
		$oPermission->itemName='item';
		$tPermission=array($oPermission);
		$oModel->method('findListByGroup')->willReturn($tPermission);

		$oACL->expects($this->once() )->method('allow')->with('write','item');

		$oAccount=new stdclass();
		$oAccount->VARuser_fk_group_idENDVAR=1;

		$oBusiness=new business_VARmoduleRightsManagerENDVAR($oModel,$oACL,new plugin_i18nFake(),$oValid );
		$oBusiness->loadForUser($oAccount);

		$this->assertTrue(true);
	}

	public function test_allowListShouldFinishOk(){
		$oModel=$this->createMock('VARinterfaceModelRightmanagerENDVAR');
		$oACL=$this->createMock('VARinterfaceACLENDVAR');
		$oValid=$this->createMock('interface_valid');

		$oPermission=new stdclass;
		$oPermission->actionName='write';
		$oPermission->itemName='item';
		$tPermission=array($oPermission);

		$oACL->expects($this->once() )->method('allow')->with('write','item');


		$oBusiness=new business_VARmoduleRightsManagerENDVAR($oModel,$oACL,new plugin_i18nFake(),$oValid  );
		$oBusiness->allowList($tPermission);

		$this->assertTrue(true);
	}

	public function test_getCheckShouldFinishOk(){
		$oModel=$this->createMock('VARinterfaceModelRightmanagerENDVAR');
		$oACL=$this->createMock('VARinterfaceACLENDVAR');
		$oValid=new plugin_sc_valid();

		$tParam=array(
			'VARpermission_fk_group_idENDVAR'=>'',
			'VARpermission_fk_action_idENDVAR'=>'',
			'VARpermission_fk_item_idENDVAR'=>''
		);

		$oBusiness=new business_VARmoduleRightsManagerENDVAR($oModel,$oACL,new plugin_i18nFake(),$oValid   );
		$oResultValid=$oBusiness->getCheck($tParam);

		$this->assertFalse($oResultValid->isValid() );

		$tErrorExpected=array(
			'VARpermission_fk_group_idENDVAR'=>array('{errorIsEmpty}'),
			'VARpermission_fk_action_idENDVAR'=>array('{errorIsEmpty}'),
			'VARpermission_fk_item_idENDVAR'=>array('{errorIsEmpty}')
		);

		$this->assertEquals($tErrorExpected,$oResultValid->getListError() );
	}

	public function test_updatePermission(){
		$oModel=$this->createMock('VARinterfaceModelRightmanagerENDVAR');
		$oACL=$this->createMock('VARinterfaceACLENDVAR');
		$oValid=new plugin_sc_valid();

		$oModel->method('findById')->willReturn(new stdclass() );

		$tParam=array(
			'VARpermission_fk_group_idENDVAR'=>'1',
			'VARpermission_fk_action_idENDVAR'=>'2',
			'VARpermission_fk_item_idENDVAR'=>'3'
		);

		$oBusiness=new business_VARmoduleRightsManagerENDVAR($oModel,$oACL,new plugin_i18nFake(),$oValid   );
		$bReturn=$oBusiness->updatePermission(1,$tParam);

		$this->assertEquals(true,$bReturn );
	}


}
