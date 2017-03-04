<?php

class module_VARmoduleParentENDVAR_VARmoduleAuthENDVAR extends module_VARmoduleParentENDVAR {

	protected $_sModulePath = 'VARmoduleParentENDVAR/VARmoduleAuthENDVAR';

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


		$oBusinessAuth = new business_VARmoduleAuthENDVAR(VARmodelClassENDVAR::getInstance(), _root::getAuth(), _root::getI18n() );
		if (false === $oBusinessAuth->checkCredentials($sLogin, $sPassword)) {

			return $oBusinessAuth->getReturn()->getData('error');
		}

		$oUser=$oBusinessAuth->getReturn()->getData('oAccount');
		$oBusinessRightManager = new business_VARmoduleRightsManagerENDVAR(VARmodel_rightManagerENDVAR::getInstance(),_root::getACL(), _root::getI18n(), new plugin_sc_valid() );
		$oBusinessRightManager->loadForUser($oUser);

		_root::redirect('VARmoduleParentRedirectENDVAR_VARmoduleChildRedirectENDVAR::VARactionViewChildRedirectENDVAR');
	}

	public function _logout() {
		_root::getAuth()->logout();
	}
}
