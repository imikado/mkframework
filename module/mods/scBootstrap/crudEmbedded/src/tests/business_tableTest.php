<?php

require_once(__DIR__ . '/../autoload_unitaire.php');

//fake i18n class
require_once(__DIR__ . '/plugin/plugin_i18nFake.php');

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class VARbusiness_tableENDVARTest extends PHPUnit_Framework_TestCase {

	public function run(PHPUnit_Framework_TestResult $result = NULL) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	public function test_insertItemShoulFinishOk() {

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');

		$oBusiness = new VARbusiness_tableENDVAR($oMockModel,new plugin_i18nFake() );

		$tParam = array();
		$tColumn = array(VARbusinessArrayColumnENDVAR);
		foreach ($tColumn as $sColumn) {
			$tParam[$sColumn] = 1;
		}

		$bReturn = $oBusiness->insertItem(new stdclass(), $tParam);

		$this->assertEquals(true, $bReturn);
	}

	public function test_updateItemShoulFinishOk() {

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');

		$oBusiness = new VARbusiness_tableENDVAR($oMockModel,new plugin_i18nFake() );

		$tParam = array();
		$tColumn = array(VARbusinessArrayColumnENDVAR);
		foreach ($tColumn as $sColumn) {
			$tParam[$sColumn] = 1;
		}

		$bReturn = $oBusiness->updateItem(new stdclass(), $tParam);

		$this->assertEquals(true, $bReturn);
	}

	public function test_insertItemShouldReturnErrorsMissing() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');

		$oBusiness = new VARbusiness_tableENDVAR($oMockModel,new plugin_i18nFake() );

		$tParam = array();
		$tColumn = array(VARbusinessArrayColumnENDVAR);
		foreach ($tColumn as $sColumn) {
			$tParam[$sColumn] = null;
		}

		$bReturn = $oBusiness->insertItem(new stdclass(), $tParam);

		$this->assertEquals(false, $bReturn);

		$tError = array();
		foreach ($tColumn as $sColumn) {
			$tError[$sColumn] = array('{errorIsEmpty}');
		}
		$this->assertEquals($tError, $oBusiness->getReturn()->getData('tError'));
	}

	public function test_updateItemShouldReturnErrorsMissing() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');

		$oBusiness = new VARbusiness_tableENDVAR($oMockModel,new plugin_i18nFake() );

		$tParam = array();
		$tColumn = array(VARbusinessArrayColumnENDVAR);
		foreach ($tColumn as $sColumn) {
			$tParam[$sColumn] = null;
		}

		$bReturn = $oBusiness->updateItem(new stdclass(), $tParam);

		$this->assertEquals(false, $bReturn);

		$tError = array();
		foreach ($tColumn as $sColumn) {
			$tError[$sColumn] = array('{errorIsEmpty}');
		}
		$this->assertEquals($tError, $oBusiness->getReturn()->getData('tError'));
	}

}
