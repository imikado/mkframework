<?php
class abstract_moduleBuilder{

	public function __construct(){
		$sFile=_root::getConfigVar('path.module').'/'.$this->sModuleView.'/i18n/'._root::getConfigVar('language.default').'.php';
		if(file_exists($sFile)){
			include($sFile);
		}
	}

	//tools
	protected function getView($sView){
		return new _tpl($this->sModuleView.'::'.$sView);
	}
	protected function getSourceFilename($sFilename){
		return _root::getConfigVar('path.module').'/'.$this->sModuleView.'/src/'.$sFilename;
	}
	protected function getProjectFilename($sFilename){
		return _root::getConfigVar('path.generation')._root::getParam('id').'/'.$sFilename;
	}
	protected function replaceInSource($tReplace,$sSource){
		return module_builder::getTools()->stringReplaceIn(
											$tReplace,
											$this->getSourceFilename($sSource));
	}
	protected function projectSaveFile($sContent,$sFilename){
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/'.$sFilename );

		if($oFile->exist()){
		  return false;
		}

		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}
	protected function projectMkdir($sDir){
		return module_builder::getTools()->projetmkdir($sDir);
	}
	protected function isPost(){
		return _root::getRequest()->isPost();
	}

	protected function getObjectSource($sSource){
		return module_builder::getTools()->getSource(_root::getConfigVar('path.module').$this->sModuleView,_root::getConfigVar('path.generation')._root::getParam('id'),$sSource);
	}
	protected function copyFromTo($sFrom,$sTo){
		return model_mkfbuilderprojet::getInstance()->copyFromTo($this->getSourceFilename($sFrom),$this->getProjectFilename($sTo));
	}
	//end tools

	public function getListSource(){
		return $this->tSource;
	}

}
