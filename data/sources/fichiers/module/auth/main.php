<?php
class module_exampleauth extends abstract_module{
	
	public function before(){
		//on active l'authentification
		_root::getAuth()->enable();

		$this->oLayout=new _layout('template1');
	}

	public function _login(){
		
		$sError=null;
		if(_root::getRequest()->isPost() ){
			$sLogin=_root::getParam('login');
			//on stoque les mots de passe hashe dans la classe model_example
			$sPass=model_example::getInstance()->hashPassword(_root::getParam('password'));
			$tAccount=model_example::getInstance()->getListAccount();

			//on va verifier que l'on trouve dans le tableau retourne par notre model
			//l'entree $tAccount[ login ][ mot de passe hashe ]
			if(_root::getAuth()->checkLoginPass($tAccount,$sLogin,$sPass)){
				_root::redirect('privatemodule_action');
			}else{
				$sError='Mauvais login/mot de passe';
			}

		}
		
		$oView=new _view('auth_login');
		$oView->sError=$sError;

		$this->oLayout->add('main',$oView);

	}

	public function _logout(){
		_root::getAuth()->logout();
	}

	public function after(){
		$this->oLayout->show();
	}
}
