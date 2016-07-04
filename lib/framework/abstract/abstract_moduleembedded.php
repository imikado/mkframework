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
* classe abstract_module
* @author Mika
* @link http://mkf.mkdevs.com/
*/
abstract class abstract_moduleembedded{

	protected $_tVar;
	
	public function before(){
		
	}
	public function after(){
		
	}
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
	
	
	public static function _getLink($sRootModule,$tRootParams,$sModuleName,$sAction,$tParam=null){
		
		$sPrefix=$sModuleName;
		
		$tNewParam=array();
		if($tRootParams){
			$tNewParam=$tRootParams;
		}
		
		$tNewParam[$sPrefix.'Action']=$sAction;
		if($tParam){
			foreach($tParam as $sKey => $sVal){
				$tNewParam[ $sPrefix.$sKey ]=$sVal;
			}
		}
	
		return _root::getLink($sRootModule,$tNewParam);
	}
	public static function _getParam($sModuleName,$sVar,$uDefault=null){
		$sPrefix=$sModuleName;
		return _root::getParam($sPrefix.$sVar,$uDefault);
	}
	public static function _setParam($sModuleName,$sVar,$uValue){
		$sPrefix=$sModuleName;
		return _root::setParam($sPrefix.$sVar,$uValue);
	}
	
	
	public static function _redirect($sRootModule,$tRootParams,$sModuleName,$sModuleAction,$tModuleParam=null){
		
		$sPrefix=$sModuleName;
		
		$tParam=array();
		if($tRootParams){
			$tParam=$tRootParams;
		}
		
		$tParam[ $sPrefix.'Action' ]=$sModuleAction;
		if($tModuleParam){
			foreach($tModuleParam as $sKey => $sVal){
				$tParam[ $sPrefix.$sKey ]=$sVal;
			}
		}
	
		return _root::redirect($sRootModule,$tParam);
	}

}
