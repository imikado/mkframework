<?php 
class module_group extends abstract_module{
	
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
		$oGroupModel=new model_group;
		$tGroup=$oGroupModel->findAll();
		
		$oView=new _view('group::list');
		$oView->tGroup=$tGroup;
		$oView->tColumn=$oGroupModel->getListColumn();//array('id','titre');//
		
		
		
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
		$oGroupModel=new model_group;
		$oGroup=new row_group;
		
		$oView=new _view('group::new');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		
		
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
		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( $id );
		
		$oView=new _view('group::edit');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		
		
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
		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( $id );
		
		$oView=new _view('group::show');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _show(){
		$oView=$this->getShow(_root::getParam('id'));
		$this->oLayout->add('main',$oView);
	}
	
	public function getDelete($id){
		$oGroupModel=new model_group;
		$oGroup=$oGroupModel->findById( $id );
		
		$oView=new _view('group::delete');
		$oView->oGroup=$oGroup;
		$oView->tColumn=$oGroupModel->getListColumn();
		$oView->tId=$oGroupModel->getIdTab();
		
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
	
		$oGroupModel=new model_group;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oGroup=new row_group;	
		}else{
			$oGroup=$oGroupModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oGroupModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) === null ) continue;
			if( in_array($sColumn,$oGroupModel->getIdTab())) continue;
			$oGroup->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oGroup->save()){
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('group::list');
		}else{
			return $oGroup->getListError();
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
	
		$oGroupModel=new model_group;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oGroup=$oGroupModel->findById( _root::getParam('id',null) );
		}
			
		$oGroup->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('group::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
