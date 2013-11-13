<?php 
class module_examplemodule extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		//$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	//iciMethodList
	
	//iciMethodNew
	
	//iciMethodEdit
	
	//iciMethodShow
	
	//iciMethodDelete
	
	
	private function fillRow($oExamplemodel){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return $oExamplemodel;
		}
		
		$tId=model_examplemodel::getInstance()->getIdTab();
		$tColumn=model_examplemodel::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oExamplemodel->$sColumn=_root::getParam($sColumn,null) ;
		}
		return $oExamplemodel;
	}

	private function processSave(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oExamplemodel=new row_examplemodel;	
		}else{
			$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id',null) );
		}
		
		$tId=model_examplemodel::getInstance()->getIdTab();
		$tColumn=model_examplemodel::getInstance()->getListColumn();
		foreach($tColumn as $sColumn){
			//iciuploadsave if( _root::getParam($sColumn,null) === null ){ 
				continue;
			}else if( in_array($sColumn,$tId)){
				 continue;
			}
			
			$oExamplemodel->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oExamplemodel->save()){
			//une fois enregistre on redirige (vers la page liste)
			_root::redirect('examplemodule::list');
		}else{
			return $oExamplemodel->getListError();
		}
		
	}
	
	//iciMethodProcessDelete

	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

/*variables
#select		$oView->tJoinexamplemodel=examplemodel::getInstance()->getSelect();#fin_select
#uploadsave $oPluginUpload=new plugin_upload($sColumn);
			if($oPluginUpload->isValid()){
				$sNewFileName=_root::getConfigVar('path.upload').$sColumn.'_'.date('Ymdhis');

				$oPluginUpload->saveAs($sNewFileName);
				$oExamplemodel->$sColumn=$oPluginUpload->getPath();
				continue;	
			}else #fin_uploadsave

#methodList
	public function _list(){
		
		$tExamplemodel=model_examplemodel::getInstance()->findAll();
		
		$oView=new _view('examplemodule::list');
		$oView->tExamplemodel=$tExamplemodel;
		
		//icilist
		
		$this->oLayout->add('main',$oView);
		 
	}
methodList#

#methodPaginationList
	public function _list(){
		
		$tExamplemodel=model_examplemodel::getInstance()->findAll();
		
		$oView=new _view('examplemodule::list');
		$oView->tExamplemodel=$tExamplemodel;
		
		//icilist
		
		$oModulePagination=new module_pagination;
		$oModulePagination->setModuleAction('examplemodule::list');
		$oModulePagination->setParamPage('page');
		$oModulePagination->setLimit(5);
		$oModulePagination->setPage( _root::getParam('page') );
		$oModulePagination->setTab( $tExamplemodel );
		
		$oView->tExamplemodel=$oModulePagination->getPageElement();
		
		$this->oLayout->add('main',$oView);
		
		
		$oViewPagination=$oModulePagination->build();
		
		$this->oLayout->add('main',$oViewPagination);
		 
	}
methodPaginationList#
			
#methodNew
	public function _new(){
		$tMessage=$this->processSave();
	
		$oExamplemodel=new row_examplemodel;
		$oExamplemodel=$this->fillRow($oExamplemodel);
		
		$oView=new _view('examplemodule::new');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icinew
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
methodNew#
	
#methodEdit
	public function _edit(){
		$tMessage=$this->processSave();
		
		$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id') );
		$oExamplemodel=$this->fillRow($oExamplemodel);
		
		$oView=new _view('examplemodule::edit');
		$oView->oExamplemodel=$oExamplemodel;
		$oView->tId=model_examplemodel::getInstance()->getIdTab();
		
		//iciedit
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
methodEdit#

#methodShow
	public function _show(){
		$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id') );
		
		$oView=new _view('examplemodule::show');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icishow
		$this->oLayout->add('main',$oView);
	}
methodShow#
	
#methodDelete
	public function _delete(){
		$tMessage=$this->processDelete();

		$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id') );
		
		$oView=new _view('examplemodule::delete');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icishow

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
methodDelete#	

#methodProcessDelete
	public function processDelete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id',null) );
				
		$oExamplemodel->delete();
		//une fois enregistre on redirige (vers la page liste)
		_root::redirect('examplemodule::list');
		
	}
methodProcessDelete#	
			
variables*/

