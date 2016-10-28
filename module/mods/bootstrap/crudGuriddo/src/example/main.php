<?php
class module_#MODULE# extends abstract_module{

	public function before(){
		$this->oLayout=new _layout('bootstrap');

		//$this->oLayout->addModule('menu','menu::index');
	}


	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}

	#iciMethodList#

	#iciMethodNew#

	#iciMethodEdit#

	#iciMethodShow#

	#iciMethodDelete#

	#iciMethodJsonList#


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
			$#oExamplemodel#=new row_#examplemodel#;
		}else{
			$#oExamplemodel#=model_#examplemodel#::getInstance()->findById( _root::getParam('id',null) );
		}

		$tColumn=#icitColumn#
		foreach($tColumn as $sColumn){
			$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
		}
		#iciUpload#

		if($#oExamplemodel#->save()){
			//une fois enregistre on redirige (vers la page liste)
			_root::redirect('#examplemodule#::list');
		}else{
			return $#oExamplemodel#->getListError();
		}

	}

	public function _postJson(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		if(_root::getParam('oper')=='del'){
			return $this->processDelete();
		}else if(_root::getParam('oper')=='edit'){
			$iId=_root::getParam('id',null);
			$#oExamplemodel#=model_#examplemodel#::getInstance()->findById( $iId );
		}else if(_root::getParam('oper')=='add'){
			$#oExamplemodel#=new row_#examplemodel#;
		}

		$tColumn=#icitColumn#
		foreach($tColumn as $sColumn){
			$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
		}
		#iciUpload#

		if($#oExamplemodel#->save()){
			//une fois enregistre on redirige (vers la page liste)

		}else{
		      header('Content-Type: application/json');

		      $tListError=$#oExamplemodel#->getListError();

		      $tError=array();
		      foreach($tListError as $key => $line){
			$tError[]='<p>'.$key.':'.implode('',$line).'</p>';
		      }

		      echo json_encode( array('error'=> implode('',$tError)) );
		      exit;
		}
	}

	#iciMethodProcessDelete#

	public function after(){
		$this->oLayout->show();
	}


}

