<?php
/*
This file is part of Mkframework.

Mkframework is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License.

Mkframework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

*/
Class module_menu extends abstract_module{
	
	public function __construct(){
		plugin_i18n::start();
	}
	
	public function _index(){
		
		$tLink=array(
			tr('menuTop_createProject') => 'builder::new',
			tr('menuTop_editProjects') => 'builder::list',

			tr('menuTop_marketBuilder') => 'builder::marketBuilder',
			
		);
		
		$oTpl=new _tpl('menu::index');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	public function _export(){
		$oTpl=new _tpl('menu::export');
		
		return $oTpl;
	}
	
	public function _projetEmbedded(){
		/*
		if(_root::getParam('action')=='model'){
			$tLink=array(
				//'Cr&eacute;er la couche mod&egrave;le' => 'model',
			);
		}else{//if(_root::getParam('action')=='module'){
			$tLink=array(
				'Modules' => 'title',
				'Cr&eacute;er un module' => 'module',
				
				'Cr&eacute;er un module CRUD' => 'crud',
				'Cr&eacute;er un module Lecture seule' => 'crudreadonly',
				
				'Cr&eacute;er un module d\'authentification' => 'authmodule',
				'Cr&eacute;er un module d\'authentification avec inscription' => 'authwithinscriptionmodule',
			
			'Modules int&eacute;grable' => 'title',
				'Cr&eacute;er un module menu ' => 'addmodulemenu',
				'Cr&eacute;er un module int&eacute;grable' => 'moduleembedded',
				'Cr&eacute;er un module CRUD int&eacute;grable' => 'crudembedded',
				'Cr&eacute;er un module Lecture seule int&eacute;grable' => 'crudembeddedreadonly',
			);
		}*/

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
				//'coucheModel',
				'modules',
				'modulesEmbedded',
				'views',
				//'databasesEmbedded',
				//'unitTest',
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

		
		$oTpl=new _tpl('menu::projetEmbedded');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	
	private function getListDir($oRootDir){
		$toDir=$oRootDir->getListDir();
		$tDir=array();
		foreach($toDir as $oDir){
			$tDir[$oDir->getName()]['dir']=$this->getListDir($oDir);
			$tDir[$oDir->getName()]['file']=$this->getListFile($oDir);
		}
		ksort($tDir);
		return $tDir;
	}
	private function getListFile($oRootDir){
		$toFile=$oRootDir->getListFile();
		$tFile=array();
		foreach($toFile as $oFile){
			$tFile[$oFile->getName()]=$oFile->getAdresse();
		}
		asort($tFile);
		return $tFile;
	}
	
	public function codeArbo($sProject){
		
		$oDir=new _dir('data/genere/'.$sProject);
		$tDir=$oDir->getListDir();
		
		$tFileAndDir=array();
		foreach($tDir as $oDir){
			$tFileDir[$oDir->getName()]['dir']=$this->getListDir($oDir);
			$tFileDir[$oDir->getName()]['file']=$this->getListFile($oDir);
			
		}
		
		ksort($tFileDir);
		$oView=new _view('menu::codearbo');
		$oView->tDir=$tDir;
		$oView->tFileDir=$tFileDir;
		return $oView;
	}
}
?>
