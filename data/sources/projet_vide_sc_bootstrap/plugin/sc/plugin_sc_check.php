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
* plugin_check classe pour verifier un lot de valeurs (verification de formulaire par exemple)
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_sc_check{


	/**
	* verifie si $uValueA est egal a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @bool retourne true/false selon
	*/
	public function isEqual($sColumn,$uValueB,$sErrorMsg='KO isEqual'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA==$uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est strictement egal a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isStrictlyEqual($sColumn,$uValueB,$sErrorMsg='KO isStrictlyEqual'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA === $uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA  n'est pas egal a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isNotEqual($sColumn,$uValueB,$sErrorMsg='KO isNotEqual'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA!=$uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est superieur a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isUpperThan($sColumn,$uValueB,$sErrorMsg='KO isUpperThan'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA > $uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est superieur ou egal a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isUpperOrEqualThan($sColumn,$uValueB,$sErrorMsg='KO isUpperOrEqualThan'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA >= $uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est inferieur a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isLowerThan($sColumn,$uValueB,$sErrorMsg='KO isLowerThan'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA < $uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est inferieur ou egal a $uValueB
	* @access public
	* @param string $sColumn champ
	* @param undefined $uValueB valeur B
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isLowerOrEqualThan($sColumn,$uValueB,$sErrorMsg='KO isLowerOrEqualThan'){
		$uValueA=$this->getValue($sColumn);
		if($uValueA <= $uValueB){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA est vide
	* @access public
	* @param string $sColumn champ
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isEmpty($sColumn,$sErrorMsg='KO isEmpty'){
		$uValueA=$this->getValue($sColumn);
		if(trim($uValueA)==''){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si $uValueA n'est pas vide
	* @access public
	* @param string $sColumn champ
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isNotEmpty($sColumn,$sErrorMsg='KO isNotEmpty'){
		$uValueA=$this->getValue($sColumn);
		if(trim($uValueA)!=''){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si le $uValueA est un email valide
	* @access public
	* @param string $sColumn champ
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function isEmailValid($sColumn,$sErrorMsg='KO isEmailValid'){
		$uValueA=$this->getValue($sColumn);
		if(preg_match('/^[\w.\-_]+@[\w.\-_]+\.[a-zA-Z]{2,6}$/',$uValueA)){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si le champ $sName verifie l'expression
	* @access public
	* @param string $sColumn champ
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function matchExpression($sColumn,$sExpression,$sErrorMsg='KO matchExpression'){
		$uValueA=$this->getValue($sColumn);
		if(preg_match($sExpression,$uValueA)){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
	/**
	* verifie si le champ $sName ne verifie pas l'expression
	* @access public
	* @param string $sColumn champ
	* @param string $sErrorMsg message d'erreur a afficher
	* @return bool retourne true/false selon
	*/
	public function notMatchExpression($sColumn,$sExpression,$sErrorMsg='KO notMatchExpression'){
		$uValueA=$this->getValue($sColumn);
		if(!preg_match($sExpression,$uValueA)){
			return true;
		}
		return $this->ko($sColumn,$sErrorMsg);
	}
}
