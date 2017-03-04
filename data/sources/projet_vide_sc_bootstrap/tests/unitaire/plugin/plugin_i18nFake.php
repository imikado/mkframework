<?php

class plugin_i18nFake implements interface_i18n{
	public function load($sLang_){}
	public function getLang(){}
	public function loadFromDir($sPath_){}
	public function setContent($tab_){}
	public function addContent($tab_){}

	public function tr($sTag_) {
		return '{' . $sTag_ . '}';
	}

	public function trR($sTag_, $tReplace_) {
		return str_replace(array_keys($tReplace_), array_values($tReplace_), $this->tr($sTag_));
	}
}
