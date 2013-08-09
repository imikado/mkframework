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
		
	public function _index(){
		
		$tLink=array(
			'Cr&eacute;er un projet' => 'builder::new',
			'Administrer les projets' => 'builder::list',
			
		);
		
		$oTpl=new _tpl('menu::index');
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	public function _projet(){
		
		$tLink=array(
			//'Ajouter un module' => 'addmodule',
			'Cr&eacute;er la couche mod&egrave;le' => 'model',
			'Cr&eacute;er un module CRUD' => 'crud',
			'Cr&eacute;er un module CRUD int&eacute;grable' => 'crudembedded',
			'Ajouter un module menu ' => 'addmodulemenu',
			'Cr&eacute;er un module' => 'module',
			'Cr&eacute;er un module int&eacute;grable' => 'moduleembedded',
			'Cr&eacute;er une base xml' => 'xml',
			'Cr&eacute;er un index sur base xml' => 'xmlindex',
			'Cr&eacute;er une base csv' => 'csv',
			'Cr&eacute;er une base sqlite' => 'sqlite',
		);
		
		$oTpl=new _tpl('menu::projet');
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
