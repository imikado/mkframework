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
* classe _tpl unitTest
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _view{
	
	public $_sModule;
	public $_sTpl;
	public $_tVar;
	public $_sPath;
	
	/** 
	* constructeur
	* @access public	
	* @param string $sRessource nom du fichier de template a utiliser (module::fichier)
	*/
	public function __construct($sRessource=null){
		
		$this->_tVar=array();
	
		/*LOG*///_root::getLog()->info('--vue: initialisation ['.$sRessource.']');
		if($sRessource!=null){
			
			if(preg_match('/::/',$sRessource)){
				list($this->_sModule,$this->_sTpl)=preg_split('/::/',$sRessource);
				$sRessource=_root::getConfigVar('path.module').$this->_sModule.'/';
				$sRessource.=_root::getConfigVar('path.view','tpl/').$this->_sTpl.'.php';
			}
			
			$this->choose($sRessource);
		}
	}
	
	public function __set($sVar, $sVal){
		/*LOG*///_root::getLog()->info('---vue: assignation ['.$sVar.']');
		$this->_tVar[$sVar]=$sVal;
	}
	public function __get($sVar){
		if(!array_key_exists($sVar,$this->_tVar)){
			/*LOG*///_root::getLog()->error('Variable '.$sVar.' inexistante dans le template '.$this->_sModule.'::'.$this->_sTpl);
			throw new Exception('Variable '.$sVar.' inexistante dans le template '.$this->_sModule.'::'.$this->_sTpl);
		}else{
			return $this->_tVar[$sVar];
		}
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
	* retourne la sortie
	* @access public
	* @return string
	*/
	public function show(){
		/*LOG*///_root::getLog()->info('--vue: affichage ['.$this->_sPath.']');
		ob_start();
		include $this->_sPath;
		$sSortie=ob_get_contents();
		ob_end_clean();
		
		return $sSortie;
	}
	/** 
	* retourne le path de la vue
	* @access public
	* @return string
	*/
	public function getPath(){
		return $this->_sPath;
	}
	/** 
	* retourne un lien framework
	* @access public
	* @return string
	*/
	public function getLink($sLink,$tParam=null,$bAmp=true){	
		return _root::getLink($sLink,$tParam,$bAmp);
	}
	
	protected function choose($sPath){
		if(!file_exists($sPath) and !file_exists($sPath._root::getConfigVar('template.extension'))){
				/*LOG*///_root::getLog()->error('vue '.$sPath.' et  inexistant');
				throw new Exception('vue '.$sPath.' et '.$sPath._root::getConfigVar('template.extension').' inexistant');
		}
		$this->_sPath=$sPath;
	}
	
	
	
}
