<?php

class module_VARmoduleParentENDVAR_VARmoduleChildENDVAR extends module_VARmoduleParentENDVAR {

	protected $_sModulePath = 'VARmoduleParentENDVAR/VARmoduleChildENDVAR';

	public function getView($sView_) {
		return new _view($this->_sModulePath . '::' . $sView_);
	}

	VARfieldsENDVAR
}
