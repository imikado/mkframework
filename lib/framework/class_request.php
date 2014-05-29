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
* _request classe pour gerer le les requete
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _request{
	
	private $_tVar;
	private $_sModule;
	private $_sAction;
	private $_bHasNavigation;
	
	/** 
	* constructeur
	* @access public
	* @param array $tTab tableau de la requete $_GET, $_POST...
	*/
	public function __construct(){
		
		$this->_sModule=_root::getConfigVar('navigation.module.default',null);
		
		$this->_sAction=_root::getConfigVar('navigation.action.default',null);
				
		$this->_tVar=array();
	
		$this->_bHasNavigation=false;
	}
	/** 
	* retourne le parametre $sVar ou $else
	* @access public
	* @return undefined $else
	* @param string $sVar variable a retourner
	*/
	public function getParam($sVar,$else=null){
	
		if(array_key_exists($sVar,$this->_tVar)){
			if( (int)_root::getConfigVar('security.xss.enabled')==1){
				if(is_array($this->_tVar[$sVar]) ){
					return array_map('customHtmlentities',$this->_tVar[$sVar]);
				}
				return customHtmlentities($this->_tVar[$sVar]);
			}else{
				return $this->_tVar[$sVar];
			}	
		}else{
			return $else;
		}
	}
	
	



	
	
	
	
	/** 
	* force une variable de request
	* @access public
	* @param string $sVar
	* @param string $val
	*/
	public function setParam($sVar,$val){
		$this->_tVar[$sVar]=$val;
		if(!$this->hasNavigation()){
			$this->loadContext();
		}
	}
	
	/**
	* recupere un tableau de l'ensemble des parametres
	*/
	public function getParams(){
		if( (int)_root::getConfigVar('security.xss.enabled')==1){
			return array_map('customHtmlentities',$this->_tVar);
		}else{
			return $this->_tVar;
		}
	}
	
	/**
	* recupere un tableau de l'ensemble des parametres GET (hors navigation: module::action)
	*/
	public function getParamsGET(){
		if( (int)_root::getConfigVar('security.xss.enabled')==1){
			$tParam=array();
			foreach($_GET as $key => $val){
					$tParam[customHtmlentities($key)]=customHtmlentities($val);
			}

		}else{
			$tParam= $_GET;
		}
		unset($tParam[ _root::getConfigVar('navigation.var') ] );
		
		return $tParam;
	}
	
	/**
	* recupere un tableau de l'ensemble des parametres POST
	*/
	public function getParamsPOST(){
		if( (int)_root::getConfigVar('security.xss.enabled')==1){
			return array_map('customHtmlentities',$_POST);
		}else{
			return $_POST;
		}
	}
	
	/** 
	* retourne la valeur de navigation
	* @access public
	* @return string navigation (module::action)
	*/
	public function getParamNav(){
	
		return $this->getModule().'::'.$this->getAction();
	}
	/** 
	* force une variable de navigation
	* @access public
	* @param string $sNav
	*/
	public function setParamNav($sNav){
		$this->loadModuleAndAction($sNav);
	}
	
	/** 
	* retourne le parametre $sVar ou $else
	* @access public
	* @return undefined $else
	* @param string $sVar variable a retourner
	*/
	public function hasNavigation(){
		return $this->_bHasNavigation;
	}
	/** 
	* defini le module $sModule
	* @access public
	* @param string $sModule
	*/
	public function setModule($sModule){
		$this->_sModule=$sModule;
	}
	/** 
	* defini le module $sAction
	* @access public
	* @param string $sAction
	*/
	public function setAction($sAction){
		$this->_sAction=$sAction;
	}
	/** 
	* retourne le module
	* @access public
	* @return string
	*/
	public function getModule(){
		return $this->_sModule;
	}
	/** 
	* retourne l'action
	* @access public
	* @return string
	*/
	public function getAction(){
		return $this->_sAction;
	}

	private function loadContext(){
		if($this->getParam( _root::getConfigVar('navigation.var'),null)!==null ){
			$this->loadModuleAndAction($this->getParam( _root::getConfigVar('navigation.var') ) );
		}else{
			$this->setModule( _root::getConfigVar('navigation.module.default'));
			$this->setAction( _root::getConfigVar('navigation.action.default'));
		}
	}
	/** 
	* initialise module/action a partir d'une chaine module::action
	* @access public
	* @param string $sChaine
	*/
	public function loadModuleAndAction($sChaine){
		$this->_tVar[ _root::getConfigVar('navigation.var') ]=$sChaine;
		$this->_bHasNavigation=true;
		$sModule='';
		$sAction='';
		if(preg_match('/::/',$sChaine)){
			list($sModule,$sAction)=preg_split('/::/',$sChaine);
		}else{
			$sModule=$sChaine;
		}
		if($sAction==''){
			$sAction='index';
		}
		if($sModule==''){
			$sModule='index';
		}
		$this->setModule($sModule);
		$this->setAction($sAction);
	}
	
	/** 
	* indique si la requete utilise la methode POST (soumission de formulaire par exple)
	* @access public
	* @return bool
	*/
	public function isPost(){
		if($_SERVER['REQUEST_METHOD']=='POST'){ return true;}
		return false;
	}
	/** 
	* indique si la requete utilise la methode GET
	* @access public
	* @return bool
	*/
	public function isGet(){
		if($_SERVER['REQUEST_METHOD']=='GET'){ return true;}
		return false;
	}
	
	public function stripslashes_deep($value){
		if(is_array($value)){
			return array_map('stripslashes_deep', $value);
		}else{
			return stripslashes($value);
		}
	}
	public function magic_quote(){
	  $this->_tVar=array_map('stripslashes_deep', $this->_tVar);
	}
	
	public function __set($sVar,$sVal){
		$this->_tVar[$sVar]=$sVal;
	}
	public function __get($sVar){
		return $this->_tVar[$sVar];
	}


}
