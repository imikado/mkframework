<?php
class module_default extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('html5');
		
		$this->oLayout->addModule('menu','menu::index');
	}

	public function _googleMap(){
		
		$oModuleGoogleMap=new module_googleMap();
		$oModuleGoogleMap->setWidth(500);
		$oModuleGoogleMap->setHeight(400);
		$oModuleGoogleMap->setZoom(14);

		$oModuleGoogleMap->addPosition('5 rue des champs elysees','Champs elysees');

		$this->oLayout->add('main',$oModuleGoogleMap->getMap());
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
}
