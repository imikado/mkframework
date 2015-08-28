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
	
	//iciMethodJsonList
	
	public function _postJson(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		if(_root::getParam('oper')=='del'){
			return $this->processDelete();
		}else if(_root::getParam('oper')=='edit'){
			$iId=_root::getParam('id',null);
			$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id',null) );
		}else if(_root::getParam('oper')=='add'){
			$oExamplemodel=new row_examplemodel;
		}
		
		$tColumn=//icitColumn
		foreach($tColumn as $sColumn){
			$oExamplemodel->$sColumn=_root::getParam($sColumn,null) ;
		}
		//iciUpload
		
		if($oExamplemodel->save()){
			//une fois enregistre on redirige (vers la page liste)
			 
		}else{
			return $oExamplemodel->getListError();
		}
	}

	//iciMethodProcessDelete

	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

