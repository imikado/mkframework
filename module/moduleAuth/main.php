<?php
class module_moduleAuth{
	
	
	public function _index(){
		$tMessage=$this->generate();
		
		$msg=null;
		$detail=null;
		if(isset($tMessage['success']) and $tMessage['success']==1){
			$sModuleName=_root::getParam('modulename');
			$msg='Cr&eacute;ation du module '.$sModuleName;
			$detail='
			Cr&eacute;ation du repertoire module/'.$sModuleName.'<br/>
			Cr&eacute;ation du fichier module/'.$sModuleName.'/main.php<br/>
			Cr&eacute;ation du repertoire module/'.$sModuleName.'/view<br/>
			Cr&eacute;ation du fichier module/'.$sModuleName.'/view/login.php<br/>
			<br/>
			Editez votre fichier <a target="_blank" href="'._root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'conf/site.ini.php')).'">conf/site.ini.php</a> et indiquez <br/>
			<div style="padding:8px;border:2px dotted gray">
			[auth]<br/>
			enabled=1<br/>
			et<br/>
			module='.$sModuleName.'::login
			</div>
			';
			
			
		}
	
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array();
		$tRowMethodes=array();
		foreach($oDir->getListFile() as $oFile){
			if(preg_match('/.sample.php/',$oFile->getName()) or !preg_match('/.php$/',$oFile->getName())) continue;
			$tFile[]=$oFile->getName();
			require_once( $oFile->getAdresse() );
			$sClassFoo=substr($oFile->getName(),0,-4);
			$oModelFoo=new $sClassFoo;
			
			if( method_exists( $oModelFoo, 'getListAccount') and method_exists( $oModelFoo, 'hashPassword') ){
				$tRowMethodes[substr($oFile->getName(),0,-4)]=substr($oFile->getName(),0,-4).'::getInstance()->getListAccount() et hashPassword()';
			}
		}
		
		$tModule=module_builder::getTools()->getListModule();
		$tModuleAndMethod=array();
		foreach($tModule as $oModule){
			$sModuleName=$oModule->getName();
			if(in_array($sModuleName,array('menu','builder','example','exampleembedded'))){
				continue;
			}
			include module_builder::getTools()->getRootWebsite().'module/'.$sModuleName.'/main.php';
			
			if(get_parent_class('module_'.$sModuleName)!='abstract_module'){
				continue;
			}
			
			$tMethods=get_class_methods('module_'.$sModuleName);
			foreach($tMethods as $i => $sMethod){
				if($sMethod[0]!='_' or substr($sMethod,0,2)=='__'){ 
					unset($tMethods[$i]);
				}
			}
			if(empty($tMethods)){
				continue;
			}
			$tModuleAndMethod[$sModuleName]=$tMethods;
		}
		
		
		$oTpl= new _Tpl('moduleAuth::index');
		$oTpl->tRowMethodes=$tRowMethodes;
		$oTpl->tModuleAndMethod=$tModuleAndMethod;
		$oTpl->tMessage=$tMessage;
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		
		return $oTpl;
	}
	private function generate(){
		if(!_root::getRequest()->isPost()){
			return null;
		}
		
		$oPluginValid=new plugin_valid(_root::getRequest()->getParams());
		$oPluginValid->isNotEmpty('modulename','Le champ doit &ecirc;tre rempli');
		$oPluginValid->isNotEmpty('classmodel','Le champ doit &ecirc;tre rempli');
		$oPluginValid->isNotEmpty('redirect','Le champ doit &ecirc;tre rempli');
		
		if(!$oPluginValid->isValid()){
			return $oPluginValid->getListError();
		}
		
		$sModuleName=_root::getParam('modulename');
		$sClassModuleName='module_'.$sModuleName;
		$sRedirectModuleAction=_root::getParam('redirect');
		$sModelName=_root::getParam('classmodel');
		$sViewName=$sModuleName.'::login';
		
		$sContent=module_builder::getTools()->stringReplaceIn(array(
										'module_exampleauth' => $sClassModuleName,
										'privatemodule_action' => $sRedirectModuleAction,
										'model_example' => $sModelName,
										'auth_login' => $sViewName, 
										
									),
									'data/sources/fichiers/module/auth/main.php'
		);
		
		module_builder::getTools()->projetmkdir('module/'.$sModuleName);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleName.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
		
		module_builder::getTools()->projetmkdir('module/'.$sModuleName.'/view');
		
		$oFile=new _file('data/sources/fichiers/module/auth/view/login.php');
		$sContent=$oFile->getContent();
		
		$oNewViewFile=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleName.'/view/login.php');
		$oNewViewFile->setContent($sContent);
		$oNewViewFile->save();
		
		return array('success'=>1);
		
		
	}
	
	
}
