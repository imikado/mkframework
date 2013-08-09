<?php 
class module_auteur extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function getList(){
		$oAuteurModel=new model_auteur;
		$tAuteur=$oAuteurModel->findAll();
		
		$oView=new _view('auteur::list');
		$oView->tAuteur=$tAuteur;
		$oView->tColumn=$oAuteurModel->getListColumn();//array('id','titre');//
		
		
		
		return $oView;
	}
	public function _list(){
		$oView=$this->getList();
		$this->oLayout->add('main',$oView);
	}
	
	public function getNew(){
		$oAuteurModel=new model_auteur;
		$oAuteur=new row_auteur;
		
		$oView=new _view('auteur::new');
		$oView->oAuteur=$oAuteur;
		$oView->tColumn=$oAuteurModel->getListColumn();
		$oView->tId=$oAuteurModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _new(){
		$oView=$this->getNew();
		$this->oLayout->add('main',$oView);
	}
	
	public function getEdit($id){
		$oAuteurModel=new model_auteur;
		$oAuteur=$oAuteurModel->findById( $id );
		
		$oView=new _view('auteur::edit');
		$oView->oAuteur=$oAuteur;
		$oView->tColumn=$oAuteurModel->getListColumn();
		$oView->tId=$oAuteurModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _edit(){
		$oView=$this->getEdit(_root::getParam('id'));
		$this->oLayout->add('main',$oView);
	}
	
	public function getShow($id){
		$oAuteurModel=new model_auteur;
		$oAuteur=$oAuteurModel->findById( $id );
		
		$oView=new _view('auteur::show');
		$oView->oAuteur=$oAuteur;
		$oView->tColumn=$oAuteurModel->getListColumn();
		$oView->tId=$oAuteurModel->getIdTab();
		
		
		
		return $oView;
	}
	public function _show(){
		$this->getShow(_root::getParam('id'));
		$this->oLayout->add('main',$oView);
	}
	
	public function _save(){
		$oAuteurModel=new model_auteur;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oAuteur=new row_auteur;	
		}else{
			$oAuteur=$oAuteurModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oAuteurModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) === null ) continue;
			if( in_array($sColumn,$oAuteurModel->getIdTab())) continue;
			$oAuteur->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		$oAuteur->save();
		_root::redirect('auteur::edit',array('id'=>$oAuteur->getId()));
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
