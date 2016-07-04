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
* classe abstract_model
* @author Mika
* @link http://mkf.mkdevs.com/
*/
abstract class abstract_model{
	
	public $_sClassSgbd=null;
	public $_oSgbd=null;
	public static $_tInstance=array();
	
	public $tTestUnitReturn=null;
	public $oSave=null;

	protected $_tAssoc=array();

	public function setReturn($tTestUnitReturn){
		$this->tTestUnitReturn=$tTestUnitReturn;
	}
	
	public function getConfig(){
		return $this->sConfig;
	}
	public function getTable(){
		return $this->sTable;
	}

	public function findOne(){
		$tSql=func_get_args();
		return $this->tTestUnitReturn;
	}
	public function findMany(){
		$tSql=func_get_args();
		return $this->tTestUnitReturn;
	}
	public function findOneSimple(){
		$tSql=func_get_args();
		return $this->tTestUnitReturn;
	}
	public function findManySimple(){
		$tSql=func_get_args();
		return $this->tTestUnitReturn;
	}

	protected static function _getInstance($class) {
		if (array_key_exists($class, self::$_tInstance) === false){
			self::$_tInstance[$class] = new $class();
		}
		return self::$_tInstance[$class];
	}


	public function update($oRow){

print "UPDATE unitTest \n\n";

		$oSave=new $this->sClassRow;
		$tFieldUpdate=$oRow->getToUpdate();
		$tFieldId=$oRow->getWhere();

		foreach($tFieldUpdate as $sField => $sValue){
			$oSave->$sField=$sValue;
		}
		foreach($tFieldId as $sField => $sValue){
			$oSave->$sField=$sValue;
		}

print_r($oSave);

		$this->oSave=$oSave;
	}
	/** 
	* ajoute un enregistrement
	* @access public
	* @param object $oRow
	*/
	public function insert($oRow){
		$oSave=new stdclass;
		$tFieldUpdate=$oRow->getToUpdate();
		$tFieldId=$oRow->getWhere();

		foreach($tFieldUpdate as $sField => $sValue){
			$oSave->$sField=$sValue;
		}
		foreach($tFieldId as $sField => $sValue){
			$oSave->$sField=$sValue;
		}


		$this->oSave=$oSave;
		return $oSave;
	}
	/** 
	* supprime un enregistrement
	* @access public
	* @param object $oRow
	*/
	public function delete($oRow){
		$this->getSgbd()->delete($this->sTable,$oRow->getWhere());
	}


	public function UTgetSaveObject(){
		return $this->oSave;
	}
	public static function UTsetInstance($class,$oObject){
		self::$_tInstance[$class]=$oObject;
	}


	public function getIdTab(){
		return $this->tId;
	}
}