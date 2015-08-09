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
* classe abstract_row
* @author Mika
* @link http://mkf.mkdevs.com/
*/
abstract class abstract_rownosql{
	
	private $_bChooseUpdate=false;
	protected $_tProperty=array();
	protected $_tPropertyToUpdate;

	/**
	* constructeur
	* @access public
	*/
	public function __construct($tRow=null){
		if($tRow!=null){
			$this->_tProperty=$tRow;
			$this->chooseUpdate();
		}
	}
	/**
	* retourne l'objet model
	* @access public
	* @return object
	*/
	public function getModel(){
		$sModel=$this->sClassModel;
		return call_user_func(array($sModel,'getInstance'));
	}
	/**
	* enregistre la row
	* @access public
	*/
	public function save(){
		if($this->_bChooseUpdate == true){
			$this->update();
		}else{
			$this->setId($this->insert());
		}
	}
	/**
	* enregistre la row par update
	* @access public
	*/
	public function update(){
		$this->getModel()->update($this);
	}
	/**
	* enregistre la row par insertion
	* @access public
	*/
	public function insert(){
		return $this->getModel()->insert($this);
	}
	
	/**
	* supprime la row
	* @access public
	*/
	public function delete(){
		return $this->getModel()->delete($this);
	}
	
	/**
	* retourne si l'objet est vide ou non (pour verifier suite a une requete par exemple)
	* @return bool
	*/
	public function isEmpty(){
	  if(empty($this->_tProperty) and empty($this->_tPropertyToUpdate)){
		return true;
	  }
	  return false;
	}
	
	public function chooseUpdate(){
		$this->_bChooseUpdate=true;
	}
	/**
	* retourne le tableau contenant les proprietes a mettre a jour
	* @access public
	* @return array
	*/
	public function getToUpdate(){
		$tToUpdate=array();
		if($this->_tPropertyToUpdate){
			foreach($this->_tPropertyToUpdate as $sVar){
				$tToUpdate[$sVar]=$this->_tProperty[$sVar];
			}
		}
		return $tToUpdate;
	}
	/**
	* retourne le tableau contenant les proprietes id
	* @access public
	* @return array
	*/
	public function getWhere(){
		$tWhereId=array();
		$tId=$this->getModel()->getIdTab();
		if($tId){
			foreach($tId as $sVar){
				if(isset($this->_tProperty[$sVar])){
					$tWhereId[$sVar]=$this->_tProperty[$sVar];
				}
			}
		}
		return $tWhereId;
	}
	
	/**
	* setter
	*/
	public function __set($sVar,$sVal){
		$this->_tProperty[$sVar]=$sVal;
		$this->_tPropertyToUpdate[]=$sVar;
	}
	/**
	* getter
	*/
	public function __get($sVar){
		if(array_key_exists( (string)$sVar,$this->_tProperty)){
			return $this->_tProperty[$sVar];
		}
		return null;
	}
	/** 
	* isset
	*/
	public function __isset($sVar){
        return isset($this->_tProperty[$sVar]);
    }
    
    /** 
	* unset
	*/
    public function __unset($sVar){
        unset($this->_tProperty[$sVar]);
    }
	/**
	* force l'id de l'enregistrement
	* @param undefined $uId
	*/
	public function setId($uId){
		if($uId == null){ return false; }
		$tColumnId=$this->getModel()->getIdTab();
		$sColumnId=$tColumnId[0];
		
		$this->_tProperty[$sColumnId]=$uId;
		
		$this->chooseUpdate();
	}
	
	public function getId(){
		return implode('::',$this->getWhere());
	}

	public function getTab(){
		return $this->_tProperty;
	}

}
