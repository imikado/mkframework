<?php
class module_moduleModel{
	
	private function mongodbAddCollection(){
		$oModelMongo=new model_mkfbuilderfactory();
		$oModelMongo->setConfig(_root::getParam('sConfig'));
		
		$oModelMongo->getSgbd()->getDb()->createCollection(_root::getParam('collection'));
		
		_root::redirect('builder::edit',array('id'=>_root::getParam('id'),'action'=>_root::getParam('action'),'sConfig'=>_root::getParam('sConfig')));
	}
	
	public function _index(){
		
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		
		if(_root::getParam('sAction')=='mongodbAddCollection'){
			$this->mongodbAddCollection();
		}
		
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
		$r="\n";
		$t="\t";
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
		
		$sSave='parent::save();';
		
		$sSaveDuplicateKey=null;
		if(_root::getParam('mysqlOnDuplicateKey')==1){
			
			$soData='$o'.ucfirst($sTable);
			
			$tColumn=module_builder::getTools()->getListColumnFromConfigAndTable($sConfig,$sTable);
			foreach($tColumn as $sColumn){
				if($sColumn==$sId){
					continue;
				}
				$tFieldSql[]=$sColumn;
				
				$tSqlInsert[]='?';
				$tSqlUpdate[]=$sColumn.'=?';
				
				$tParam[]=$soData.'->'.$sColumn;
			}
			foreach($tColumn as $sColumn){
				if($sColumn==$sId){
					continue;
				}
				$tParam[]=$soData.'->'.$sColumn;
			}
			//$tParam[]=$soData.'->'.$sId;
			
			$sSqlUpdateId=$sId;
			
			$sSaveDuplicateKey.='public function save('.$soData.'){'."\n";
			$sSaveDuplicateKey.="\t"."\t".'$this->execute(\'INSERT INTO \'.$this->sTable.\' ('.implode(',',$tFieldSql).') VALUES ('.implode(',',$tSqlInsert).') ON DUPLICATE KEY UPDATE '.implode(',',$tSqlUpdate).'  \',array('.implode(',',$tParam).'));'."\n";
			$sSaveDuplicateKey.="\t".'}'."\n";
			
			$sSave='model_'.$sTable.'::getInstance()->save($this);';
		}
		
		$sRules=null;
		
		if(_root::getParam('tRuleColumn'.$sTable) ){
			$tRuleColumn=_root::getParam('tRuleColumn'.$sTable);
			$tRuleName=_root::getParam('tRuleName'.$sTable);
			$tRuleParam=_root::getParam('tRuleParam'.$sTable);
			$tRuleMsg=_root::getParam('tRuleMsg'.$sTable);
			
			if($tRuleColumn){
				
				$sRules=$r;
				
				foreach($tRuleColumn as $key => $sRuleColumn){
					
					
					if($sRuleColumn==''){
						continue;
					}
					
					$sRuleName=$tRuleName[$key];
					$sRuleParam=$tRuleParam[$key];
					$sRuleMsg=$tRuleMsg[$key];
					
					$sRules.=$t.$t;
					
					//$oPluginValid->isEqual('champ','valeurB','Le champ n\est pas &eacute;gal &agrave; '.$valeurB);
					$sRules.='$oPluginValid->'.$sRuleName.'('."'$sRuleColumn'";
					if(!in_array($sRuleName,array('isEmpty','isNotEmpty','isEmailValid'))){
						$sRules.=",'$sRuleParam'";
					}
					$sRules.=",'$sRuleMsg'";
					$sRules.=');'.$r;

					
					
					
				}
			}
		}
		
		$sFileModel='data/sources/projet/model/model_example.sample.php';
		if(_root::getConfigVar('db.'._root::getParam('sConfig').'.sgbd')=='mongodb'){
			$sFileModel='data/sources/fichiers/model/model_exampleMongodb.php';
		}
		
		$sContent=module_builder::getTools()->stringReplaceIn(array(
											'exampletb' => $sTable,
											'exampleid' => $sId,
											'exampleconfig' => $sConfig,
											'\/\/ICI' => $sContentGetSelect,
											'\/\/sSaveDuplicateKey' => $sSaveDuplicateKey,
											'\/\/save' => $sSave,
											'\/\/checkContraint' =>$sRules
										),
										$sFileModel
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
