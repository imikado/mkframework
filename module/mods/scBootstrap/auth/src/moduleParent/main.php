<?php

class module_VARmoduleParentENDVAR extends abstract_module {

	protected $oLayout;

	public function before() {
		//we enable authentification
		_root::getAuth()->enable();

		$sLang = _root::getConfigVar('language.default');
		if (isset($_SESSION['lang'])) {
			$sLang = $_SESSION['lang'];
		}
		_root::getI18n()->load($sLang);
		_root::getI18n()->loadFromDir(_root::getConfigVar('path.module').$this->_sModulePath.'/i18n/');

		$this->oLayout = new _layout('bootstrap');
	}


	public function after() {
		$this->oLayout->show();
	}

}
