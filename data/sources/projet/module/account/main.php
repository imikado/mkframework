<?php 
class module_account extends abstract_module{
	
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
$oAccountModel=new model_account;
$tAccount=$oAccountModel->findAll();

$oView=new _view('account::list');
$oView->tAccount=$tAccount;
$oView->tColumn=$oAccountModel->getListColumn();//array('id','titre');//



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
		$oAccountModel=new model_account;
		$oAccount=new row_account;
		
		$oView=new _view('account::new');
		$oView->oAccount=$oAccount;
		$oView->tColumn=$oAccountModel->getListColumn();
		$oView->tId=$oAccountModel->getIdTab();
		
		
		
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
		$oAccountModel=new model_account;
		$oAccount=$oAccountModel->findById( $id );
		
		$oView=new _view('account::edit');
		$oView->oAccount=$oAccount;
		$oView->tColumn=$oAccountModel->getListColumn();
		$oView->tId=$oAccountModel->getIdTab();
		
		
		
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
		$oAccountModel=new model_account;
		$oAccount=$oAccountModel->findById( $id );
		
		$oView=new _view('account::show');
		$oView->oAccount=$oAccount;
		$oView->tColumn=$oAccountModel->getListColumn();
		$oView->tId=$oAccountModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _show(){
		$oView=$this->getShow(_root::getParam('id'));
		$this->oLayout->add('main',$oView);
	}
	
	public function getDelete($id){
		$oAccountModel=new model_account;
		$oAccount=$oAccountModel->findById( $id );
		
		$oView=new _view('account::delete');
		$oView->oAccount=$oAccount;
		$oView->tColumn=$oAccountModel->getListColumn();
		$oView->tId=$oAccountModel->getIdTab();
		
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
	
		$oAccountModel=new model_account;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oAccount=new row_account;	
		}else{
			$oAccount=$oAccountModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oAccountModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) ==null ) continue;
			if( in_array($sColumn,$oAccountModel->getIdTab())) continue;
			$oAccount->$sColumn=_root::getParam($sColumn,null) ;
		}

		$oAccount->pass=sha1(_root::getParam('pass'));
		
		if($oAccount->save()){
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('account::list');
		}else{
			return $oAccount->getListError();
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
	
		$oAccountModel=new model_account;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oAccount=$oAccountModel->findById( _root::getParam('id',null) );
		}
			
		$oAccount->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('account::list');
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
