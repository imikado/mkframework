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
* _dir classe pour gerer un repertoire
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _dir{
	
	private $_sAdresse=null;
	
	/** 
	* constructeur
	* @access public	
	* @param string $sAdresse l'adresse du repertoire
	*/
	public function __construct($sAdresse=null){
		if($sAdresse!=null){
			$this->setAdresse($sAdresse);
		}
	}
	/** 
	* indique que c'est un repertoire (utilise apres un _file->getList 
	* @access public
	* @return true
	*/
	public function isDir(){
		return true;
	}
	/** 
	* indique que ce n'est pas un fichier (utilise apres un _file->getList 
	* @access public
	* @return false
	*/
	public function isFile(){
		return false;
	}
	/** 
	* defini l'adresse du repertoire
	* @access public	
	* @param string $sAdresse l'adresse du repertoire
	*/
	public function setAdresse($sAdresse){
		if($sAdresse!=null){
			$this->_sAdresse=$sAdresse;
		}
	}
	/** 
	* retourne l'adresse complete du repertoire
	* @access public
	* @return string
	*/
	public function getAdresse(){
		return  $this->_sAdresse;
	}
	
	/** 
	* retourne le nom du repertoire
	* @access public
	* @return string
	*/
	public function getName(){
		$this->verif();
		return basename($this->_sAdresse);
	}
	/** 
	* recupere la liste des fichiers / repertoire
	* @access public
	* @param array $tInclusion tableau des extensions a prendre
	* @param array $tExclusion tableau des extensions a exclure
	* @param string $sType dir|file pour filtrer si besoin que les fichiers/repertoires
        * @param boolen $bWithHidden boolean avec fichier cache ou non
	* @return array $tFile tableau de _file et _dir contenu dans le repertoire
	*/
	public function getList($tInclusion=null,$tExclusion=null,$bWithHidden=false){
		$this->verif();
		
		$open=openDir($this->_sAdresse);
		
		$tFile=array();

		while(false !== ($sFile=readDir($open)) ){
				
			$bIsDir=is_dir($this->_sAdresse.'/'.$sFile);
			$tDetailFile=preg_split('/\./',$sFile);
			
			if($bWithHidden==false and $sFile[0]=='.'){
				continue;
			}elseif($bIsDir==true){
				$oElement=new _dir($this->_sAdresse.'/'.$sFile);
				$tFile[]=$oElement;
			}elseif( 
				($tInclusion==null or in_array(end($tDetailFile),$tInclusion))
				and 
				($tExclusion==null or !in_array(end($tDetailFile),$tExclusion))
			){
				$oElement=new _file($this->_sAdresse.'/'.$sFile);
				$tFile[]=$oElement;
				
			}
			
			
		}
		
		return $tFile;
	}
	/** 
	* retourne un tableau des fichiers disponible
	* @access public
	* @param array $tInclusion
	* @param array $tExclusion
	* @return array d'objet _file
	*/
	public function getListFile($tInclusion=null,$tExclusion=null,$bWithHidden=false){
		$this->verif();
		
		$open=openDir($this->_sAdresse);
		
		$tFile=array();

		while(false !== ($sFile=readDir($open)) ){
				
			$bIsDir=is_dir($this->_sAdresse.'/'.$sFile);
			$tDetailFile=preg_split('/\./',$sFile);
			
			if($bWithHidden==false and $sFile[0]=='.'){
				continue;
				
			}elseif( 
				$bIsDir==false 
				and ($tInclusion==null or in_array(end($tDetailFile),$tInclusion))
				and ($tExclusion==null or !in_array(end($tDetailFile),$tExclusion))
			
			){
				$oElement=new _file($this->_sAdresse.'/'.$sFile);
				$tFile[]=$oElement;
				
			}
			
			
		}
		
		return $tFile;
	}
	/** 
	* retourne un tableau des repertoire disponible
	* @access public
	* @param array $tInclusion
	* @param array $tExclusion
	* @return array d'objet _file
	*/
	public function getListDir($bWithHidden=false){
		$this->verif();
		
		$open=openDir($this->_sAdresse);
		
		$tFile=array();

		while(false !== ($sFile=readDir($open)) ){
				
			$bIsDir=is_dir($this->_sAdresse.'/'.$sFile);
			
			if($bWithHidden==false and $sFile[0]=='.'){
				continue;
			}elseif($bIsDir==true){
				$oElement=new _dir($this->_sAdresse.'/'.$sFile);
				$tFile[]=$oElement;
			}
			
			
		}
		
		return $tFile;
	}
	/** 
	* supprime le repertoire
	* @access public
	*/
	public function delete(){
		$this->verif();
	
		if(!@rmdir($this->_sAdresse)){
			throw new Exception('Erreur rmdir ('.$this->_sAdresse.')');
		}
	}
	/** 
	* test l'existence du repertoire
	* @access public
	* @return bool true ou false
	*/
	public function exist(){
		return file_exists($this->_sAdresse);
	}
	/** 
	* cree le repertoire
	* @access public
	*/
	public function save(){
		mkdir($this->_sAdresse);
	}
	/** 
	* change les droits du repertoire
	* @access public
	*/
	public function chmod($valeur=0777){
		chmod($this->_sAdresse,$valeur);
	}
	
	private function verif(){
		
		if($this->_sAdresse==null){
			throw new Exception('objet _dir: Adresse du repertoire non defini');
		}
	
		if(!$this->exist()){
			throw new Exception($this->_sAdresse.' n\'existe pas');
		}
	}
}
