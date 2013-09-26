<?php
class module_moduleSqlite{
	
	public function _index(){
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		
		$tConnexion=_root::getConfigVar('db');
		
		$tSqlite=array();
		foreach($tConnexion as $sConfig => $val){
			if(substr($val,0,6)=='sqlite'){
				$tSqlite[ substr($sConfig,0,-4) ]=$val;
			}
		}
		

		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sDbFilename=_root::getParam('sDbFilename');
			
			$sTable=_root::getParam('sTable');
			$tField=_root::getParam('tField');
			$tType=_root::getParam('tType');
			$tSize=_root::getParam('tSize');
			
			try{
				$oDb = new PDO($sDbFilename);
			}catch( PDOException $exception ){
				die($exception->getMessage());
			}
			$oDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sSql='CREATE TABLE IF NOT EXISTS '.$sTable.'(';
			$sSql.='id  INTEGER PRIMARY KEY AUTOINCREMENT';
			foreach($tField as $i => $sField){
				$sSql.=',';
				$sSql.=$sField.' '.$tType[$i]; if($tType[$i]=='VARCHAR'){ $sSql.='('.$tSize[$i].')';} 
			}
			$sSql.=')';
			
			
			try{
				$oDb->exec($sSql);
			}catch( PDOException $exception ){
				die($exception->getMessage());
			}

			$msg='Table '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation du fichier sqlite '.$sDbFilename;
			
			
		}
	
		$oTpl= new _Tpl('moduleSqlite::index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tSqlite=$tSqlite;
		return $oTpl;
	}
	
}
