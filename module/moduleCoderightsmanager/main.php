<?php
class module_moduleCoderightsmanager{
	
	private $tField;
	
	public function __construct(){
		$this->tField=array(
			'classModelUser',
			'classModelGroup',
			'classModelPermission',
			'classModelAction',
			'classModelItem',
			
			'moduleToGenerate',
			'modelToGenerate',
			
			
		);
		
		$tFields=array(
			'classModelUser' => array('columnUser',array('groups_id')),
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
		$tMessage=$this->processIndex();
		
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
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
		$oForm->moduleToGenerate='rightsManager';
		$oForm->modelToGenerate='rightsManager';
		
		
		$oView=new _tpl('moduleCoderightsmanager::index');
		$oView->tModel=$tFile;
		$oView->tColumn=$tColumn;
		$oView->oForm=$oForm;
		$oView->tMessage=$tMessage;
		
		return $oView;
		
	}
	private function processIndex(){
		if(!_root::getRequest()->isPost() or _root::getParam('actioncode')!='generate'){
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
		
		//users
		$classModelUser=_root::getParam('classModelUser');
		include_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$classModelUser.'.php');
		$oUser=new $classModelUser;
		
		
		$exampleConfig=$oPermission->getConfig();
		$exampleTable=$oPermission->getTable();
		
		$exampleRequeteFindListByGroup=null;
		
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
		
		$sUserTable=$oUser->getTable();	
			$sUserId=_root::getParam('classModelUser_id');
			$sUserGroupId=_root::getParam('classModelUser_groups_id');
			$sUserLogin=_root::getParam('classModelUser_login');
			
		
		$r="\n";
		$t="\t";
		
		//requete findByGroupId
		$sRequest=$r;
		$sRequest.=$t.$t.'SELECT '.$r;
			$sRequest.=$t.$t.$t.''.$sActionTable.'.'.$sActionName.' as actionName';
			$sRequest.=' , '.$sItemTable.'.'.$sItemName.' as itemName'.$r;
		$sRequest.=$t.$t.'FROM '.$sPermissionTable.''.$r;
			$sRequest.=$t.$t.$t.'INNER JOIN '.$sActionTable.''.$r;
				$sRequest.=$t.$t.$t.$t.'ON '.$sActionTable.'.'.$sActionId.'='.$sPermissionTable.'.'.$sPermissionActionId.$r;
			$sRequest.=$t.$t.$t.'INNER JOIN '.$sItemTable.''.$r;
				$sRequest.=$t.$t.$t.$t.' ON '.$sItemTable.'.'.$sItemId.'='.$sPermissionTable.'.'.$sPermissionItemId.$r;
		$sRequest.=$t.$t.'WHERE '.$sPermissionTable.'.'.$sPermissionGroupId.'=?';
		
		$exampleRequeteFindListByGroup=$sRequest;
		
		//requete findAll
		$sRequest=$r;
		$sRequest.=$t.$t.'SELECT '.$r;
			$sRequest.=$t.$t.$t.''.$sActionTable.'.'.$sActionName.' as actionName';
			$sRequest.=' , '.$sItemTable.'.'.$sItemName.' as itemName'.$r;
			$sRequest.=' , '.$sGroupTable.'.'.$sGroupName.' as groupName'.$r;
			$sRequest.=' , '.$sPermissionTable.'.'.$sPermissionId.$r;
			
		$sRequest.=$t.$t.'FROM '.$sPermissionTable.''.$r;
		
			$sRequest.=$t.$t.$t.'INNER JOIN '.$sActionTable.''.$r;
				$sRequest.=$t.$t.$t.$t.'ON '.$sActionTable.'.'.$sActionId.'='.$sPermissionTable.'.'.$sPermissionActionId.$r;
			$sRequest.=$t.$t.$t.'INNER JOIN '.$sItemTable.''.$r;
				$sRequest.=$t.$t.$t.$t.' ON '.$sItemTable.'.'.$sItemId.'='.$sPermissionTable.'.'.$sPermissionItemId.$r;
			$sRequest.=$t.$t.$t.'INNER JOIN '.$sGroupTable.''.$r;
				$sRequest.=$t.$t.$t.$t.' ON '.$sGroupTable.'.'.$sGroupId.'='.$sPermissionTable.'.'.$sPermissionGroupId.$r;
		
		$exampleRequeteFindAll=$sRequest;
		
		//---model
		$sContentModel=module_builder::getTools()->stringReplaceIn(array(
											'exampleTable' => $exampleTable,
											'exampleConfig' => $exampleConfig,
											'exampleRequeteFindListByGroup'=>$exampleRequeteFindListByGroup,
											'exampleRequeteFindAll'=>$exampleRequeteFindAll,
											
											'examplePermission_id' => $sPermissionId,
											
											'exampleUserGroups_id'=>$sUserGroupId,
											'exampleAction_name'=>$sActionName,
											'exampleItem_name'=>$sItemName,
											
											'exampleGroupTable'=>$sGroupTable,
											'exampleGroup_id'=>$sGroupId,
											'exampleGroup_name'=>$sGroupName,
											
											'exampleActionTable'=>$sActionTable,
											'exampleAction_id'=>$sActionId,
											'exampleAction_name'=>$sActionName,
											
											'exampleItemTable'=>$sItemTable,
											'exampleItem_id'=>$sItemId,
											'exampleItem_name'=>$sItemName,
											
											'exampleUserTable'=>$sUserTable,
											'exampleUser_id'=>$sUserId,
											'exampleUser_login'=>$sUserLogin,
											'exampleUser_groupsId'=>$sUserGroupId,
											
										),
										'data/sources/fichiers/model/model_rightsManager.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClassModelRightsManager.'.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier model/'.$sClassModelRightsManager.'.php exite deja');
		}
		
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		
		$tReplace=array(
			'examplemodule' => $sModuleRightsManager,
			'model_examplemodel'=>$sClassModelRightsManager,
			'row_examplemodel'=>$sClassRowRightsManager,
			
			'examplePermissionId' =>$sPermissionId,
			
			'exampleGroupId'=>$sPermissionGroupId,
			'exampleActionId'=>$sPermissionActionId,
			'exampleItemId'=>$sPermissionItemId,
			
			'exampleUserTable'=>$sUserTable,
			'exampleUser_id'=>$sUserId,
			'exampleUser_login'=>$sUserLogin,
			'exampleUser_groupsId'=>$sUserGroupId,
			
			'exampleUserTable'=>$sUserTable,
			'exampleUser_id'=>$sUserId,
			'exampleUser_login'=>$sUserLogin,
			'exampleUser_groupsId'=>$sUserGroupId,
			
		);
		
		//-module
		//---main
		module_builder::getTools()->projetmkdir('module/'.$sModuleRightsManager );
		module_builder::getTools()->projetmkdir('module/'.$sModuleRightsManager.'/view');
		
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/main.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/main.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/main.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//--view index
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/view/index.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/view/index.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/index.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//--view new
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/view/new.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/view/new.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/new.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//--view edit
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/view/edit.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/view/edit.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/edit.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//--view delete
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/view/delete.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/view/delete.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/delete.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//--view editUser
		$sContentModel=module_builder::getTools()->stringReplaceIn($tReplace,
										'data/sources/fichiers/module/rightsManager/view/userEdit.php'
		);
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleRightsManager.'/view/userEdit.php' );
		
		if($oFile->exist()){
		  return array('error' => 'Fichier module/'.$sModuleRightsManager.'/view/userEdit.php exite deja');
		}
		$oFile->setContent($sContentModel);
		$oFile->save();
		$oFile->chmod(0666);
		
		//-----------
		
		
		$sMsg=null;
		$sDetail=null;
		
		$sMsg='Model '.$sClassModelRightsManager.' et Module '.$sModuleRightsManager.' g&eacute;n&eacute;r&eacute;s avec succ&egrave;s';
		$sDetail.='Cr&eacute;ation fichier model/'.$sClassModelRightsManager.'.php <br/><br/>';
		
		$sDetail.='Cr&eacute;ation du repertoire module/'.$sModuleRightsManager.'/ <br/>';
		$sDetail.='Cr&eacute;ation du repertoire module/'.$sModuleRightsManager.'/view/ <br/>';

		$sDetail.='Cr&eacute;ation fichier module/'.$sModuleRightsManager.'/main.php <br/>';
		$sDetail.='Cr&eacute;ation fichier module/'.$sModuleRightsManager.'/view/index.php <br/>';
		$sDetail.='Cr&eacute;ation fichier module/'.$sModuleRightsManager.'/view/new.php <br/>';
		$sDetail.='Cr&eacute;ation fichier module/'.$sModuleRightsManager.'/view/edit.php <br/>';
		$sDetail.='Cr&eacute;ation fichier module/'.$sModuleRightsManager.'/view/delete.php <br/>';
				
		$sDetail.='<br/><br/>Pour y acc&eacute;der <a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModuleRightsManager.'::index">cliquer ici (index.php?:nav='.$sModuleRightsManager.'::index)</a>';

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



