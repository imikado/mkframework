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
* plugin_form classe pour generer des elements de formulaire
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_formMultiRow{
	
	protected $tObject;
	protected $ttMessage;
	protected $isPost;

	protected $i=-1;
	
	const NOVALUE='pluginFormNoValue';
	
	/** 
	* constructeur
	* @access public
	* @param object $oObject objet en edition
	*/
	public function __construct(){
		
		$this->isPost=false;
		if(_root::getRequest()->isPost()){
			$this->isPost=true;
		}
	}

	public function add($oObject){
		$this->i++;
		$this->tObject[$this->i]=$oObject;
	}

	
	/** 
	* initialise le tableau de message d'erreur
	* @access public
	* @param array $tMessage tableau de message d'erreur
	*/
	public function setMessage($tMessage){
		$this->tMessage=$tMessage;
	}
	/** 
	* retourne un champ input cache
	* @access public
	* @param string $sName nom du champ
	* @param array $tOption options du champ texte
	*/
	public function getInputHidden($sName,$tOption=null){
		$sHtml=null;
		$sHtml.='<input type="hidden" name="'.$sName.'['.$this->i.']" value="'.$this->getValue($sName).'" '.$this->getOption($tOption).'/>';
		return $sHtml;
	}
	/** 
	* retourne un champ input de jeton (xsrf)
	* @access public
	* @param string $sName nom du champ
	* @param string $sValue valeur du jeton
	* @param array $tOption options du champ texte
	*/
	public function getToken($sName,$sValue,$tOption=null){
		$sHtml=null;
		$sHtml.='<input type="hidden" type="text" name="'.$sName.'['.$this->i.']" value="'.$sValue.'" '.$this->getOption($tOption).'/>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ input texte
	* @access public
	* @param string $sName nom du champ
	* @param array $tOption options du champ texte
	*/
	public function getInputText($sName,$tOption=null){
		$sHtml=null;
		$sHtml.='<input type="text" name="'.$sName.'['.$this->i.']" value="'.$this->getValue($sName).'" '.$this->getOption($tOption).'/>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ input textarea
	* @access public
	* @param string $sName nom du champ
	* @param array $tOption options du champ texte
	*/
	public function getInputTextarea($sName,$tOption=null){
		$sHtml=null;
		$sHtml.='<textarea type="text" name="'.$sName.'['.$this->i.']" '.$this->getOption($tOption).'>';
		$sHtml.=$this->getValue($sName).'</textarea>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ upload
	* @access public
	* @param string $sName nom du champ
	* @param array $tOption options du champ upload
	*/
	public function getInputUpload($sName,$tOption=null){
		$sHtml=null;
		$sHtml.='<input type="file" name="'.$sName.'['.$this->i.']" '.$this->getOption($tOption).'/>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ menu deroulant
	* @access public
	* @param string $sName nom du champ
	* @param array @tValue tableau des valeurs du menu deroulant
	* @param array $tOption options du champ 
	*/
	public function getSelect($sName,$tValue,$tOption=null){
		
		$sCurrentValue=$this->getValue($sName);
		
		$sHtml=null;
		$sHtml.='<select name="'.$sName.'['.$this->i.']" '.$this->getOption($tOption).'>';
			foreach($tValue as $sValue => $sLabel){
				$sHtml.='<option '; 
				if($sValue==$sCurrentValue){ 
					$sHtml.=' selected="selected"'; 
				} 
				$sHtml.=' value="'.$sValue.'">'.$sLabel.'</option>';
			}
		$sHtml.='</select>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
		
	}
	/** 
	* retourne une liste de champs radio
	* @access public
	* @param string $sName nom du champ
	* @param array @tValue tableau des valeurs de champ radio
	* @param array $tOption options des champs 
	*/
	public function getListRadio($sName,$tValue,$tOption=null){
		$sCurrentValue=$this->getValue($sName);
		
		$sHtml=null;
	
		foreach($tValue as $sValue => $sLabel){
			$sHtml.='<input type="radio" name="'.$sName.'['.$this->i.']" '; 
			if($sValue==$sCurrentValue){ 
				$sHtml.=' checked="checked"'; 
			} 
			$sHtml.=' value="'.$sValue.'" '.$this->getOption($tOption).'/>'.$sLabel.' ';
		}
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ input checkbox
	* @access public
	* @param string $sName nom du champ
	* @param string $sValue valeur du champ checkbox
	* @param array $tOption options du champ 
	*/
	public function getInputCheckbox($sName,$sValue,$tOption=null){
		$sCurrentValue=$this->getValue($sName);
		
		$sHtml='<input type="checkbox" '; 
		if($sCurrentValue==$sValue){ 
			$sHtml.='checked="checked" '; 
		} 
		$sHtml.=' name="'.$sName.'['.$this->i.']" value="'.$sValue.'" '.$this->getOption($tOption).'/>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	/** 
	* retourne un champ input radio
	* @access public
	* @param string $sName nom du champ
	* @param string $sValue valeur du champ radio
	* @param array $tOption options du champ 
	*/
	public function getInputRadio($sName,$sValue,$tOption=null){
		$sCurrentValue=$this->getValue($sName);
		
		$sHtml='<input type="radio" '; 
		if($sCurrentValue==$sValue){ 
			$sHtml.='checked="checked" '; 
		} 
		$sHtml.=' name="'.$sName.'['.$this->i.']" value="'.$sValue.'" '.$this->getOption($tOption).'/>';
		$sHtml.=$this->getMessage($sName);
		return $sHtml;
	}
	
	private function getValue($sName){
		$tPost=_root::getParam($sName);


		if($this->isPost and isset($tPost[$this->id]) ){
			return $tPost[$this->id];
		}else if($this->tObject and isset($this->tObject[$this->i]->$sName)){
			return $this->tObject[$this->i]->$sName; 
		}
		return null;
	}
	
	private function getMessage($sName){
		if(isset($this->tMessage[$this->i]) and isset($this->tMessage[$this->i][$sName])){
			if(is_array($this->tMessage[$this->i][$sName])){
				return '<p class="error">'.implode(',',$this->tMessage[$this->i][$sName]).'</p>';
			}else{
				return '<p class="error">'.$this->tMessage[$this->i][$sName].'</p>';
			}
		}
		return null;
	}
	
	private function getOption($tOption=null){
		
		if(!$tOption){
			return null;
		}
		
		$sHtml=null;
		foreach($tOption as $sKey => $sValue){
			$sHtml.=$sKey.'="'.$sValue.'" ';
			
		}
		return $sHtml;
		
	}
}
