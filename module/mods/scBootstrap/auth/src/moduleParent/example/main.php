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

	public function _logout() {
		_root::getAuth()->logout();
	}


}
