<?php

require_once(__DIR__ . '/../autoload_unitaire.php');

//fake i18n class
require_once(__DIR__ . '/plugin/plugin_i18nFake.php');

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class business_VARmoduleChildENDVARTest extends PHPUnit_Framework_TestCase {

	public function run(PHPUnit_Framework_TestResult $result = NULL) {
		$this->setPreserveGlobalState(false);
		return parent::run($result);
	}

	public function test_checkCredentialsShouldFinishOk() {

		$tLogin = array();
		$tLogin['login']['hashPass'] = new stdclass;

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockModel->method('getListAccount')->willReturn($tLogin);
		$oMockModel->method('hashPassword')->willReturn('hashPass');

		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'pass';

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(true, $bReturn);
	}

	public function test_checkCredentialsShouldFinishBadCredientials() {
		$tLogin = array();
		$tLogin['login']['wrongHash'] = new stdclass;

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockModel->method('getListAccount')->willReturn($tLogin);
		$oMockModel->method('hashPassword')->willReturn('hashPass');

		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'pass';

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(false, $bReturn);
		$this->assertEquals('{badCredentials}', $oBusinessAuth->getReturn()->getData('error'));
	}

	public function test_checkCredentialsShouldFinishPasswordTooLong() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'a';
		for ($i = 0; $i < 101; $i++) {
			$sPass.='b';
		}

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->checkCredentials($sLogin, $sPass);

		$this->assertEquals(false, $bReturn);
		$this->assertEquals('{passwordTooLong}', $oBusinessAuth->getReturn()->getData('error'));
	}

	public function test_registerNewAccountShouldFinishOk() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'password';
		$sPass2 = 'password';

		$oUser = new stdClass;

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->registerNewAccount($oUser, $sLogin, $sPass, $sPass2);

		$this->assertEquals(true, $bReturn);
	}

	public function test_registerNewAccountShouldErrorLoginEmpty() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = '';
		$sPass = 'password';
		$sPass2 = 'password';

		$oUser = new stdClass;

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->registerNewAccount($oUser, $sLogin, $sPass, $sPass2);
		$tData = $oBusinessAuth->getReturn()->getData('tError');

		$this->assertEquals(false, $bReturn);
		$this->assertEquals(array('VARloginFieldENDVAR' => array('{champDutilisteurRequis}')), $tData);
	}

	public function test_registerNewAccountShouldErrorPasswordNotEqual() {
		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'password';
		$sPass2 = 'password2';

		$oUser = new stdClass;

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->registerNewAccount($oUser, $sLogin, $sPass, $sPass2);
		$tData = $oBusinessAuth->getReturn()->getData('tError');

		$this->assertEquals(false, $bReturn);
		$this->assertEquals(array('VARpasswordFieldENDVAR' => array('{lesDeuxMotsDePasseDoiventEtreIdentique}')), $tData);
	}

	public function test_registerNewAccountShouldErrorUserStillExist() {

		$tAccount = array();
		$tAccount['login']['passwordHashed'] = new stdclass;

		$oMockModel = $this->createMock('VARinterfaceModelENDVAR');
		$oMockModel->method('getListAccount')->willReturn($tAccount);
		$oMockAuth = $this->createMock('VARinterfaceAuthENDVAR');

		$sLogin = 'login';
		$sPass = 'password';
		$sPass2 = 'password';

		$oUser = new stdClass;

		$oBusinessAuth = new business_VARmoduleChildENDVAR($oMockModel, $oMockAuth,new plugin_i18nFake() );

		$bReturn = $oBusinessAuth->registerNewAccount($oUser, $sLogin, $sPass, $sPass2);
		$tData = $oBusinessAuth->getReturn()->getData('tError');

		$this->assertEquals(false, $bReturn);
		$this->assertEquals(array('VARloginFieldENDVAR' => array('{utilisateurDejaExistant}')), $tData);
	}

}
