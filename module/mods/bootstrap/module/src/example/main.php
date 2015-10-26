<?php 
class module_#MODULE# extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('bootstrap');
		
		//$this->oLayout->addModule('menu','menu::index');
	}
	
	#METHODS#
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
