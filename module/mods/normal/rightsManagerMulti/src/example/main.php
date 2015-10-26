<?php
/*
examplemodule
* 
model_examplemodel
row_examplemodel
oExamplemodel
* 
exampleGroupId
exampleActionId
exampleItemId

exampleUser_groupsId
 * */
class module_#examplemodule# extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		//$this->oLayout->addModule('menu','menu::index');
	}
	
	public function _index(){
		$this->_list();
	}
	
	public function _list(){
		$tPermission=#model_examplemodel#::getInstance()->findAll();
		
		$oView=new _view('#examplemodule#::index');
		$oView->tPermission=$tPermission;
		
		$tUser=#model_examplemodel#::getInstance()->findListUser();
		$oView->tUser=$tUser;
		
		$oView->tJoinGroup= #model_examplemodel#::getInstance()->findSelectGroup();
		
		$this->oLayout->add('main',$oView);
	}
	
	public function _edit(){
		
		$tMessage=$this->processEdit();
		
		$oPermission=#model_examplemodel#::getInstance()->findById(_root::getParam('id'));
		$oView=new _view('#examplemodule#::edit');
		$oView->oPermission=$oPermission;
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$oView->tJoinGroup= #model_examplemodel#::getInstance()->findSelectGroup();
		$oView->tJoinAction= #model_examplemodel#::getInstance()->findSelectAction();
		$oView->tJoinItem= #model_examplemodel#::getInstance()->findSelectItem();
		
		$this->oLayout->add('main',$oView);
	}
	private function processEdit(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
	
		$#oExamplemodel#=#model_examplemodel#::getInstance()->findById(_root::getParam('id'));
		
		$sGroupText=trim(_root::getParam('#exampleGroupId#_text'));
		$sActionText=trim(_root::getParam('#exampleActionId#_text'));
		$sItemText=trim(_root::getParam('#exampleItemId#_text'));
		
		$tColumn=array('#exampleGroupId#','#exampleActionId#','#exampleItemId#');
		foreach($tColumn as $sColumn){
			$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($#oExamplemodel#->save()){
			//une fois enregistre on redirige (vers la page liste)
			_root::redirect('#examplemodule#::index');
		}else{
			return $#oExamplemodel#->getListError();
		}
	}
	
	public function _new(){
		
		$tMessage=$this->processNew();
		
		$oPermission=new #row_examplemodel#();
		$oView=new _view('#examplemodule#::new');
		$oView->oPermission=$oPermission;
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$oView->tJoinGroup= #model_examplemodel#::getInstance()->findSelectGroup();
		$oView->tJoinAction= #model_examplemodel#::getInstance()->findSelectAction();
		$oView->tJoinItem= #model_examplemodel#::getInstance()->findSelectItem();
		
		$this->oLayout->add('main',$oView);
	}
	private function processNew(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
	
		$#oExamplemodel#=new #row_examplemodel#;
		
		$sGroupText=trim(_root::getParam('#exampleGroupId#_text'));
		$sActionText=trim(_root::getParam('#exampleActionId#_text'));
		$sItemText=trim(_root::getParam('#exampleItemId#_text'));
		
		$tColumn=array('#exampleGroupId#','#exampleActionId#','#exampleItemId#');
		foreach($tColumn as $sColumn){
			$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($sGroupText!=''){
			$oGroup=#model_examplemodel#::getInstance()->findGroupByName($sGroupText);
			if(!$oGroup){
				#model_examplemodel#::getInstance()->insertGroup($sGroupText);
				$oGroup=#model_examplemodel#::getInstance()->findGroupByName($sGroupText);
			}
			$#oExamplemodel#->#exampleGroupId#=$oGroup->id;
		}
		if($sActionText!=''){
			$oAction=#model_examplemodel#::getInstance()->findActionByName($sActionText);
			if(!$oAction){
				#model_examplemodel#::getInstance()->insertAction($sActionText);
				$oAction=#model_examplemodel#::getInstance()->findActionByName($sActionText);
			}
			$#oExamplemodel#->#exampleActionId#=$oAction->id;
		}
		if($sItemText!=''){
			$oItem=#model_examplemodel#::getInstance()->findItemByName($sItemText);
			if(!$oItem){
				#model_examplemodel#::getInstance()->insertItem($sItemText);
				$oItem=#model_examplemodel#::getInstance()->findItemByName($sItemText);
			}
			$#oExamplemodel#->#exampleItemId#=$oItem->id;
		}
		
		if($#oExamplemodel#->save()){
			//une fois enregistre on redirige (vers la page liste)
			_root::redirect('#examplemodule#::index');
		}else{
			return $#oExamplemodel#->getListError();
		}
	}
	
	
	public function _delete(){
		
		$tMessage=$this->processDelete();
		
		$oPermission=#model_examplemodel#::getInstance()->findById(_root::getParam('id'));
		$oView=new _view('#examplemodule#::delete');
		$oView->oPermission=$oPermission;
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$oView->tJoinGroup= #model_examplemodel#::getInstance()->findSelectGroup();
		$oView->tJoinAction= #model_examplemodel#::getInstance()->findSelectAction();
		$oView->tJoinItem= #model_examplemodel#::getInstance()->findSelectItem();
		
		$this->oLayout->add('main',$oView);
	}
	private function processDelete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
	
		$#oExamplemodel#=#model_examplemodel#::getInstance()->findById(_root::getParam('id'));
		
		$#oExamplemodel#->delete();
		_root::redirect('#examplemodule#::index');
	
	}
	
	
	public function _editUser(){
		$tMessage=$this->processEditUser();
		
		$oUser=#model_examplemodel#::getInstance()->findUserById(_root::getParam('id'));
		$oView=new _view('#examplemodule#::userEdit');
		$oView->oUser=$oUser;
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		$oView->tGroup=#model_examplemodel#::getInstance()->findListGroupByUser(_root::getParam('id'));
		
		$oView->tJoinGroup= #model_examplemodel#::getInstance()->findSelectGroup();
		
		$this->oLayout->add('main',$oView);
		
	}
	private function processEditUser(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
		
		$user_id=_root::getParam('id');
		$tGroup=_root::getParam('#exampleGroupId#');
		
		#model_examplemodel#::getInstance()->updateUserGroup( $user_id,$tGroup);
		
		_root::redirect('#examplemodule#::index');
	}
	
	
	
	public function after(){
		$this->oLayout->show();
	}
	
	
	
}
