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
* plugin_check classe pour verifier un lot de valeurs (verification de formulaire par exemple)
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_sc_valid extends plugin_sc_check implements interface_valid{

	private $bCheck;
	private $tPost;
	private $tCheck;

	/**
	* charge le tableau a verifier
	* @access public
	* @param array tableau a verifier ($_POST,tableau de la row...)
	*/
	public function load($tPost){
		$this->tPost=$tPost;
		$this->bCheck=true;
	}

	/**
	* retourne la valeur $sName du tableau en memoire
	* @access protected
	* @param string $sName nom du champ
	* @return undefined retourne la valeur du champ
	*/
	protected function getValue($sName){
		if(!isset($this->tPost[$sName])){
			return null;
		}
		return $this->tPost[$sName];
	}

	/**
	* verifie si tout est ok
	* @access public
	* @return bool retourne true/false selon
	*/
	public function isValid(){
		return $this->bCheck;
	}
	/**
	* retourne le tableau d'erreur
	* @access public
	* @return array tableau d'erreur
	*/
	public function getListError(){
		return $this->tCheck;
	}

	/**
	* ajoute une message d'erreur
	* @access public
	*/
	public function ko($sField_,$sErrorMessage_){
		$this->bCheck=false;
		$this->tCheck[ $sField_ ][]= $sErrorMessage_;
	}

}
