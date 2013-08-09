<?php
class module_prive extends abstract_module{
	
	public function before(){
		_root::getAuth()->enable();
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function before_list(){
		
		
	}
	public function _list(){
		
		$oView=new _view('prive::list');
		
		$tArticle=model_article::getInstance()->findAll();
		$oView->tArticle=$tArticle;
				
		$this->oLayout->add('main',$oView);
	}
	
	public function _new(){
		
		if(!_root::getACL()->can('add','article') ){
			//on verifie que l'utilisateur a le droit d'acceder a cette page
			_root::redirect('prive::list'); 
		}

		$tMessage=$this->save();
	
		$oArticleModel=new model_article;
		$oArticle=new row_article;
		
		$oView=new _view('prive::new');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
	public function _edit(){

		if(!_root::getACL()->can('edit','article') ){
			//on verifie que l'utilisateur a le droit d'acceder a cette page
			_root::redirect('prive::list'); 
		}
		
		$tMessage=$this->save();
	
		$oArticleModel=new model_article;
		$oArticle=$oArticleModel->findById( _root::getParam('id') );
		
		$oView=new _view('prive::edit');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	private function save(){
		
		if(!_root::getRequest()->isPost() ){
			return false;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oArticleModel=new model_article;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oArticle=new row_article;	
		}else{
			$oArticle=$oArticleModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oArticleModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) ==null ) continue;
			if( in_array($sColumn,$oArticleModel->getIdTab())) continue;
			$oArticle->$sColumn=_root::getParam($sColumn,null) ;
		}
		if($oArticle->save()){
			//une fois enregistre on redirige (vers la page de liste)
			_root::redirect('prive::list');
		}else{
			return $oArticle->getListError();
		}
	}

}
