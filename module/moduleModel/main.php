<?php
class module_moduleModel{
	
	
	public function _index(){
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
		$tTables=array();
		$tTableColumn=array();
		
		if(_root::getParam('sConfig') != ''){
			$sConfig= _root::getParam('sConfig');
			$tTables=module_builder::getTools()->getListTablesFromConfig( $sConfig );
			$tTableColumn=array();
			foreach($tTables as $sTable){
				$tTableColumn[$sTable]=module_builder::getTools()->getListColumnFromConfigAndTable($sConfig,$sTable);
			}
		}
		
		if(_root::getRequest()->isPost()){
			$tEnable=_root::getParam('tEnable');
			$tTables=_root::getParam('tTable');
			$tPrimary=_root::getParam('tPrimary');

			$tSelectEnable=_root::getParam('tSelectEnable');
			$tSelectKey=_root::getParam('tSelectKey');
			$tSelectVal=_root::getParam('tSelectVal');

			foreach($tTables as $i => $sTable){
				if(!in_array($sTable,$tEnable)) continue;
				
				$tSelect=null;
				if(is_array($tSelectEnable) and in_array($sTable,$tSelectEnable)){
					$tSelect=array(
						'key' => $tSelectKey[$i],
						'val' => $tSelectVal[$i],
					);
				}

				$this->genModelAndRowByTableConfigAndId($sTable,$sConfig,$tPrimary[$i],$tSelect);
				$detail.='Creation du fichier model/model_'.$sTable.'.php<br />';
			}
			$msg='Couche mod&egrave;le g&eacute;n&eacute;r&eacute;e avec succ&egrave;s';
		}
		
		$oTpl= new _Tpl('moduleModel::index');
		$oTpl->tTables=$tTables;
		$oTpl->tTableColumn=$tTableColumn;
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tConnexion=_root::getConfigVar('db');
		return $oTpl;
	}
	
	private function genModelAndRowByTableConfigAndId($sTable,$sConfig,$sId,$tSelect=null){
		$sTable=trim($sTable);
		
		$sContentGetSelect=null;
		if(is_array($tSelect)){
			$sContentGetSelect=module_builder::getTools()->stringReplaceIn(array(
												'exampleselectkey' => $tSelect['key'],
												'exampleselectval' => $tSelect['val'],
											),
											'data/sources/fichiers/model/getSelect.php'
			);
		}

		$sContent=module_builder::getTools()->stringReplaceIn(array(
											'exampletb' => $sTable,
											'exampleid' => $sId,
											'exampleconfig' => $sConfig,
											'\/\/ICI' => $sContentGetSelect,
										),
										'data/sources/projet/model/model_example.sample.php'
		);
		
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/model/model_'.$sTable.'.php' );
		
		if($oFile->exist()){
		  return false;
		}
		
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
		return true;
	}
	
}
