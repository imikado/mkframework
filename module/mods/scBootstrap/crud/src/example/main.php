<?php

class module_VARmoduleParentENDVAR_VARmoduleChildENDVAR extends module_VARmoduleParentENDVAR {

	protected $_sModulePath = 'VARmoduleParentENDVAR/VARmoduleChildENDVAR';

	public function getView($sView_) {
		return new _view($this->_sModulePath . '::' . $sView_);
	}

	public function _index() {
		//on considere que la page par defaut est la page de listage
		$this->_list();
	}

	public function _list() {

		$VARtTableENDVAR = VARmodel_tableENDVAR::getInstance()->findAll();

		$oView = $this->getView('list');
		$oView->VARtTableENDVAR = $VARtTableENDVAR;

		VARtJoinENDVAR

		$this->oLayout->add('main', $oView);
	}

	public function _new() {
		$tMessage = $this->processSave();

		$VARoTableENDVAR = new VARrow_tableENDVAR;

		$oView = $this->getView('new');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('main', $oView);
	}

	public function _edit() {
		$tMessage = $this->processSave();

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(_root::getParam('id'));

		$oView = $this->getView('edit');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('main', $oView);
	}

	public function _show() {
		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(_root::getParam('id'));

		$oView = $this->getView('show');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR


		$this->oLayout->add('main', $oView);
	}

	public function _delete() {
		$tMessage = $this->processDelete();

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(_root::getParam('id'));

		$oView = $this->getView('delete');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR


		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		$this->oLayout->add('main', $oView);
	}

	private function processSave() {
		if (!_root::getRequest()->isPost()) { //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { //on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$tParams = _root::getRequest()->getParams();

		$oBusiness = new VARbusiness_tableENDVAR(VARmodel_tableENDVAR::getInstance(), _root::getI18n(), new plugin_sc_valid() );

		$iId = _root::getParam('id', null);
		if ($iId == null) {
			if (false === $oBusiness->insertItem(new VARrow_tableENDVAR, $tParams)) {
				return $oBusiness->getReturn()->getData('tError');
			}
		} else {
			if (false === $oBusiness->updateItem(_root::getParam('id'), $tParams)) {
				return $oBusiness->getReturn()->getData('tError');
			}
		}


		//une fois enregistre on redirige (vers la page liste)
		_root::redirect('VARmoduleParentENDVAR_VARmoduleChildENDVAR::list');
	}

	public function processDelete() {
		if (!_root::getRequest()->isPost()) { //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { //on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oBusiness = new VARbusiness_tableENDVAR(VARmodel_tableENDVAR::getInstance(), _root::getI18n(), new plugin_sc_valid()  );

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(_root::getParam('id'));

		if (false === $oBusiness->deleteItem($VARoTableENDVAR)) {
			return $oBusiness->getReturn()->getData('tError');
		}

		//une fois supprime on redirige (vers la page liste)
		_root::redirect('VARmoduleParentENDVAR_VARmoduleChildENDVAR::list');
	}

}
