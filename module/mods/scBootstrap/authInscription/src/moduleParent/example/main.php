<?php

class module_VARmoduleParentENDVAR_VARmoduleChildENDVAR extends module_VARmoduleParentENDVAR {

	protected $_sModulePath = 'VARmoduleParentENDVAR/VARmoduleChildENDVAR';

	public function getView($sView_) {
		return new _view($this->_sModulePath . '::' . $sView_);
	}

	public function _login() {

		$sMessage = $this->checkLoginPass();

		$oView = $this->getView('login');
		$oView->sError = $sMessage;

		$this->oLayout->add('main', $oView);
	}

	private function checkLoginPass() {
		//if form is not sent, we stop there
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$sLogin = _root::getParam('login');
		$sPassword = _root::getParam('password');


		$oBusinessAuth = new business_VARmoduleChildENDVAR(VARmodelClassENDVAR::getInstance(), _root::getAuth(), _root::getI18n() );
		if (false === $oBusinessAuth->checkCredentials($sLogin, $sPassword)) {

			return $oBusinessAuth->getReturn()->getData('error');
		}


		_root::redirect('VARmoduleParentRedirectENDVAR_VARmoduleChildRedirectENDVAR::VARactionViewChildRedirectENDVAR');
	}

	public function _inscription() {
		$tMessage = $this->processInscription();

		$oView = $this->getView('inscription');
		$oView->tMessage = $tMessage;

		$oView->oUser = new VARrow_userENDVAR;

		$this->oLayout->add('main', $oView);
	}

	private function processInscription() {
		if (!_root::getRequest()->isPost()) {
			return null;
		}

		$sLogin = _root::getParam('VARloginFieldENDVAR');
		$sPassword = _root::getParam('VARpasswordFieldENDVAR');
		$sPassword2 = _root::getParam('VARpasswordFieldENDVARConfirm');

		$oBusinessAuth = new business_VARmoduleChildENDVAR(VARmodelClassENDVAR::getInstance(), _root::getAuth(), _root::getI18n() );
		if (false === $oBusinessAuth->registerNewAccount(new VARrow_userENDVAR, $sLogin, $sPassword, $sPassword2)) {

			return $oBusinessAuth->getReturn()->getData('tError');
		}

		return array('success' => array(tr('compteCreeAvecSucces')));
	}

	public function _logout() {
		_root::getAuth()->logout();
	}


}
