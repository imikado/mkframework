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
	
	public function _list(){
		
		$tExamplemodel=model_examplemodel::getInstance()->findAll();
		
		$oView=new _view('examplemodule::list');
		$oView->tExamplemodel=$tExamplemodel;
		
		//icilist

		$this->oLayout->add('main',$oView);
	}

	public function _show(){
		$oExamplemodel=model_examplemodel::getInstance()->findById( _root::getParam('id') );
		
		$oView=new _view('examplemodule::show');
		$oView->oExamplemodel=$oExamplemodel;
		
		//icishow
		$this->oLayout->add('main',$oView);
	}
	
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}


/*variables
#select		$oView->tJoinexamplemodel=examplemodel::getInstance()->getSelect();#fin_select
variables*/
