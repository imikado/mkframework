<?php
class business_VARmoduleAuthENDVAR extends business_abstract {

	protected $_oModel;
	protected $_oAuth;
	protected $_oI18n;

	//maximum lenght for password
	protected $_iMaxPasswordLength = 100;

	public function __construct(VARinterfaceModelENDVAR $oModel_, VARinterfaceAuthENDVAR $oAuth_, interface_i18n $oI18n_) {
		$this->_oModel = $oModel_;
		$this->_oAuth = $oAuth_;
		$this->_oI18n = $oI18n_;
	}

	public function tr($sTag_){
		return $this->_oI18n->tr($sTag_);
	}

	public function findAccount($tAccount_,$sLogin_,$sPasswordHashed_){
		//we check that we find in array returned by model class
		//entry $tAccount[ login ][ hashed password ]
		if(isset($tAccount_[$sLogin_]) ){
			if(isset($tAccount_[$sLogin_][$sPasswordHashed_]) ){
				return $this->sendReturn(
					true,
					array(
						'oAccount' => $tAccount_[$sLogin_][$sPasswordHashed_]
					));
			}else{
				return $this->sendReturn(
						false,
						array(
							'error'=> $this->tr('loginExistButWrongPassword')
						));
			}
		}else{
			return $this->sendReturn(
					false,
					array(
						'error'=> $this->tr('loginNotFound')
					));
		}
	}

	public function checkCredentials($sLogin_, $sPassword_) {
		if (strlen($sPassword_) > $this->_iMaxPasswordLength) {
			return $this->sendReturn(false, array('error' => $this->tr('passwordTooLong')));
		}
		//we store password hashed in class model_exampleToReplace
		$sPasswordHashed = $this->_oModel->hashPassword($sPassword_);
		$tAccount = $this->_oModel->getListAccount();

		if($this->findAccount($tAccount,$sLogin_,$sPasswordHashed) ){
			$oAccount=$this->getReturn()->getData('oAccount');

			$this->_oAuth->_connect();
			$this->_oAuth->setAccount($oAccount);

			return $this->sendReturn(true,array('oAccount'=>$oAccount) );
		} else {
			return $this->sendReturn(false, array('error' => $this->tr('badCredentials')));
		}


		return $this->sendReturn(true);
	}



}
