<?php

class module_mods_scBootstrap_moduleEmbedded extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_moduleEmbedded';
	protected $sModuleView = 'mods/scBootstrap/moduleEmbedded';
	protected $tSource = array(
	    'example/main.php',
	    array(
		'field' => 'actions',
		'tag' => 'view',
		'file' => 'example/view/exampletpl.php'
	    ),
		//'business/business_module.php',
		//'business/interface/businessInterface_module.php',
		//'tests/business_moduleTest.php',
	);
	private $msg = null;
	private $detail = null;
	private $tError = null;

	public function _index() {

		$oModule = new module_builderForm();
		$oModule->load($this->sModuleView);
		$oModule->loadParams(_root::getRequest()->getParams());
		$oModule->loadEngine(new module_mods_scBootstrap_moduleEngine());
		$oModule->loadSource($this->tSource);

		return $oModule->run();
	}

}

class module_mods_scBootstrap_moduleEngine {

	public function getApplicationPath() {
		$oTools = new module_builderTools();
		return $oTools->getRootWebsite();
	}

	public function getLinkToFile($sFile) {

		return '<a href="' . _root::getLink('code::index', array('project' => _root::getParam('id'), 'file' => $sFile)) . '">' . $sFile . '</a>';
	}

	public function preProcess($iStep, $tParam) {

		if ($iStep === '1') {

		} else if ($iStep == '2') {

		}


		return array('status' => true, 'tParam' => $tParam);
	}

}
