<?php

class module_mods_scBootstrap_crud extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_crud';
	protected $sModuleView = 'mods/scBootstrap/crud';
	protected $tSource = array(
	    'example/main.php',
	    'example/view/delete.php',
	    'example/view/edit.php',
	    'example/view/new.php',
	    'example/view/show.php',
	    'example/view/list.php',
		'example/i18n/fr.php',
		'example/i18n/en.php',
	    'business/business_table.php',
	    'tests/business_tableTest.php',
	    'tests/module_tableTest.php',
	);
	private $msg = null;
	private $detail = null;
	private $tError = null;

	public function _index() {

		$oModule = new module_builderForm();
		$oModule->load($this->sModuleView);
		$oModule->loadParams(_root::getRequest()->getParams());
		$oModule->loadEngine(new module_mods_scBootstrap_crudEngine());
		$oModule->loadSource($this->tSource);

		return $oModule->run();
	}

}

class module_mods_scBootstrap_crudEngine extends abstract_moduleBuilderEngine {


	public function preProcess($iStep, $tParam) {

		if ($iStep === '1') {

			$tParam['project'] = _root::getParam('id');

			$tParam['moduleNameDeducted'] = substr($tParam['model'], 6, -4);

			$tParam['enctype'] = '';
		} else if ($iStep == '2') {

			$tParam['moduleChild'] = $tParam['moduleName'];

			$tParam['model_table'] = substr($tParam['model'], 0, -4);
			$tParam['row_table'] = 'row_' . substr($tParam['model_table'], 6);

			$tParam['sTable'] = substr($tParam['model_table'], 6);
			$tParam['tTable'] = 't' . ucfirst($tParam['sTable']);
			$tParam['oTable'] = 'o' . ucfirst($tParam['sTable']);

			$tParam['business_table'] = 'business_crud' . ucfirst($tParam['moduleChild']);

			$tParam['interfaceModel'] = 'interface_model';


			require($this->getApplicationPath() . '/model/' . $tParam['model'] );
			$oClass = new $tParam['model_table'];

			if (false === is_subclass_of($oClass, $tParam['interfaceModel'])) {


				return array('status' => false, 'getPage' => 1, 'tParam' => $tParam);
			}
		} else if ($iStep == '3') {
			$tParam['colspan'] = count($tParam['tColumn']);

			foreach ($tParam['tColumn'] as $iColumn => $sColumn) {
				if ($sColumn == $tParam['keyField']) {
					unset($tParam['tColumn'][$iColumn]);
					unset($tParam['tLabel'][$iColumn]);
					unset($tParam['tType'][$iColumn]);
				}
			}

			$tParam['lien']=$this->getApplicationModuleLink($tParam['moduleParent'].'_'.$tParam['moduleName'].'::index' );
		}


		return array('status' => true, 'tParam' => $tParam);
	}

}
