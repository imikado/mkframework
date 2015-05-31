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
			
		);
		
		$oTpl=new _tpl('menu::index');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	public function _export(){
		$oTpl=new _tpl('menu::export');
		
		return $oTpl;
	}
	public function _projet(){
		
		$bBootstrap=0;
		if(file_exists('data/genere/'._root::getParam('id').'/layout/bootstrap.php')){
			$bBootstrap=1;
		}
		
		if($bBootstrap){
			
			$supB=' <sup>Bootstrap</sup>';
			
			$tLink=array(
				//'Ajouter un module' => 'addmodule',
				tr('menuProject_title_couchemodel')=>'title',
					tr('menuProject_link_createCoucheModel') => 'model',
				
				tr('menuProject_title_modules') => 'title',
					tr('menuProject_link_createModule') => 'moduleWithBootstrap',
					
					tr('menuProject_link_createModuleCRUD') => 'crudWithBootstrap',
					tr('menuProject_link_createModuleCRUDreadonly') => 'crudreadonlyWithBootstrap',
					
					tr('menuProject_link_createModuleAuth') => 'authmoduleWithBootstrap',
					tr('menuProject_link_createModuleAuthWithInscription') => 'authwithinscriptionmoduleWithBootstrap',
				
					//tr('menuProject_link_createAcl') => 'addrightsmanager',

				tr('menuProject_title_moduleEmbedded') => 'title',
					tr('menuProject_link_createModuleMenuEmbedded') => 'addmodulemenuWithBootstrap',
					tr('menuProject_link_createModuleEmbedded') => 'moduleembeddedWithBootstrap',
					tr('menuProject_link_createModuleCRUDEmbedded') => 'crudembeddedWithBootstrap',
					//'Cr&eacute;er un module Lecture seule int&eacute;grable'.$supB => 'crudembeddedreadonlyWithBootstrap',
				
				tr('menuProject_title_databasesEmbedded') => 'title',
				
					tr('menuProject_link_createDatabaseXml') => 'xml',
					tr('menuProject_link_createDatabaseXmlIndex') => 'xmlindex',
					tr('menuProject_link_createDatabaseCsv') => 'csv',
					tr('menuProject_link_createDatabaseSqlite') => 'sqlite',
					tr('menuProject_link_createDatabaseJson') => 'json',
					tr('menuProject_link_createDatabaseJsonIndex') => 'jsonindex',
			);
		}else{
			
			$tLink=array(
				//'Ajouter un module' => 'addmodule',
				tr('menuProject_title_couchemodel')=>'title',
					tr('menuProject_link_createCoucheModel') => 'model',
				
				tr('menuProject_title_modules') => 'title',
					tr('menuProject_link_createModule') => 'module',
					
					tr('menuProject_link_createModuleCRUD') => 'crud',
					tr('menuProject_link_createModuleCRUDreadonly') => 'crudreadonly',
					
					tr('menuProject_link_createModuleAuth') => 'authmodule',
					tr('menuProject_link_createModuleAuthWithInscription') => 'authwithinscriptionmodule',
					
					tr('menuProject_link_createAcl') => 'addrightsmanager',
				
				tr('menuProject_title_moduleEmbedded') => 'title',
					tr('menuProject_link_createModuleMenuEmbedded') => 'addmodulemenu',
					tr('menuProject_link_createModuleEmbedded') => 'moduleembedded',
					tr('menuProject_link_createModuleCRUDEmbedded') => 'crudembedded',
					tr('menuProject_link_createModuleCRUDreadonlyEmbedded') => 'crudembeddedreadonly',
					
				tr('menuProject_title_views') => 'title',
					tr('menuProject_link_addViewTablesimple') => 'addviewtablemoduletablesimple',
					//'Cr&eacute;er un tableau avec tri (avec le module table)' => 'addviewtablemoduletablewithorder',
					//'Cr&eacute;er un tableau avec tri + ligne cliquable (avec le module table)' => 'addviewtablemoduletablewithorderclic',
					tr('menuProject_link_addForm') => 'addviewform',
				
				tr('menuProject_title_databasesEmbedded') => 'title',
				
					tr('menuProject_link_createDatabaseXml') => 'xml',
					tr('menuProject_link_createDatabaseXmlIndex') => 'xmlindex',
					tr('menuProject_link_createDatabaseCsv') => 'csv',
					tr('menuProject_link_createDatabaseSqlite') => 'sqlite',
					tr('menuProject_link_createDatabaseJson') => 'json',
					tr('menuProject_link_createDatabaseJsonIndex') => 'jsonindex',
			);
			
		}
		
		$oTpl=new _tpl('menu::projet');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	public function _projetEmbedded(){
		
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
