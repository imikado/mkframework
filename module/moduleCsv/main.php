<?php
class module_moduleCsv{
	
	public function _index(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			$this->generate($sTable,$tField);
			
			$msg='Base '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail.='<br />Cr&eacute;ation fichier data/csv/base/'.$sTable.'.csv';
			
		}
	
		$oTpl= new _Tpl('moduleCsv::index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	public function generate($sTable,$tField){
		$ret="\n";
		$sep=';';
		
		$sFile='1'.$ret;
		$sFile.='id'.$sep;
		
		foreach($tField as $sField){
			if(trim($sField)=='') continue;
			$sFile.=trim($sField).$sep;
		}
		$sFile.=$ret;
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/csv/base/'.$sTable.'.csv';
		$oFile=new _file($sPath);
		$oFile->setContent($sFile);
		$oFile->save();
	}
	
}
