<?php
class module_mods_scBootstrap_rightsManager extends abstract_moduleBuilder{

	protected $sModule='mods_scBootstrap_rightsManager';
	protected $sModuleView='mods/scBootstrap/rightsManager';
	protected $tSource = array(
		'moduleParent/main.php',

		'moduleParent/moduleAuth/main.php',
		'moduleParent/moduleAuth/view/login.php',
		'moduleParent/moduleAuth/i18n/fr.php',
		'moduleParent/moduleAuth/i18n/en.php',

		'moduleParent/moduleRightsManager/main.php',
		'moduleParent/moduleRightsManager/view/delete.php',
		'moduleParent/moduleRightsManager/view/edit.php',
		'moduleParent/moduleRightsManager/view/index.php',
		'moduleParent/moduleRightsManager/view/new.php',
		'moduleParent/moduleRightsManager/view/userEdit.php',
		'moduleParent/moduleRightsManager/i18n/fr.php',
		'moduleParent/moduleRightsManager/i18n/en.php',

		'model/model_user.php',
		'model/model_rightsManager.php',

		'business/business_auth.php',
		'business/business_rightManager.php',

		'interface/interface_businessAuthModel.php',
		'interface/interface_businessRightManagerModel.php',

		'tests/business_authTest.php',
		'tests/business_rightManagerTest.php',
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

class module_mods_scBootstrap_authEngine extends abstract_moduleBuilderEngine {


	public function preProcess($iStep, $tParam) {

		if ($iStep === '2') {

			$tParam['project'] = _root::getParam('id');

			$tParam['interfaceModel'] = 'interface_business' . ucfirst( $tParam['moduleAuth']) . 'ModelUser';
			$tParam['interfaceAuth'] = 'interface_auth';
			$tParam['interfaceACL'] = 'interface_acl';
			$tParam['interfaceModelRightmanager'] = 'interface_business' . ucfirst( $tParam['moduleRightsManager']) . 'Model';

			return array('status' => true, 'tParam' => $tParam);





		} else if ($iStep === '3') {

			$tParam['modelClass'] = 'model_'.$tParam['moduleAuth'];

			$tParam['model_user']= 'model_'.$tParam['moduleAuth'];
			$tParam['row_user']= 'row_'.$tParam['moduleAuth'];
			$tParam['oUser']= 'o'.ucfirst($tParam['moduleAuth']);

			return array('status' => true, 'tParam' => $tParam);




		} else if ($iStep === '6') {
			$tParam['moduleRedirect'] = 'module_' . $tParam['moduleParentRedirect'] . '_' . $tParam['moduleChildRedirect'];
		} else if ($iStep === '7') {
			$tParam['actionViewChildRedirect'] = substr($tParam['actionChildRedirect'], 1);

			$tParam['lien']=$this->getApplicationModuleLink($tParam['moduleParent'].'_'.$tParam['moduleAuth'].'::login' );

		}


		return array('status' => true, 'tParam' => $tParam);
	}

}
