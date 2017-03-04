<?php

/*
  This file is part of Mkframework.

  Mkframework is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License.

  Mkframework is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License
  along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * plugin_i18n classe pour gerer l'internationnalisation
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
class plugin_sc_i18n implements interface_i18n{

	protected $_tLangue;
	protected $_sLang;

	/**
	 * charge le fichier de langue situe dans la section [path], valeur de i18n
	 * @access public
	 * @param string $sLang (doit etre present dans le fichier de config [language] allow separer par des virgules
	 */
	public function load($sLang_) {
		$tAllowed = preg_split('/,/', _root::getConfigVar('language.allow'));
		if (!in_array($sLang_, $tAllowed) and $sLang_ != _root::getConfigVar('language.default')) {
			throw new Exception('Lang not allowed, list allow:' . _root::getConfigVar('language.allow'));
		}

		$this->_sLang=$sLang_;

		include_once _root::getConfigVar('path.i18n') . $sLang_ . '.php';
	}

	public function getLang(){
		return $this->_sLang;
	}

	public function loadFromDir($sPath_){
		if(file_exists($sPath_.'/'.$this->getLang().'.php')){
			include_once $sPath_.'/'.$this->getLang().'.php';
		}
	}

	public function setContent($tab_){
		$this->_tLangue=$tab_;
	}

	public function addContent($tab_){
		$tLang=$this->_tLangue;
		$this->_tLangue=array_merge($tLang,$tab_);
	}

	/**
	 * retourne la traduction du tag $sTag_
	 * @access public
	 * @param string $sTag tag du mot a traduire
	 */
	public function tr($sTag_) {
		if (!isset($this->_tLangue[$sTag_])) {
			return '{' . $sTag_ . '}';
		}
		return $this->_tLangue[$sTag_];
	}

	/**
	 * retourne la traduction du tag $sTag_ en remplacant les pattern du tableau $tReplace_
	 * @access public
	 * @param string $sTag tag du mot a traduire
	 */
	public function trR($sTag_, $tReplace_) {
		return str_replace(array_keys($tReplace_), array_values($tReplace_), $this->tr($sTag_));
	}

}
