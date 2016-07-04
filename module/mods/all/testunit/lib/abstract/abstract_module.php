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
* classe abstract_module unitTest
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class abstract_module{
	
	public $_tVar;
	
	/**
	* setter
	*/
	public function __set($sVar,$sVal){
		$this->_tVar[$sVar]=$sVal;
	}
	/**
	* getter
	*/
	public function __get($sVar){
		if(!isset($this->_tVar[$sVar])){
			throw new Exception('Propriete '.$sVar.' _module inexistant');
		}
		return $this->_tVar[$sVar];
	}
	
	public function before(){
		
	}
	public function after(){
		
	}

	public function UTgetView($sPlace){
		return $this->oLayout->_tContent[$sPlace][0];
	}
	public function UTgetListView($sPlace){
		return $this->oLayout->_tContent[$sPlace];
	}

}