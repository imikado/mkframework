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
* classe _layout unitTest pour gerer le layout
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _layout{
	
	public $_tContent;
	public $_sLayout;
	public $_tVar;
	
	/** 
	* constructeur
	* @access public
	* @param string $sLayout nom du layout a utiliser
	*/
	public function __construct($sLayout=null){
		
		$this->_tVar=array();
	
		/*LOG*///_root::getLog()->info('--layout: initialisation ['.$sLayout.']');
		if($sLayout==null){ throw new Exception('layout non definit dans le constructeur :(');}
		$this->setLayout($sLayout);
	}

	/**
	* setter
	*/
	public function __set($sVar, $sVal){
		/*LOG*///_root::getLog()->info('---layout: assignation ['.$sVar.']');
		$this->_tVar[$sVar]=$sVal;
	}
	/** 
	* getter
	*/
	public function __get($sVar){
		if(!array_key_exists($sVar,$this->_tVar)){
			/*LOG*///_root::getLog()->error('Variable '.$sVar.' inexistante dans le layout '.$this->_sLayout);
			throw new Exception('Variable '.$sVar.' inexistante dans le layout '.$this->_sLayout);
		}else{
			return $this->_tVar[$sVar];
		}
	}
	
	public function UTgetView($sPlace){
		return $this->_tContent[$sPlace][0];
	}
	
	public function UTgetListView($sPlace){
		return $this->_tContent[$sPlace];
	}
	
	/** 
	* isset
	*/
	public function __isset($sVar){
		return isset($this->_tVar[$sVar]);
	}

	/** 
	* unset
	*/
	public function __unset($sVar){
		unset($this->_tVar[$sVar]);
	}
	
	/** 
	* forcer le layout
	* @access public
	* @param string $sLayout nom du layout a utiliser
	*/
	public function setLayout($sLayout){
		/*LOG*///_root::getLog()->info('-layout: choix de ['._root::getConfigVar('path.layout').$sLayout.'.php]');
		$this->_sLayout=$sLayout;
	}
	
	/** 
	* ajoute un objet _tpl $oTpl a l'emplacement $sPlace
	* @access public
	* @param string $sPlace emplacement
	* @param _tpl $oTpl objet _tpl
	*/
	public function add($sPlace,$oTpl){
		/*LOG*///_root::getLog()->info('-layout: ajout appel vue ['.$oTpl->getPath().'] a la place ['.$sPlace.']');
		$this->_tContent[$sPlace][]=$oTpl;
	}
	/** 
	* ajoute l'appel a un module (module::action) a l'emplacement $sPlace
	* @access public
	* @param string $sPlace emplacement
	* @param string $sAppel appel du module module::action
	*/
	public function addModule($sPlace,$sAppel){
		/*LOG*///_root::getLog()->info('-layout: ajout appel module ['.$sAppel.'] a la place ['.$sPlace.']');
		list($sModule,$sAction)= preg_split('/::/',$sAppel);
		$sModule='module_'.$sModule;
		$oModule=new $sModule;
		$this->_tContent[$sPlace][]=call_user_func( array($oModule,'_'.$sAction));
	}
	/** 
	* affiche le layout et son contenu
	* @access public
	*/
	public function show(){
		/*LOG*///_root::getLog()->info('-layout: affichage ['._root::getConfigVar('path.layout').$this->_sLayout.'.php]');
		if(!file_exists( _root::getConfigVar('path.layout').$this->_sLayout.'.php')){
			$tErreur=array(
				'layout '.$this->_sLayout.' introuvable dans '._root::getConfigVar('path.layout'),
				_root::getConfigVar('path.layout').$this->_sLayout.'.php introuvable',
			);
			throw new Exception(implode("\n",$tErreur));
		}
		include _root::getConfigVar('path.layout').$this->_sLayout.'.php';
	}
	/** 
	* recupere le layout et son contenu
	* @access public
	*/
	public function getOutput(){
		
		/*LOG*///_root::getLog()->info('-layout: getOutput ['._root::getConfigVar('path.layout').$this->_sLayout.'.php]');
		if(!file_exists( _root::getConfigVar('path.layout').$this->_sLayout.'.php')){
			$tErreur=array(
				'layout '.$this->_sLayout.' introuvable dans '._root::getConfigVar('path.layout'),
				_root::getConfigVar('path.layout').$this->_sLayout.'.php introuvable',
			);
			throw new Exception(implode("\n",$tErreur));
		}
		ob_start();
		include _root::getConfigVar('path.layout').$this->_sLayout.'.php';
		
		$sSortie=ob_get_contents();
		ob_end_clean();
		
		return $sSortie;
	}
	
	/** 
	* retourne le contenu d'un objet _tpl a l'emplacement $sPlace
	* @access public
	* @return string
	* @param string $sPlace
	*/
	public function load($sPlace){
		/*LOG*///_root::getLog()->info('-layout: chargement/affichage place ['.$sPlace.']');
		if(!isset($this->_tContent[$sPlace])){ return null;}
		$sLoad='';
		foreach($this->_tContent[$sPlace] as $oTpl){
			$sLoad.=$oTpl->show();
		}
		return $sLoad;
	}
}
