<?php 
class module_auth extends abstract_module{

	
	public function before(){
		_root::getAuth()->enable();
	
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}

	public function _login(){
		$oView=new _view('auth::login');
		
		$this->oLayout->add('main',$oView);

		if(_root::getRequest()->isPost() ){
			$sLogin=_root::getParam('login');
			$sPass=sha1(_root::getParam('password'));
 			$oModelAccount=new model_account;
			$tAccount=$oModelAccount->getListAccount();
			
			if(_root::getAuth()->checkLoginPass($tAccount,$sLogin,$sPass)){
				$oAccount=_root::getAuth()->getAccount();
				$tPermission=model_permission::getInstance()->findByGroup($oAccount->groupe);
				
				//on purge les permissions en session
				_root::getACL()->purge();
				
				//boucle sur les permissions
				if($tPermission)
				foreach($tPermission as $oPermission){
					if($oPermission->allowdeny=='ALLOW'){
						_root::getACL()->allow($oPermission->action,$oPermission->element);
					}else{
						_root::getACL()->deny($oPermission->action,$oPermission->element);
					}
				}
			
				_root::redirect('prive::list');
			}
			
		}

	}

	public function _logout(){
		_root::getAuth()->logout();
	}


	public function after(){
		$this->oLayout->show();
	}
}
