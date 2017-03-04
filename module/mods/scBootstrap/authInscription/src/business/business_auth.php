<?php

class business_VARmoduleChildENDVAR extends business_abstract {

	protected $_oModel;
	protected $_oAuth;
	protected $_oI18n;
//maximum lenght for password
	protected $_iMaxPasswordLength = 100;

	public function __construct(VARinterfaceModelENDVAR $oModel_, VARinterfaceAuthENDVAR $oAuth_, interface_i18n $oI18n_ ) {
		$this->_oModel = $oModel_;
		$this->_oAuth = $oAuth_;
		$this->_oI18n=$oI18n_;
	}

	public function tr($sTag_){
		return $this->_oI18n->tr($sTag_);
	}

	public function checkCredentials($sLogin_, $sPassword_) {
		if (strlen($sPassword_) > $this->_iMaxPasswordLength) {
			return $this->sendReturn(false, array('error' => $this->tr('passwordTooLong')) );
		}
//we store password hashed in class model_exampleToReplace
		$sPasswordHashed = $this->_oModel->hashPassword($sPassword_);
		$tAccount = $this->_oModel->getListAccount();

//we check that we find in array returned by model class
//entry $tAccount[ login ][ hashed password ]
		if (isset($tAccount[$sLogin_][$sPasswordHashed])) {
			$this->_oAuth->_connect();
			$this->_oAuth->setAccount($tAccount[$sLogin_][$sPasswordHashed]);

			return $this->sendReturn(true);
		} else {
			return $this->sendReturn(false, array('error' => $this->tr('badCredentials')) );
		}


		return $this->sendReturn(true);
	}

	public function registerNewAccount($VARoUserENDVAR_, $sLogin_, $sPassword_, $sPassword2_) {

		$tAccount = $this->_oModel->getListAccount();

		$tError = array();

		if ($sPassword_ != $sPassword2_) {
			$tError['VARpasswordFieldENDVAR'] = array($this->tr('lesDeuxMotsDePasseDoiventEtreIdentique'));
		} elseif ($sLogin_ == '') {
			$tError['VARloginFieldENDVAR'] = array($this->tr('champDutilisteurRequis'));
		} elseif ($sPassword_ == '') {
			$tError['VARpasswordFieldENDVAR'] = array($this->tr('champDeMotDePasseRequis'));
		} elseif (strlen($sPassword_) > $this->_iMaxPasswordLength) {
			$tError['VARpasswordFieldENDVAR'] = array($this->tr('motDePasseTropLong'));
		} elseif (isset($tAccount[$sLogin_])) {
			$tError['VARloginFieldENDVAR'] = array($this->tr('utilisateurDejaExistant'));
		}

		if ($tError) {
			return $this->sendReturn(false, array('tError' => $tError));
		}

		$VARoUserENDVAR_->VARloginFieldENDVAR = $sLogin_;
		$VARoUserENDVAR_->VARpasswordFieldENDVAR = $this->_oModel->hashPassword($sPassword_);

		$this->_oModel->insert($VARoUserENDVAR_);

		return true;
	}

}
