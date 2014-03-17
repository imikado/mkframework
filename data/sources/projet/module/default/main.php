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
		$oModuleGoogleMap->setZoom(13);

		//$oModuleGoogleMap->addPosition('5 rue des champs elysees','Champs elysees');
		
		$oModuleGoogleMap->addPositionWithContent('5 Avenue Anatole France, 75007 Paris','Tour eiffel',array(
													'<h1>Tour Eiffel</h1>',
													'<p>5 Avenue Anatole France</p>'
													));
													
		$oModuleGoogleMap->addPositionWithContent('Place Charles de Gaulle, 75008 Paris','Arc de Triomphe',array(
													'<h1>Arc de Triomphe</h1>',
													'<p>Place Charles de Gaulle</p>'
													));
		

		$this->oLayout->add('main',$oModuleGoogleMap->getMap());
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
}
