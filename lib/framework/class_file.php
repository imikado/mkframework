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
* _file classe pour gerer un fichier
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _file{
	
	private $_sAdresse;
	private $_sContent;
	
	/** 
	* constructeur
	* @access public
	* @param string $sAdresse l'adresse du fichier
	*/
	public function __construct($sAdresse=null){
		if($sAdresse!=null){
			$this->setAdresse($sAdresse);
		}
		$this->_sContent=null;
	}
	/** 
	* indique que ce n'est pas un repertoire (utilise apres un _file->getList 
	* @access public
	* @return false
	*/
	public function isDir(){
		return false;
	}
	/** 
	* indique que c'est un fichier (utilise apres un _file->getList 
	* @access public
	* @return true
	*/
	public function isFile(){
		return true;
	}
	/** 
	* defini l'adresse du fichier
	* @access public
	* @param string $sAdresse adresse du fichier
	*/
	public function setAdresse($sAdresse){
		if($sAdresse!=null){
			$this->_sAdresse=$sAdresse;
		}
	}
	/** 
	* retourne l'adresse complete du fichier
	* @access public
	* @return string
	*/
	public function getAdresse(){
		return  $this->_sAdresse;
	}
	/** 
	* initialise le contenu du fichier
	* @access public
	* @param string $sContent contenu du fichier
	*/
	public function setContent($sContent){
		$this->_sContent=$sContent;
	}
	/** 
	* ajoute du contenu au fichier
	* @access public
	* @param string $sContent contenu du fichier a ajouter
	*/
	public function addContent($sContent){
		$this->_sContent.=$sContent;
	}
	/** 
	* sauvegarde le fichier
	* @access public
	*/
	public function save($sOption='w'){
		$this->write($this->_sContent,$sOption);
	}
	/** 
	* retourne le contenu du fichier
	* @access public
	* @return string 
	*/
	public function getContent(){
		$this->verif();
		
		$sFichier=file_get_contents($this->_sAdresse);
		
		return $sFichier;		
	}
	/** 
	* charge le contenu du fichier
	* @access public
	*/
	public function load(){
		$this->_sContent=$this->getContent();
	}
	/** 
	* reinitialise le fichier
	* @access public
	*/
	public function clean(){
		$this->_sContent=null;
		$this->_sAdresse=null;
	}
	/** 
	* retourne le contenu du fichier sous forme d'un tableau
	* @access public
	* @return array 
	*/
	public function getTab(){
		$this->verif();
	
		return file($this->_sAdresse);
	}
	/** 
	* supprime le fichier
	* @access public
	*/
	public function delete(){
		$this->verif();
	
		unlink($this->_sAdresse);
	}
	/** 
	* test l'existence du fichier
	* @access public
	* @return bool true ou false
	*/
	public function exist(){
		return file_exists($this->_sAdresse);
	}
	/** 
	* retourne le nom du fichier
	* @access public
	* @return string
	*/
	public function getName(){
		$this->verif();
		return basename($this->_sAdresse);
	}
	/** 
	* retourne l'extension du fichier
	* @access public
	* @return string
	*/
	public function getExtension(){
		$this->verif();
		$tTmp=preg_split('/\./',$this->_sAdresse);
		return end($tTmp);
	}
	/** 
	* ecrit $sContent avec l'option $sOption
	* @access public
	* @param string $sContent
	* @param string $sOption
	*/
	public function write($sContent,$sOption='w'){
		
		if($sContent==''){
			file_put_contents($this->_sAdresse,$sContent);
		}else if(!file_put_contents($this->_sAdresse,$sContent)){
			throw new Exception('Can t write "'.$sContent.'"'.$this->_sAdresse); 
		}
	}
	/** 
	* retourne le timestamp de modification du fichier
	* @access public
	* @return int
	*/
	public function filemtime(){
		return filemtime($this->_sAdresse);
	}
	/** 
	* change les droits d'un fichier
	* @access public
	* @param string $iVal du chmod a faire
	*/
	public function chmod($iVal=0777){
		chmod($this->_sAdresse,$iVal);
	}
	
	private function verif(){
		if(!$this->exist()){
			throw new Exception($this->_sAdresse.' n\'existe pas');
		}
		return true;
	}

}
