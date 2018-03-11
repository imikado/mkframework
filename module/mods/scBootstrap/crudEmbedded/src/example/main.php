<?php

class module_VARmoduleParentENDVAR_VARmoduleChildENDVAR extends abstract_moduleembedded {

	public static $sModuleName = 'VARmoduleParentENDVAR_VARmoduleChildENDVAR';
	public static $sRootModule;
	public static $tRootParams;

	public function getView($sView_) {
		return new _view('VARmoduleParentENDVAR/VARmoduleChildENDVAR::' . $sView_);
	}

	public function __construct() {
		self::setRootLink(_root::getParamNav(), null);

		_root::getI18n()->loadFromDir(__DIR__.'/i18n/');
	}

	public static function setRootLink($sRootModule, $tRootParams = null) {
		self::$sRootModule = $sRootModule;
		self::$tRootParams = $tRootParams;
	}

	public static function getLink($sAction, $tParam = null) {
		return parent::_getLink(self::$sRootModule, self::$tRootParams, self::$sModuleName, $sAction, $tParam);
	}

	public static function getParam($sVar, $uDefault = null) {
		return parent::_getParam(self::$sModuleName, $sVar, $uDefault);
	}

	public static function redirect($sModuleAction, $tModuleParam = null) {
		return parent::_redirect(self::$sRootModule, self::$tRootParams, self::$sModuleName, $sModuleAction, $tModuleParam);
	}

	public function _index() {
		$sAction = '_' . self::getParam('Action', 'list');
		return $this->$sAction();
	}

	public function _list() {

		$VARtTableENDVAR = VARmodel_tableENDVAR::getInstance()->findAll();

		$oView = $this->getView('list');
		$oView->VARtTableENDVAR = $VARtTableENDVAR;

		VARtJoinENDVAR


		return $oView;
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

		return $oView;
	}

	public function _edit() {
		$tMessage = $this->processSave();

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(self::getParam('id'));

		$oView = $this->getView('edit');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR

		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		return $oView;
	}

	public function _show() {
		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(self::getParam('id'));

		$oView = $this->getView('show');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR


		return $oView;
	}

	public function _delete() {
		$tMessage = $this->processDelete();

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(self::getParam('id'));

		$oView = $this->getView('delete');
		$oView->VARoTableENDVAR = $VARoTableENDVAR;

		VARtJoinENDVAR


		$oPluginXsrf = new plugin_xsrf();
		$oView->token = $oPluginXsrf->getToken();
		$oView->tMessage = $tMessage;

		return $oView;
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

		$oBusiness = new VARbusiness_tableENDVAR(VARmodel_tableENDVAR::getInstance(), _root::getI18n() );

		$iId = self::getParam('id', null);
		if ($iId == null) {
			$VARoTableENDVAR = new VARrow_tableENDVAR;

			if (false === $oBusiness->insertItem($VARoTableENDVAR, $tParams)) {
				return $oBusiness->getReturn()->getData('tError');
			}
		} else {
			$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(self::getParam('id'));

			if (false === $oBusiness->updateItem($VARoTableENDVAR, $tParams)) {
				return $oBusiness->getReturn()->getData('tError');
			}
		}


		//une fois enregistre on redirige (vers la page liste)
		$this->redirect('list');
	}

	public function processDelete() {
		if (!_root::getRequest()->isPost()) { //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}

		$oPluginXsrf = new plugin_xsrf();
		if (!$oPluginXsrf->checkToken(_root::getParam('token'))) { //on verifie que le token est valide
			return array('token' => $oPluginXsrf->getMessage());
		}

		$oBusiness = new VARbusiness_tableENDVAR(VARmodel_tableENDVAR::getInstance(), _root::getI18n() );

		$VARoTableENDVAR = VARmodel_tableENDVAR::getInstance()->findById(self::getParam('id'));

		if (false === $oBusiness->deleteItem($VARoTableENDVAR)) {
			return $oBusiness->getReturn()->getData('tError');
		}

		//une fois supprime on redirige (vers la page liste)
		$this->redirect('list');
	}

}
