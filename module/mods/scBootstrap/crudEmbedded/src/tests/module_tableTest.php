<?php

require_once(__DIR__ . '/../autoload_unitaire.php');

if (!function_exists('tr')) {

	function tr($sTag) {
		return $sTag;
	}

}

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class module_VARmoduleParentENDVAR_VARmoduleChildENDVARTest extends PHPUnit_Framework_TestCase {

	public function run(PHPUnit_Framework_TestResult $result = NULL) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	public function test_newShoulFinishOk() {

		_root::getI18n()->load('fr');

		$tColumn = array(VARbusinessArrayColumnENDVAR);

		//arrange
		$_SERVER['REQUEST_METHOD'] = 'GET';
		_root::addRequest(array());
		_root::loadRequest();

		//act
		$VARoTableENDVAR = new stdclass;
		foreach ($tColumn as $sColumn) {
			$VARoTableENDVAR->$sColumn = 'value' . $sColumn . 'value';
		}

		$oModule = new module_VARmoduleParentENDVAR_VARmoduleChildENDVAR;

		$oView = $oModule->getView('new');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;
		$oView->tMessage = array();
		$oView->token = null;

		$sView = $oView->show();

		//assert

		foreach ($tColumn as $sColumn) {
			$this->assertRegExp('/name="' . $sColumn . '"/', $sView);
		}
	}

	public function test_editShoulFinishOk() {

		_root::getI18n()->load('fr');

		$tColumn = array(VARbusinessArrayColumnENDVAR);

		//arrange
		$_SERVER['REQUEST_METHOD'] = 'GET';
		_root::addRequest(array());
		_root::loadRequest();

		//act
		$VARoTableENDVAR = new stdclass;
		foreach ($tColumn as $sColumn) {
			$VARoTableENDVAR->$sColumn = 'value' . $sColumn . 'value';
		}

		$oModule = new module_VARmoduleParentENDVAR_VARmoduleChildENDVAR;

		$oView = $oModule->getView('edit');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;
		$oView->tMessage = array();
		$oView->token = null;

		$sView = $oView->show();

		//assert

		foreach ($tColumn as $sColumn) {
			$this->assertRegExp('/name="' . $sColumn . '"/', $sView);
			$this->assertRegExp('/value' . $sColumn . '/', $sView);
		}
	}

	public function test_showShoulFinishOk() {

		_root::getI18n()->load('fr');

		$tColumn = array(VARbusinessArrayColumnENDVAR);

		//arrange
		$_SERVER['REQUEST_METHOD'] = 'GET';
		_root::addRequest(array());
		_root::loadRequest();

		//act
		$VARoTableENDVAR = new stdclass;
		foreach ($tColumn as $sColumn) {
			$VARoTableENDVAR->$sColumn = 'value' . $sColumn . 'value';
		}

		$oModule = new module_VARmoduleParentENDVAR_VARmoduleChildENDVAR;

		$oView = $oModule->getView('show');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		$sView = $oView->show();

		//assert

		foreach ($tColumn as $sColumn) {
			$this->assertRegExp('/' . $sColumn . '/', $sView);
			$this->assertRegExp('/value' . $sColumn . '/', $sView);
		}
	}

	public function test_listShoulFinishOk() {

		_root::getI18n()->load('fr');

		$tColumn = array(VARbusinessArrayColumnENDVAR);

		//arrange
		$_SERVER['REQUEST_METHOD'] = 'GET';
		_root::addRequest(array());
		_root::loadRequest();

		//act
		$VARtTableENDVAR = array();
		for ($i = 0; $i < 4; $i++) {

			$VARoTableENDVAR = new stdclass;
			foreach ($tColumn as $sColumn) {
				$VARoTableENDVAR->$sColumn = 'value' . $i . $sColumn . 'value' . $i;
			}
			$VARoTableENDVAR->VARkeyFieldENDVAR = $i;
			$VARtTableENDVAR[] = $VARoTableENDVAR;
		}

		$oModule = new module_VARmoduleParentENDVAR_VARmoduleChildENDVAR;

		$oView = $oModule->getView('list');
		$oView->VARtTableENDVAR = $VARtTableENDVAR;

		$sView = $oView->show();

		//assert
		foreach ($tColumn as $sColumn) {
			$this->assertRegExp('/' . $sColumn . '/', $sView);
		}

		for ($i = 0; $i < 4; $i++) {
			foreach ($tColumn as $sColumn) {
				$this->assertRegExp('/value' . $i . $sColumn . 'value' . $i . '/', $sView);
			}
		}
	}

}
