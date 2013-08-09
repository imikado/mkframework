<?php 
class module_permission extends abstract_module{
	
	public function before(){
		_root::getAuth()->enable();
		//on check les permissions		
		if(!_root::getACL()->can('edit','acl') ){
			_root::redirect('prive::list');
		}
		
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function getList(){
		$oPermissionModel=new model_permission;
		$tPermission=$oPermissionModel->findAll();
		
		$oView=new _view('permission::list');
		$oView->tPermission=$tPermission;
		$oView->tColumn=$oPermissionModel->getListColumn();//array('id','titre');//
		
		
		
		return $oView;
	}
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	public function _list(){
		$oView=$this->getList();
		$this->oLayout->add('main',$oView);
	}
	
	public function getNew(){
		$oPermissionModel=new model_permission;
		$oPermission=new row_permission;
		
		$oView=new _view('permission::new');
		$oView->oPermission=$oPermission;
		$oView->tColumn=$oPermissionModel->getListColumn();
		$oView->tId=$oPermissionModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _new(){
		$tMessage=$this->save();
	
		$oView=$this->getNew();
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function getEdit($id){
		$oPermissionModel=new model_permission;
		$oPermission=$oPermissionModel->findById( $id );
		
		$oView=new _view('permission::edit');
		$oView->oPermission=$oPermission;
		$oView->tColumn=$oPermissionModel->getListColumn();
		$oView->tId=$oPermissionModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _edit(){
		$tMessage=$this->save();
		
		$oView=$this->getEdit(_root::getParam('id'));
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}

	public function getShow($id){
		$oPermissionModel=new model_permission;
		$oPermission=$oPermissionModel->findById( $id );
		
		$oView=new _view('permission::show');
		$oView->oPermission=$oPermission;
		$oView->tColumn=$oPermissionModel->getListColumn();
		$oView->tId=$oPermissionModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _show(){
		$oView=$this->getShow(_root::getParam('id'));
		$this->oLayout->add('main',$oView);
	}
	
	public function getDelete($id){
		$oPermissionModel=new model_permission;
		$oPermission=$oPermissionModel->findById( $id );
		
		$oView=new _view('permission::delete');
		$oView->oPermission=$oPermission;
		$oView->tColumn=$oPermissionModel->getListColumn();
		$oView->tId=$oPermissionModel->getIdTab();
		
		//
		
		return $oView;
	}
	public function _delete(){
		$tMessage=$this->delete();
		
		$oView=$this->getDelete(_root::getParam('id'));
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}

	public function save(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oPermissionModel=new model_permission;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oPermission=new row_permission;	
		}else{
			$oPermission=$oPermissionModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oPermissionModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) === null ) continue;
			if( in_array($sColumn,$oPermissionModel->getIdTab())) continue;
			$oPermission->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oPermission->save()){
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('permission::list');
		}else{
			return $oPermission->getListError();
		}
		
	}

	public function delete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oPermissionModel=new model_permission;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oPermission=$oPermissionModel->findById( _root::getParam('id',null) );
		}
			
		$oPermission->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('permission::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
