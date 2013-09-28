<?php 
class module_examplemodule extends abstract_moduleembedded{
	
	public static $sModuleName='examplemodule';
	public static $sRootModule;
	public static $tRootParams;
	
	public function __construct(){
		self::setRootLink(_root::getParamNav(),null);
	}
	public static function setRootLink($sRootModule,$tRootParams=null){
		self::$sRootModule=$sRootModule;
		self::$tRootParams=$tRootParams;
	}
	public static function getLink($sAction,$tParam=null){
		return parent::_getLink(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sAction,$tParam);
	}
	public static function getParam($sVar,$uDefault=null){
		return parent::_getParam(self::$sModuleName,$sVar,$uDefault);
	}
	public static function redirect($sModuleAction,$tModuleParam=null){
		return parent::_redirect(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sModuleAction,$tModuleParam);
	}
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_examplemodule();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleExamplemodule->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleExamplemodule->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	
	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	public function _list(){
		
		$tExamplemodel=model_examplemodel::getInstance()->findAll();
		
		$oView=new _view('examplemodule::list');
		$oView->tExamplemodel=$tExamplemodel;
		
		//icilist

		return $oView;
	}
	
	//iciMethodNew
	
	//iciMethodEdit
	
	//iciMethodShow
	
	//iciMethodDelete
	

	

	public function processSave(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$iId=module_examplemodule::getParam('id',null);
		if($iId==null){
			$oExamplemodel=new row_examplemodel;	
		}else{
			$oExamplemodel=model_examplemodel::getInstance()->findById( module_examplemodule::getParam('id',null) );
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
			$this->redirect('list');
		}else{
			return $oExamplemodel->getListError();
		}
		
	}

	
	//iciMethodProcessDelete
	
	
	
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


#methodNew
	public function _new(){
		$tMessage=$this->processSave();
	
		$oExamplemodel=new row_examplemodel;
		
		$oView=new _view('examplemodule::new');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icinew
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodNew#
	
#methodEdit
	public function _edit(){
		$tMessage=$this->processSave();
		
		$oExamplemodel=model_examplemodel::getInstance()->findById( module_examplemodule::getParam('id') );
		
		$oView=new _view('examplemodule::edit');
		$oView->oExamplemodel=$oExamplemodel;
		$oView->tId=model_examplemodel::getInstance()->getIdTab();
		
		//iciedit
		
		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodEdit#

#methodShow
	public function _show(){
		$oExamplemodel=model_examplemodel::getInstance()->findById( module_examplemodule::getParam('id') );
		
		$oView=new _view('examplemodule::show');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icishow
		return $oView;
	}
methodShow#

#methodDelete	
	public function _delete(){
		$tMessage=$this->processDelete();

		$oExamplemodel=model_examplemodel::getInstance()->findById( module_examplemodule::getParam('id') );
		
		$oView=new _view('examplemodule::delete');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icishow

		$oPluginXsrf=new plugin_xsrf();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		return $oView;
	}
methodDelete#

#methodProcessDelete
	public function processDelete(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oExamplemodel=model_examplemodel::getInstance()->findById( module_examplemodule::getParam('id',null) );
				
		$oExamplemodel->delete();
		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
		
	}
methodProcessDelete#

			
variables*/

