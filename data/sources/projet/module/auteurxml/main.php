<?php 
class module_auteurxml extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function _list(){
		$oAuteurModel=new model_auteur;
		$tAuteur=$oAuteurModel->findAll();
		
		$oView=new _view('auteurxml::list');
		$oView->tAuteur=$tAuteur;
		$oView->tColumn=array('id','titre');//
		
		$this->oLayout->add('main',$oView);
	}
	
	public function _showXml(){
		
		$oAuteur=model_auteur::getInstance()->findById(_root::getParam('id'));
		
		$oXml=new plugin_xmlObject($oAuteur);
		$oXml->setListColumn(array('id','nom','prenom'));
		$oXml->show();
	}
	
	public function _listXml(){
		
		$tAuteur=model_auteur::getInstance()->findAll();
		
		$oXml=new plugin_xmlListObject($tAuteur);
		$oXml->setListColumn(array('id','nom','prenom'));
		$oXml->show();
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
}
