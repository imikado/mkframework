<?php

class module_global extends abstract_module {

	protected $oLayout;

	public function before() {
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
