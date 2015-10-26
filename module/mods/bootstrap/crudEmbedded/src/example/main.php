<?php 
class module_#MODULE# extends abstract_moduleembedded{
	
	public static $sModuleName='#MODULE#';
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
	$oModule#MODULE#=new module_#MODULE#();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModule#MODULE#->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModule#MODULE#->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/

	public function _index(){
		$sAction='_'.self::getParam('Action','list');
		return $this->$sAction();
	}
	
	#iciMethodList#
	
	#iciMethodNew#
	
	#iciMethodEdit#
	
	#iciMethodShow#
	
	#iciMethodDelete#
	

	private function processSave(){
		if(!_root::getRequest()->isPost() or _root::getParam('formmodule')!=self::$sModuleName ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new plugin_xsrf();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$iId=module_#examplemodule#::getParam('id',null);
		if($iId==null){
			$#oExamplemodel#=new row_#examplemodel#;	
		}else{
			$#oExamplemodel#=model_#examplemodel#::getInstance()->findById( module_#examplemodule#::getParam('id',null) );
		}
		
		$tColumn=#icitColumn#
		foreach($tColumn as $sColumn){
			$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
		}
		#iciUpload#
		
		if($#oExamplemodel#->save()){
			//une fois enregistre on redirige (vers la page liste)
			$this->redirect('list');
		}else{
			return $#oExamplemodel#->getListError();
		}
		
	}
	
	#iciMethodProcessDelete#
	
	
	
	
}