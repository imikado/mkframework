<?php
class module_mods_all_sgbdXml extends abstract_moduleBuilder{

	protected $sModule='mods_all_sgbdXml';
	protected $sModuleView='mods/all/sgbdXml';
		
	public function _index(){
		$msg='';
		$detail='';
		if($this->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			$this->projectMkdir('data/xml/base/'.$sTable);
			
			$this->generate($sTable,$tField);

			$msg=trR('baseTableGenereAvecSucces',array('#maTable#'=>$sTable,'#listField#'=>implode(',',$tField)));
			
			$detail=trR('creationRepertoire',array('#REPERTOIRE#'=>'data/xml/base/'.$sTable));
			$detail.='<br />'.trR('creationFichier',array('#FICHIER#'=>'data/xml/base/'.$sTable.'/structure.xml'));
			$detail.='<br />'.trR('creationFichier',array('#FICHIER#'=>'data/xml/base/'.$sTable.'/max.xml'));
			
		}
	
		$oTpl= $this->getView('index');
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
		
		$sPath='data/xml/base/'.$sTable.'/';
		$this->projectSaveFile($sXmlStructure,$sPath.'structure.xml');

		$this->projectSaveFile($sXmlMax,$sPath.'max.xml');
	}
	
}
