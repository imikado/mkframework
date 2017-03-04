<?php

class VARbusiness_tableENDVAR extends business_abstract {

	protected $_oModel;
	protected $_oAuth;
	protected $_oI18n;
	protected $_oValid;
	protected $_tColumn = array(VARbusinessArrayColumnENDVAR);

	public function __construct(VARinterfaceModelENDVAR $oModel_, interface_i18n $oI18n_, interface_valid $oValid_) {
		$this->_oModel = $oModel_;
		$this->_oI18n=$oI18n_;
		$this->_oValid=$oValid_;
	}

	public function tr($sTag_){
		return $this->_oI18n->tr($sTag_);
	}

	public function getCheck($tParam_) {
		$this->_oValid->load($tParam_);

		foreach ($this->_tColumn as $sColumn) {
			$this->_oValid->isNotEmpty($sColumn, $this->tr('errorIsEmpty'));
		}
		return $this->_oValid;
	}

	public function insertItem($VARoTableENDVAR_,$tParam_) {
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

	public function updateItem($id_, $tParam_) {
		$oValid = $this->getCheck($tParam_);
		if (!$oValid->isValid()) {
			return $this->sendReturn(false, array('tError' => $oValid->getListError()));
		}

		$VARoTableENDVAR_=$this->_oModel->findByID($id_);
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
