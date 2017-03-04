<?php

class abstract_moduleBuilderEngine{

	public function getApplicationPath() {
		$oTools = new module_builderTools();
		return $oTools->getRootWebsite();
	}
	public function getApplicationModuleLink($sModule_){
		return $this->getApplicationPath().'/public/index.php?:nav='.$sModule_;
	}


	public function getLinkToFile($sFile) {
		return '<a href="' . _root::getLink('code::index', array('project' => _root::getParam('id'), 'file' => $sFile)) . '">' . $sFile . '</a>';
	}
}
