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
class module_code extends abstract_module{
	
	public static $INI='INI';
	public static $VIEW='VIEW';
	public static $MODEL='MODEL';
	public static $MODULE='MODULE';
	public static $UNDEFINED='UNDEFINED';
	
	public function before(){
		$this->oLayout=new _layout('templateCode');
		
		$oModuleMenu=new module_menu;
		$oMenuView=$oModuleMenu->codearbo(_root::getParam('project'));
		
		$this->oLayout->add('menu',$oMenuView);
		
	}
	
	public function _index(){
		$oView=new _view('code::index');
		$this->oLayout->add('main',$oView);
	}
	
	public function _createfile(){
		$oFile=new _file(_root::getParam('file'));
		$oFile->setContent("\n");
		$oFile->save();
		
		_root::redirect('code::editcode',array(
													'project'=>_root::getParam('project'),
													'file' => _root::getParam('file'),
													'type' => _root::getParam('type')
													));
	}
	
	public function _editcode(){
		$this->saveCode();
		
		$oModuleDir=new _dir('data/genere/'._root::getParam('project').'/module');
		$tModule=array();
		foreach($oModuleDir->getListDir() as $oDir){
			$tModule[]=$oDir->getName();
		}
		
		$oModelDir=new _dir('data/genere/'._root::getParam('project').'/model');
		$tModel=array();
		foreach($oModelDir->getListFile() as $oFile){
			$tModel[]=substr($oFile->getName(),0,-4);
		}
		
		$this->oLayout=new _layout('templateCodeFrame');
		$oFile=new _file(_root::getParam('file'));
		
		if(!$oFile->exist()){
			$oView=new _view('code::nocode');
		}else{
			$oView=new _view('code::code');
		}
		
		$oView->oFile=$oFile;
		$oView->tModule=$tModule;
		$oView->tModel=$tModel;

		$this->oLayout->add('main',$oView);
	}
	public function _editfullcode(){
		$this->saveFullCode();
		
		$this->oLayout=new _layout('templateCodeFrame');
		$oFile=new _file(_root::getParam('file'));
		
		if(!$oFile->exist()){
			$oView=new _view('code::nocode');
		}else{
			$oView=new _view('code::fullcode');
		}
		
		$oView->oFile=$oFile;
		

		$this->oLayout->add('main',$oView);
	}
	private function saveCode(){
		if(!_root::getRequest()->isPost()){
			return null;
		}
		$iLine=(int)_root::getParam('iLine')-1;
		
		if(isset($_POST['content_begin'])){
			$sContent=$_POST['content_begin'].'='.$_POST['content_end'];
		}else{
			$sContent=$_POST['content'];
		}
	
		$oFile=new _file(_root::getParam('file'));
		
		//backup
		$oBackupFile=new _file(_root::getParam('file').'.bak');
		$oBackupFile->setContent($oFile->getContent());
		$oBackupFile->save();
		
		$tLine=$oFile->getTab();
		$tLine[$iLine]=$sContent;
		$tLine=array_map('rtrim',$tLine);
		$oFile->setContent(implode($tLine,"\n"));
		$oFile->save();
		
	}
	private function saveFullCode(){
		if(!_root::getRequest()->isPost()){
			return null;
		}
		
		$sContent=$_POST['content'];
		
	
		$oFile=new _file(_root::getParam('file'));
		
		//backup
		$oBackupFile=new _file(_root::getParam('file').'.bak');
		$oBackupFile->setContent($oFile->getContent());
		$oBackupFile->save();
		
		$tLine=$oFile->getTab();
		$oFile->setContent($sContent);
		$oFile->save();
		
		_root::redirect('code::editcode',array('project'=>_root::getParam('project'),'file'=>_root::getParam('file')));
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
}
