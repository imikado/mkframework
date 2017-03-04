<?php

interface interface_i18n {

	public function load($sLang_);

	public function getLang();

	public function loadFromDir($sPath_);

	public function setContent($tab_);

	public function addContent($tab_);

	public function tr($sTag_);

	public function trR($sTag_, $tReplace_);
}
