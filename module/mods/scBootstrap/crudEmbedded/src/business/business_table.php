<?php

class VARbusiness_tableENDVAR extends business_abstract {

	protected $_oModel;
	protected $_oAuth;
	protected $_tColumn = array(VARbusinessArrayColumnENDVAR);
	protected $_oI18n;

	public function __construct(VARinterfaceModelENDVAR $oModel_, interface_i18n $oI18n_) {
		$this->_oModel = $oModel_;
		$this->_oI18n=$oI18n_;
	}

	public function tr($sTag_){
		return $this->_oI18n->tr($sTag_);
	}

	public function getCheck($tParam_) {
		$oPluginValid = new plugin_sc_valid();
		$oPluginValid->load($tParam_);

		foreach ($this->_tColumn as $sColumn) {
			$oPluginValid->isNotEmpty($sColumn, $this->tr('errorIsEmpty'));
		}
		return $oPluginValid;
	}

	public function insertItem($VARoTableENDVAR_, $tParam_) {
		$oValid = $this->getCheck($tParam_);
		if (!$oValid->isValid()) {
			return $this->sendReturn(false, array('tError' => $oValid->getListError()));
		}

		foreach ($this->_tColumn as $sColummn) {
			$VARoTableENDVAR_->$sColummn = $tParam_[$sColummn];
		}

		$this->_oModel->insert($VARoTableENDVAR_);


		return true;
	}

	public function updateItem($VARoTableENDVAR_, $tParam_) {
		$oValid = $this->getCheck($tParam_);
		if (!$oValid->isValid()) {
			return $this->sendReturn(false, array('tError' => $oValid->getListError()));
		}

		foreach ($this->_tColumn as $sColummn) {
			$VARoTableENDVAR_->$sColummn = $tParam_[$sColummn];
		}

		$this->_oModel->update($VARoTableENDVAR_);


		return true;
	}

	public function deleteItem($VARoTableENDVAR_) {
		$this->_oModel->delete($VARoTableENDVAR_);


		return true;
	}

}
