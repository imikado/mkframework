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
	
	
	#iciMethodShow#
	

	
	public function after(){
		$this->oLayout->show();
	}
	
	
}

