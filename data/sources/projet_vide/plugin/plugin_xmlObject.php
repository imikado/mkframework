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
* plugin_xmlListObject classe pour generer le xml d'un objet
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_xmlObject{
	
	private $oObject;
	private $tColumn;
	private $sEncoding;
	private $sRootName;
	
	private $sXml;
	
	/** 
	* constructeur
	* @access public
	* @param object $oObject objet en edition
	*/
	public function __construct($oObject){
		$this->oObject=$oObject;
		
		$this->sEncoding='UTF-8';
		
		$this->sRootName='root';
	}
	/** 
	* definit l'encodage
	* @access public
	* @param string $sEncoding balise encoding de l'xml
	*/
	public function setEncoding($sEncoding){
		$this->sEncoding=$sEncoding;
	}
	/** 
	* definit le nom de la balise racine
	* @access public
	* @param string $sRootName nom de la balise
	*/
	public function setRootName($sRootName){
		$this->sRootName=$sRootName;
	}
	/** 
	* definit les colonnes a construire
	* @access public
	* @param array $tColumn tableau des colonnes a afficher
	*/
	public function setListColumn($tColumn){
		$this->tColumn=$tColumn;
	}
	/** 
	* affiche le xml a l'ecran
	* @access public
	*/
	public function show(){
		
		header ("Content-Type:text/xml");
		
		echo $this->build();
		
		exit;
	}
	/** 
	* retourne le xml genere
	* @access public
	* @return string le xml genere
	*/
	public function build(){
		$this->sXml.='<?xml version="1.0" encoding="'.$this->sEncoding.'"?>';
		
		$this->open($this->sRootName);
			foreach($this->tColumn as $sColumn){
				$this->add($sColumn,$this->oObject->$sColumn);
			}
		$this->close($this->sRootName);
		
		return $this->sXml;
	}
	
	private function add($sTag,$sValue){
		$this->sXml.='<'.$sTag.'><![CDATA['.$sValue.']]></'.$sTag.'>';
	}
	
	private function open($sTag){
		$this->sXml.='<'.$sTag.'>';
	}
	private function close($sTag){
		$this->sXml.='</'.$sTag.'>';
	}
	
}
