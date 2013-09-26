<?php
class module_moduleXml{
		
	public function _index(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			module_builder::getTools()->projetmkdir('data/xml/base/'.$sTable);
			
			$this->generate($sTable,$tField);
			
			$msg='Base '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire data/xml/base/'.$sTable;
			$detail.='<br />Cr&eacute;ation fichier data/xml/base/'.$sTable.'/structure.xml';
			$detail.='<br />Cr&eacute;ation fichier data/xml/base/'.$sTable.'/max.xml';
			
		}
	
		$oTpl= new _Tpl('moduleXml::index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	private function generate($sTable,$tField){
		$ret="\n";
		$sXmlStructure='<?xml version="1.0" encoding="UTF-8"?>'.$ret;
		$sXmlStructure.='<structure>'.$ret;
		$sXmlStructure.='<colonne primaire="true">id</colonne>'.$ret;
		foreach($tField as $sField){
			if(trim($sField)=='') continue;
			$sXmlStructure.='<colonne>'.trim($sField).'</colonne>'.$ret;
		}
		$sXmlStructure.='</structure>'.$ret;
		
		$sXmlMax='<?xml version="1.0" encoding="ISO-8859-1"?>'.$ret;
		$sXmlMax.='<main>'.$ret;
		$sXmlMax.='<max><![CDATA[1]]></max>'.$ret;
		$sXmlMax.='</main>'.$ret;
		
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/xml/base/'.$sTable.'/';
		$oFile=new _file($sPath.'structure.xml');
		$oFile->setContent($sXmlStructure);
		$oFile->save();
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/xml/base/'.$sTable.'/';
		$oFile=new _file($sPath.'max.xml');
		$oFile->setContent($sXmlMax);
		$oFile->save();
	}
	
}
