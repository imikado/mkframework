<?php
class module_mods_all_model extends abstract_moduleBuilder{

	protected $sModule='mods_all_model';
	protected $sModuleView='mods/all/model';
	
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

				$this->generate($sTable,$sConfig,$tPrimary[$i],$tSelect);
				$detail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'model/model_'.$sTable)).'<br />';
			}
			$msg=tr('coucheModeleGenereAvecSucces');
		}
		
		$oTpl= $this->getView('index');
		$oTpl->tTables=$tTables;
		$oTpl->tTableColumn=$tTableColumn;
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tConnexion=_root::getConfigVar('db');
		return $oTpl;
	}
	
	private function generate($sTable,$sConfig,$sId,$tSelect=null){

		$sFileModel='model_example.sample.php';
		if(_root::getConfigVar('db.'._root::getParam('sConfig').'.sgbd')=='mongodb'){
			$sFileModel='model_exampleMongodb.php';
		}

		/*SOURCE*/$oSourceModel=$this->getObjectSource($sFileModel);
		
		$r="\n";
		$t="\t";
		$sTable=trim($sTable);

		/*SOURCE*/$oSourceModel->setPattern('#maTable#',$sTable);
		/*SOURCE*/$oSourceModel->setPattern('#maTable_id#',$sId);
		/*SOURCE*/$oSourceModel->setPattern('#maConfig#',$sConfig);

		$sContentGetSelect=null;
		if(is_array($tSelect)){

			$sContentGetSelect=$oSourceModel->getSnippet(
							'methodGetSelect',
							array(
								'#key#'=>$tSelect['key'],
								'#val#'=>$tSelect['val']
								));
		}
		
		/*SOURCE*/$oSourceModel->setPattern('#modelMethods#',$sContentGetSelect);

		$srowCallSave='parent::save();';	
		$smodelSaveDuplicateKey=null;
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
			
			$smodelSaveDuplicateKey=$oSourceModel->getSnippet(
							'methodSave',
							array(
								'#oData#' => $soData, 
								'#implodetFieldSql#'=>implode(',',$tFieldSql),
								'#implodetSqlInsert#'=>implode(',',$tSqlInsert),
								'#implodetSqlUpdate#'=>implode(',',$tSqlUpdate),
								'#implodetParam#'=>implode(',',$tParam),
								));

			
			$srowCallSave='model_'.$sTable.'::getInstance()->save($this);';
		}
		/*SOURCE*/$oSourceModel->setPattern('#modelSaveDuplicateKey#',$smodelSaveDuplicateKey);
		/*SOURCE*/$oSourceModel->setPattern('#rowCallSave#',$srowCallSave);
		
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
		/*SOURCE*/$oSourceModel->setPattern('#rowMethodGetCheckContraints#',$sRules);
		
		/*SOURCE*/$oSourceModel->save();

		return true;
	}

	private function mongodbAddCollection(){
		$oModelMongo=new model_mkfbuilderfactory();
		$oModelMongo->setConfig(_root::getParam('sConfig'));
		
		$oModelMongo->getSgbd()->getDb()->createCollection(_root::getParam('collection'));
		
		_root::redirect('builder::edit',array('id'=>_root::getParam('id'),'action'=>_root::getParam('action'),'sConfig'=>_root::getParam('sConfig')));
	}
	
}
