<?php
class module_moduleJson{
		
	public function _index(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			module_builder::getTools()->projetmkdir('data/json/base/'.$sTable);
			
			$this->generate($sTable,$tField);
			
			$msg='Base '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire data/json/base/'.$sTable;
			$detail.='<br />Cr&eacute;ation fichier data/json/base/'.$sTable.'/structure.csv';
			$detail.='<br />Cr&eacute;ation fichier data/json/base/'.$sTable.'/max.txt';
			
		}
	
		$oTpl= new _Tpl('moduleXml::index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	private function generate($sTable,$tField){
		
		$tNewField=array('id');
		
		foreach($tField as $sField){
			if(trim($sField)=='') continue;
			$tNewField[]=trim($sField);
		}
		$sStructure=implode(';',$tNewField);
		
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/data/json');
		if(!$oDir->exist()){
			$oDir->save();
		}
		
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/data/json/base');
		if(!$oDir->exist()){
			$oDir->save();
		}
		
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/data/json/base/'.$sTable.'');
		if(!$oDir->exist()){
			$oDir->save();
		}
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/json/base/'.$sTable.'/';
		$oFile=new _file($sPath.'structure.csv');
		$oFile->setContent($sStructure);
		$oFile->save();
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/json/base/'.$sTable.'/';
		$oFile=new _file($sPath.'max.txt');
		$oFile->setContent(1);
		$oFile->save();
	}
	
}
