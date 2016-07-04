<?php
class module_mods_bootstrap_rightsManagerMulti extends abstract_moduleBuilder{

	protected $sModule='mods_bootstrap_rightsManagerMulti';
	protected $sModuleView='mods/bootstrap/rightsManagerMulti';

	private $msg=null;
	private $detail=null;
	private $tError=null;

	private $tField;
	
	public function init(){
		$this->tField=array(
			'classModelUser',
			'classModelGroup',
			'classModelGroupUser',
			'classModelPermission',
			'classModelAction',
			'classModelItem',
			
			'moduleToGenerate',
			'modelToGenerate',
			
			
		);
		
		$tFields=array(
			'classModelUser' => array('columnUser',array()),
			'classModelGroupUser' => array('columnUser',array('users_id','groups_id')),
			'classModelGroup' => array('columnGroup',array('id','name')),
			'classModelPermission' => array('columnPermission',array('groups_id','items_id','actions_id','id')),
			'classModelAction' => array('columnAction',array('id','name')),
			'classModelItem' => array('columnItem',array('id','name')),
		);
		
		foreach($tFields as $sKey => $tDetail){
			foreach($tDetail[1] as $sField){
				$this->tField[]=$sKey.'_'.$sField;
			}
		}
		
		
	}
	
	public function _index(){
		$this->init();

		$tMessage=$this->process();

		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');

		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array(null);
		$tColumn=array();
		$tRowMethodes=array();
		foreach($oDir->getListFile() as $oFile){
			if(preg_match('/.sample.php/',$oFile->getName()) or !preg_match('/.php$/',$oFile->getName())) continue;
			
			require_once( $oFile->getAdresse() );
			$sClass=substr($oFile->getName(),0,-4);
			$oModelFoo=new $sClass;
			
			$tFile[$sClass]=$oFile->getName();
			
			$tColumnFoo=module_builder::getTools()->getListColumnFromClass($sClass);
			$tColumnClass=array(null);
			foreach($tColumnFoo as $sField){
				$tColumnClass[ $sField ]=$sField;
			}
			$tColumn[$sClass]=$tColumnClass;
			
		}
		
		//formu
		$oForm=new stdclass;
		foreach($this->tField as $sField){
			$oForm->$sField=null;
		}
		$oForm->moduleToGenerate='rightsManagerMulti';
		$oForm->modelToGenerate='rightsManagerMulti';


		$oTpl= $this->getView('index');
		$oTpl->tModel=$tFile;
		$oTpl->tColumn=$tColumn;
		$oTpl->oForm=$oForm;
		$oTpl->tMessage=$tMessage;
		
		$oTpl->msg=$this->msg;
		$oTpl->detail=$this->detail;
		$oTpl->tError=$this->tError;
		
		return $oTpl;
	}
	private function process(){
		if(_root::getRequest()->isPost()==false or _root::getParam('actioncode')!='generate'){
			return null;
		}

		

		$tField=$this->tField;
	
		$oValid=new plugin_valid(_root::getRequest()->getParams());
		foreach($tField as $sField){
			$oValid->isLongerOrEqualThan($sField,2,'obligatoire');
		}
		
		if(!$oValid->isValid()){
			return $oValid->getListError();
		}
		
		
		//ok 
		$sClassModelRightsManager='model_'._root::getParam('modelToGenerate');
		$sClassRowRightsManager='row_'._root::getParam('modelToGenerate');
		$sObjectRightManger='o'.ucfirst(_root::getParam('modelToGenerate'));
		$sModuleRightsManager=_root::getParam('moduleToGenerate');
		
		//permissions
		$classModelPermission=_root::getParam('classModelPermission');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelPermission.'.php');
		$oPermission=new $classModelPermission;
		
		//actions
		$classModelAction=_root::getParam('classModelAction');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelAction.'.php');
		$oAction=new $classModelAction;
		
		//items
		$classModelItem=_root::getParam('classModelItem');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelItem.'.php');
		$oItem=new $classModelItem;
		
		//groups
		$classModelGroup=_root::getParam('classModelGroup');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelGroup.'.php');
		$oGroup=new $classModelGroup;
		
		//groupsUser
		$classModelGroupUser=_root::getParam('classModelGroupUser');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelGroupUser.'.php');
		$oGroupUser=new $classModelGroupUser;

		//users
		$classModelUser=_root::getParam('classModelUser');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelUser.'.php');
		$oUser=new $classModelUser;
		
		
		$exampleConfig=$oPermission->getConfig();
		$exampleTable=$oPermission->getTable();
				
		$exampleUserGroups_id=_root::getParam('classModelUser_groups_id');
		$exampleAction_name=_root::getParam('classModelAction_name');
		$exampleItem_name=_root::getParam('classModelItem_name');
		//exampleGroups_id
		//exampleAction_name
		//exampleItem_name
		
		$sPermissionTable=$oPermission->getTable();
			$sPermissionActionId=_root::getParam('classModelPermission_actions_id');
			$sPermissionItemId=_root::getParam('classModelPermission_items_id');
			$sPermissionGroupId=_root::getParam('classModelPermission_groups_id');
			$sPermissionId=_root::getParam('classModelPermission_id');
		
		$sActionTable=$oAction->getTable();
			$sActionId=_root::getParam('classModelAction_id');
			$sActionName=_root::getParam('classModelAction_name');
		
		$sItemTable=$oItem->getTable();
			$sItemId=_root::getParam('classModelItem_id');
			$sItemName=_root::getParam('classModelItem_name');
			
		$sGroupTable=$oGroup->getTable();
			$sGroupId=_root::getParam('classModelGroup_id');
			$sGroupName=_root::getParam('classModelGroup_name');

		$sGroupUserTable=$oGroupUser->getTable();
			$sGroupUserUserId=_root::getParam('classModelGroupUser_users_id');
			$sGroupUserGroupId=_root::getParam('classModelGroupUser_groups_id');
		
		$sUserTable=$oUser->getTable();	
			$sUserId=_root::getParam('classModelUser_id');
			$sUserGroupId=_root::getParam('classModelUser_groups_id');
			$sUserLogin=_root::getParam('classModelUser_login');
			
		
		$r="\n";
		$t="\t";


		
		//requete requeteFindListByUser
		/*SOURCE*/$oSourceModel=$this->getObjectSource('model_rightsManagerMulti.php');
		$exampleRequeteFindListByUser=$oSourceModel->getSnippet('requeteFindListByUser',array(
			'#sActionTable#'=>$sActionTable,
			'#sActionId#'=>$sActionId,
			'#sActionName#'=>$sActionName,

			'#sItemTable#'=>$sItemTable,
			'#sItemId#'=>$sItemId,
			'#sItemName#'=>$sItemName,

			'#sGroupUserTable#'=>$sGroupUserTable,
			'#sGroupUserGroupId#'=>$sGroupUserGroupId,
			'#sGroupUserUserId#'=>$sGroupUserUserId,

			'#sPermissionTable#'=>$sPermissionTable,
			'#sPermissionItemId#'=>$sPermissionItemId,
			'#sPermissionActionId#'=>$sPermissionActionId,
			'#sPermissionGroupId#'=>$sPermissionGroupId,
		));

		
		
		//requete findAll
		$exampleRequeteFindAll=$oSourceModel->getSnippet('exampleRequeteFindAll',array(
			'#sActionTable#'=>$sActionTable,
			'#sActionId#'=>$sActionId,
			'#sActionName#'=>$sActionName,

			'#sItemTable#'=>$sItemTable,
			'#sItemId#'=>$sItemId,
			'#sItemName#'=>$sItemName,

			'#sGroupTable#'=>$sGroupTable,
			'#sGroupId#'=>$sGroupId,
			'#sGroupName#'=>$sGroupName,
			

			'#sPermissionId#'=>$sPermissionId,
			'#sPermissionTable#'=>$sPermissionTable,
			'#sPermissionItemId#'=>$sPermissionItemId,
			'#sPermissionActionId#'=>$sPermissionActionId,
			'#sPermissionGroupId#'=>$sPermissionGroupId,
		));


		/*SOURCE*/$oSourceModel->setPattern('#model_examplemodel#',$sClassModelRightsManager);
		/*SOURCE*/$oSourceModel->setPattern('#row_examplemodel#',$sClassRowRightsManager);

		/*SOURCE*/$oSourceModel->setPattern('#exampleTable#',$exampleTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleConfig#',$exampleConfig);

		/*SOURCE*/$oSourceModel->setPattern('#requeteFindListByUser#',$exampleRequeteFindListByUser);
		/*SOURCE*/$oSourceModel->setPattern('#exampleRequeteFindAll#',$exampleRequeteFindAll);
		
		/*SOURCE*/$oSourceModel->setPattern('#examplePermission_id#',$sPermissionId);

		/*SOURCE*/$oSourceModel->setPattern('#exampleUserGroups_id#',$sUserGroupId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleAction_name#',$sActionName);
		/*SOURCE*/$oSourceModel->setPattern('#exampleItem_name#',$sItemName);

		/*SOURCE*/$oSourceModel->setPattern('#exampleGroupTable#',$sGroupTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleGroup_id#',$sGroupId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleGroup_name#',$sGroupName);

		/*SOURCE*/$oSourceModel->setPattern('#exampleActionTable#',$sActionTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleAction_id#',$sActionId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleAction_name#',$sActionName);

		/*SOURCE*/$oSourceModel->setPattern('#exampleItemTable#',$sItemTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleItem_id#',$sItemId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleItem_name#',$sItemName);

		/*SOURCE*/$oSourceModel->setPattern('#exampleUserTable#',$sUserTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleUser_id#',$sUserId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleUser_login#',$sUserLogin);
		/*SOURCE*/$oSourceModel->setPattern('#exampleUser_groupsId#',$sUserGroupId);

		/*SOURCE*/$oSourceModel->setPattern('#exampleGroupUserTable#',$sGroupUserTable);
		/*SOURCE*/$oSourceModel->setPattern('#exampleGroupUserGroupId#',$sGroupUserGroupId);
		/*SOURCE*/$oSourceModel->setPattern('#exampleGroupUserUserId#',$sGroupUserUserId);



		/*SOURCE*/$oSourceModel->setPattern('#sClassModelRightsManager#',$sClassModelRightsManager);
		/*SOURCE*/$bSave=$oSourceModel->save();

		if($bSave==false){
		  return array('error' => trR('fichierExisteDeja',array('#FICHIER#'=>'model/'.$sClassModelRightsManager.'.php')));
		}
		
		//--module main
		/*SOURCE*/$oSourceMain=$this->getObjectSource('example/main.php');
		/*SOURCE*/$oSourceMain->setPattern('#MODULE#',$sModuleRightsManager);

		$tReplace=array(
			'#examplemodule#' => $sModuleRightsManager,
			'#model_examplemodel#'=>$sClassModelRightsManager,
			'#row_examplemodel#'=>$sClassRowRightsManager,
			'#oExamplemodel#'=>$sObjectRightManger,
			
			'#examplePermissionId#' =>$sPermissionId,
			
			'#exampleGroupId#'=>$sPermissionGroupId,
			'#exampleActionId#'=>$sPermissionActionId,
			'#exampleItemId#'=>$sPermissionItemId,
			
			'#exampleUserTable#'=>$sUserTable,
			'#exampleUser_id#'=>$sUserId,
			'#exampleUser_login#'=>$sUserLogin,
			'#exampleUser_groupsId#'=>$sUserGroupId,
			
			'#exampleUserTable#'=>$sUserTable,
			'#exampleUser_id#'=>$sUserId,
			'#exampleUser_login#'=>$sUserLogin,
			'#exampleUser_groupsId#'=>$sUserGroupId,
			
		);

		/*SOURCE*/$oSourceMain->setListPattern($tReplace);
		/*SOURCE*/$bSave=$oSourceMain->save();

		if($bSave==false){
		  return array('error' => trR('fichierExisteDeja',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/main.php')));
		}

		$tView=array('index','new','edit','delete','userEdit');
		foreach($tView as $sView){

			//--view 
			/*SOURCE*/$oSourceView=$this->getObjectSource('example/view/'.$sView.'.php');
			/*SOURCE*/$oSourceView->setPattern('#MODULE#',$sModuleRightsManager);
			/*SOURCE*/$oSourceView->setListPattern($tReplace);
			/*SOURCE*/$bSave=$oSourceView->save();

			if($bSave==false){
			  return array('error' => trR('fichierExisteDeja',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/view/'.$sView.'.php')));
			}
		}

		//-----------
		
		
		$sMsg=null;
		$sDetail=null;
		
		$sMsg='Model '.$sClassModelRightsManager.' et Module '.$sModuleRightsManager.' g&eacute;n&eacute;r&eacute;s avec succ&egrave;s';
		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'model/'.$sClassModelRightsManager.'.php')).' <br/><br/>';
		
		$sDetail.=trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModuleRightsManager.'/')).'<br/>';
		$sDetail.=trR('creationRepertoire',array('#REPERTOIRE#'=>'Cr&eacute;ation du repertoire module/'.$sModuleRightsManager.'/view/')).' <br/>';

		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/main.php')).' <br/>';
		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/view/index.php')).' <br/>';
		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/view/new.php')).' <br/>';
		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/view/edit.php')).' <br/>';
		$sDetail.=trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleRightsManager.'/view/delete.php')).' <br/>';
				
		$sDetail.='<br/><br/>'.tr('accessibleVia').' <a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModuleRightsManager.'::index">index.php?:nav='.$sModuleRightsManager.'::index</a>';

		$sModuleAuth='auth';
		$tConfig=module_builder::getTools()->rootAddConf('conf/site.ini.php');
		if(isset($tConfig['auth']) and isset($tConfig['auth']['module'])){
			$sModuleAuthAndAction=$tConfig['auth']['module'];
			if(preg_match('/:/',$sModuleAuthAndAction)){
				list($sModuleAuth,$foo)=explode(':',$sModuleAuthAndAction);
			}
		}

		$r="\n";
		$t="\t";

		$sCode=null;		
		$sCode='<?php '."\n";
		$sCode.='private function checkLoginPass(){'.$r;
		$sCode.=$t.'(...)'.$r;
		
		$sCode.=$t.'$oUser=_root::getAuth()->getAccount();'.$r;
		$sCode.=$t.$sClassModelRightsManager.'::getInstance()->loadForUser($oUser);'.$r;
		$sCode.=$r;
		$sCode.=$t.'_root::redirect(\'privatemodule_action\');'.$r;
		$sCode.='}'.$r;
		
		$sCodeHighli=highlight_string($sCode,1);
		
		return array('msg' => $sMsg,'detail'=>$sDetail,'code'=>$sCodeHighli,'auth'=>$sModuleAuth);
		
		
	}

}