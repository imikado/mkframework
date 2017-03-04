<?php

class module_mods_scBootstrap_moduleParent extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_moduleParent';
	protected $sModuleView = 'mods/scBootstrap/moduleParent';
	protected $tSource = array(
	    'example/main.php',
	);
	private $msg = null;
	private $detail = null;
	private $tError = null;

	public function _index() {

		$oModule = new module_builderForm();
		$oModule->load($this->sModuleView);
		$oModule->loadParams(_root::getRequest()->getParams());
		$oModule->loadEngine(new module_mods_scBootstrap_moduleParentEngine());
		$oModule->loadSource($this->tSource);

		return $oModule->run();
	}

}

class module_mods_scBootstrap_moduleParentEngine {

	public function getApplicationPath() {
		$oTools = new module_builderTools();
		return $oTools->getRootWebsite();
	}

	public function getLinkToFile($sFile) {

		return '<a href="' . _root::getLink('code::index', array('project' => _root::getParam('id'), 'file' => $sFile)) . '">' . $sFile . '</a>';
	}

	public function preProcess($iStep, $tParam) {

		if ($iStep === '1') {

		}


		return array('status' => true, 'tParam' => $tParam);
	}

}
