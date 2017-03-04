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
* plugin_log classe pour loguer
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_sc_log{

	private $bInfo=0;
	private $bWarning=0;
	private $bError=0;
	private $bAppli=0;

	/**
	* constructeur
	* @access public
	*/
	public function __construct($bApplication_=0,$bInformation_=0,$bWarning_=0,$bError_=0){
		$this->setApplication($bApplication_);
		$this->setInformation($bInformation_);
		$this->setWarning($bWarning_);
		$this->setError($bError_);

	}
	/**
	* active/desactive le log applicatif
	* @access public
	* @param bool $bActive true/false selon
	*/
	public function setApplication($bActif){
		$this->bAppli=$bActif;
	}
	/**
	* active/desactive le log informatif (framework)
	* @access public
	* @param bool $bActive true/false selon
	*/
	public function setInformation($bActif){
		$this->bInfo=$bActif;
	}
	/**
	* active/desactive le log warning
	* @access public
	* @param bool $bActive true/false selon
	*/
	public function setWarning($bActif){
		$this->bWarning=$bActif;
	}
	/**
	* active/desactive le log erreur
	* @access public
	* @param bool $bActive true/false selon
	*/
	public function setError($bActif){
		$this->bError=$bActif;
	}

	/**
	* vous permet dans votre application de loguer
	* @access public
	* @param string $sMessage message a loguer
	*/
	public function log($sMessage){
		if(!$this->bAppli){ return null;}
		$this->writefile('log;'.$sMessage);
		return true;
	}
	/**
	* vous permet dans votre application de loguer en tant qu'erreur
	* @access public
	* @param string $sMessage message a loguer
	*/
	public function error($sMessage){
		if(!$this->bError){ return null;}
		$this->writefile('ERROR;'.$sMessage);
		return true;
	}
	/**
	* vous permet dans votre application de loguer en tant que warning
	* @access public
	* @param string $sMessage message a loguer
	*/
	public function warning($sMessage){
		if(!$this->bWarning){ return null;}
		$this->writefile('Warning;'.$sMessage);
		return true;
	}
	/**
	* utiliser principalement par le framework pour indiquer tout ce qui se passe (a desactiver en production)
	* @access public
	* @param string $sMessage message a loguer
	*/
	public function info($sMessage){
		if(!$this->bInfo){ return null;}
		$this->writefile('info;'.$sMessage);
		return true;
	}

	private function writefile($sMessage){

		$sMessage= preg_replace('/\s+/',' ',$sMessage);

		$sFilename=_root::getConfigVar('path.log','data/log/').date('Y-m-d').'_log.csv';
		$sContent=date('Y-m-d').';'.date('H:i:s').';'.$sMessage."\n";

		try{
			file_put_contents($sFilename,$sContent,FILE_APPEND);
		}catch(Exception $e){
	  		throw new Exception (
		  		'Probleme lors de l\'ecriture du log'."\n"
		  		.'note:verifier les droits du repertoire '._root::getConfigVar('path.log','data/log')."\n"
		  		.'Exception: '.$e->getMessage());
		}
	}

}
