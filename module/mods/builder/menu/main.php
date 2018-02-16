<?php
class module_mods_builder_menu extends abstract_moduleBuilder{

	protected $sModule='mods_builder_menu';
	protected $sModuleView='mods/builder/menu';

	
	public function _project(){
		
		$bBootstrap=0;
		if(file_exists('data/genere/'._root::getParam('id').'/layout/bootstrap.php')){
			$bBootstrap=1;
		}
		
		if($bBootstrap){
			$tType=array('all','bootstrap');
			
		}else{
			$tType=array('all','normal');
			
		}

		$sLang=_root::getConfigVar('language.default');
		$tLinkModule=array();

		foreach($tType as $sType){
			$sPathModule=_root::getConfigVar('path.module').'/mods/'.$sType;
			
			$tModulesAll=scandir($sPathModule);
			foreach($tModulesAll as $sModule){
				if(file_exists($sPathModule.'/'.$sModule.'/info.ini')){
					$tIni=parse_ini_file($sPathModule.'/'.$sModule.'/info.ini');
					$priority=999;
					if(isset($tIni['priority'])){
						$priority=$tIni['priority'];
					}
					$sPriority=sprintf('%03d',$priority);
					$tLinkModule[ $tIni['category'] ][ $tIni['title.'.$sLang].' <sup>version '.$tIni['version'].'</sup>' ]=$sPriority.'mods_'.$sType.'_'.$sModule.'::index';
				}
			}
		}
		

		//$tModules=scandir(_root::getConfigVar('path.module')).'/mods/normal';
		
		$tTitle=array(
			'coucheModel',
			'modules',
			'modulesEmbedded',
			'views',
			'databasesEmbedded',
			'unitTest',
		);
		$tLink=array();
		foreach($tTitle as $sTitle){
			if(isset($tLinkModule[$sTitle])){

				$tLinkModuleCat=$tLinkModule[$sTitle];
				asort($tLinkModuleCat);
			
				$tLink[ tr('menu_'.$sTitle) ]='title';

				foreach($tLinkModuleCat as $sLabel => $sLink){
					$tLink[ $sLabel ]=substr($sLink,3);
				}
			}
		}

			
		$oTpl=$this->getView('project');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	

}