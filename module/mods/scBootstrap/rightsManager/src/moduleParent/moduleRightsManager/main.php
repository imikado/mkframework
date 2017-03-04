<?php

class module_VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR extends module_VARmoduleParentENDVAR{

	protected $_sModulePath = 'VARmoduleParentENDVAR/VARmoduleAuthENDVAR';

	public function getView($sView_) {
		return new _view($this->_sModulePath . '::' . $sView_);
	}
	public function redirect($sAction_){
		_root::redirect($this->_sModulePath .'::'.$sAction_);
	}

	public function _index(){
		$this->_list();
	}

	public function _list(){
		$tPermission=VARmodel_rightManagerENDVAR::getInstance()->findAll();

		$oView=$this->getView('index');
		$oView->tPermission=$tPermission;

		$tUser=VARmodel_rightManagerENDVAR::getInstance()->findListUser();
		$oView->tUser=$tUser;

		$oView->tJoinGroup= VARmodel_rightManagerENDVAR::getInstance()->findSelectGroup();

		$this->oLayout->add('main',$oView);
	}

	public function _edit(){

		$tMessage=$this->processEdit();

		$oPermission=VARmodel_rightManagerENDVAR::getInstance()->findById(_root::getParam('id'));
		$oView=$oView=$this->getView('edit');
		$oView->oPermission=$oPermission;

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;

		$oView->tJoinGroup= VARmodel_rightManagerENDVAR::getInstance()->findSelectGroup();
		$oView->tJoinAction= VARmodel_rightManagerENDVAR::getInstance()->findSelectAction();
		$oView->tJoinItem= VARmodel_rightManagerENDVAR::getInstance()->findSelectItem();

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

		$oBusiness=new business_VARmoduleRightsManagerENDVAR(VARmodel_rightManagerENDVAR::getInstance(),_root::getACL(), _root::getI18n(), new plugin_sc_valid() );
		if(true === $oBusiness->updatePermission(_root::getParam('id'),_root::getRequest()->getParams() ) ){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('index');
		}else{
			return $oBusiness->getReturn()->getData('tError');
		}
	}

	public function _new(){

		$tMessage=$this->processNew();

		$oPermission=new VARrow_rightManagerENDVAR();
		$oView=$this->getView('new');
		$oView->oPermission=$oPermission;

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;

		$oView->tJoinGroup= VARmodel_rightManagerENDVAR::getInstance()->findSelectGroup();
		$oView->tJoinAction= VARmodel_rightManagerENDVAR::getInstance()->findSelectAction();
		$oView->tJoinItem= VARmodel_rightManagerENDVAR::getInstance()->findSelectItem();

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

		$oBusiness=new business_VARmoduleRightsManagerENDVAR(VARmodel_rightManagerENDVAR::getInstance(),_root::getACL(), _root::getI18n(), new plugin_sc_valid() );
		if(true === $oBusiness->insertPermission(new VARrow_rightManagerENDVAR(),_root::getRequest()->getParams() ) ){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('index');
		}else{
			return $oBusiness->getReturn()->getData('tError');
		}

	}


	public function _delete(){

		$tMessage=$this->processDelete();

		$oPermission=VARmodel_rightManagerENDVAR::getInstance()->findById(_root::getParam('id'));
		$oView=$oView=$this->getView('edit');
		$oView->oPermission=$oPermission;

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;

		$oView->tJoinGroup= VARmodel_rightManagerENDVAR::getInstance()->findSelectGroup();
		$oView->tJoinAction= VARmodel_rightManagerENDVAR::getInstance()->findSelectAction();
		$oView->tJoinItem= VARmodel_rightManagerENDVAR::getInstance()->findSelectItem();

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

		$oBusiness=new business_VARmoduleRightsManagerENDVAR(VARmodel_rightManagerENDVAR::getInstance(),_root::getACL(), _root::getI18n(), new plugin_sc_valid() );
		if(true === $oBusiness->deletePermission(_root::getParam('id')) ){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('index');
		}else{
			return $oBusiness->getReturn()->getData('tError');
		}

	}


	public function _editUser(){
		$tMessage=$this->processEditUser();

		$oUser=VARmodel_rightManagerENDVAR::getInstance()->findUserById(_root::getParam('id'));
		$oView=$this->getView('userEdit');
		$oView->oUser=$oUser;

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;

		$oView->tJoinGroup= VARmodel_rightManagerENDVAR::getInstance()->findSelectGroup();

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
		$group_id=_root::getParam('VARuser_fk_group_idENDVAR');

		VARmodel_rightManagerENDVAR::getInstance()->updateUserGroup( $user_id,$group_id);

		$this->redirect('index');
	}





}
