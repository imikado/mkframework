<?php

class module_mods_scBootstrap_authInscription extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_authInscription';
	protected $sModuleView = 'mods/scBootstrap/authInscription';
	protected $tSource = array(
		'moduleParent/main.php',
	    'moduleParent/example/main.php',
	    'moduleParent/example/view/login.php',
	    'moduleParent/example/view/inscription.php',
		'moduleParent/example/i18n/fr.php',
	    'moduleParent/example/i18n/en.php',
		'model/model_user.php',
	    'business/business_auth.php',
	    'interface/interface_businessAuthModel.php',
	    'tests/business_authTest.php',
	);
	private $msg = null;
	private $detail = null;
	private $tError = null;

	public function _index() {

		$oModule = new module_builderForm();
		$oModule->load($this->sModuleView);
		$oModule->loadParams(_root::getRequest()->getParams());
		$oModule->loadEngine(new module_mods_scBootstrap_authEngine());
		$oModule->loadSource($this->tSource);

		return $oModule->run();
	}

}

class module_mods_scBootstrap_authEngine extends abstract_moduleBuilderEngine{

	public function preProcess($iStep, $tParam) {

		if ($iStep === '1') {

			$tParam['project'] = _root::getParam('id');

			$tParam['interfaceModel'] = 'interface_business' . ucfirst( $tParam['moduleName']) . 'ModelUser';
			$tParam['interfaceAuth'] = 'interface_auth';

			$tParam['moduleChild'] = $tParam['moduleName'];

			return array('status' => true, 'tParam' => $tParam);
		} else if ($iStep === '2') {

			$tParam['modelClass'] = 'model_'.$tParam['moduleChild'];

			$tParam['model_user']= 'model_'.$tParam['moduleChild'];
			$tParam['row_user']= 'row_'.$tParam['moduleChild'];
			$tParam['oUser']= 'o'.ucfirst($tParam['moduleChild']);

			return array('status' => true, 'tParam' => $tParam);
		} else if ($iStep === '3') {



		} else if ($iStep === '6') {
			$tParam['moduleRedirect'] = 'module_' . $tParam['moduleParentRedirect'] . '_' . $tParam['moduleChildRedirect'];
		} else if ($iStep === '7') {
			$tParam['actionViewChildRedirect'] = substr($tParam['actionChildRedirect'], 1);

			$tParam['lien']=$this->getApplicationModuleLink($tParam['moduleParent'].'_'.$tParam['moduleChild'].'::login' );

		}


		return array('status' => true, 'tParam' => $tParam);
	}

}
