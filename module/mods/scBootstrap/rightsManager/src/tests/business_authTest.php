<?php

require_once(__DIR__ . '/../autoload_unitaire.php');

//fake i18n class
require_once(__DIR__ . '/plugin/plugin_i18nFake.php');

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class business_VARmoduleAuthENDVARTest extends PHPUnit_Framework_TestCase {

	public function run(PHPUnit_Framework_TestResult $result = NULL) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	public function test_shouldFinishOk() {

		$tLogin = array();
		$tLogin['login']['hashPass'] = new stdclass;

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockModel->method('getListAccount')->willReturn($tLogin);
		$oMockModel->method('hashPassword')->willReturn('hashPass');

		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'pass';

		$oBusinessAuth = new business_VARmoduleAuthENDVAR($oMockModel, $oMockAuth, new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(true, $bReturn);
	}

	public function test_shouldFinishBadCredientials() {
		$tLogin = array();
		$tLogin['login']['wrongHash'] = new stdclass;

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockModel->method('getListAccount')->willReturn($tLogin);
		$oMockModel->method('hashPassword')->willReturn('hashPass');

		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'pass';

		$oBusinessAuth = new business_VARmoduleAuthENDVAR($oMockModel, $oMockAuth, new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(false, $bReturn);
		$this->assertEquals('{badCredentials}', $oBusinessAuth->getReturn()->getData('error'));
	}

	public function test_shouldFinishPasswordTooLong() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'a';
		for ($i = 0; $i < 101; $i++) {
			$sPass.='b';
		}

		$oBusinessAuth = new business_VARmoduleAuthENDVAR($oMockModel, $oMockAuth, new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(false, $bReturn);
		$this->assertEquals('{passwordTooLong}', $oBusinessAuth->getReturn()->getData('error'));
	}

}
