<?php
class module_mods_bootstrap_auth extends abstract_moduleBuilder{

	protected $sModule='mods_bootstrap_auth';
	protected $sModuleView='mods/bootstrap/auth';

	private $msg=null;
	private $detail=null;
	private $tError=null;
	
	public function _index(){
		$tMessage=$this->process();

		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');

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
		if(_root::getParam('model')==null){
			$tRowMethodes=array();	
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
		
		$tColumnAccount=null;
		$sClassAccount=_root::getParam('model');
		if($sClassAccount){
			$sClassAccount=substr($sClassAccount,0,-4);
			$tColumnAccount=module_builder::getTools()->getListColumnFromClass($sClassAccount);
		}
		
		$oTpl= $this->getView('index');
		$oTpl->tRowMethodes=$tRowMethodes;
		$oTpl->tModuleAndMethod=$tModuleAndMethod;
		$oTpl->tFile=$tFile;
		$oTpl->tColumnAccount=$tColumnAccount;
		
		$oTpl->tError=$this->tError;
		$oTpl->msg=$this->msg;
		$oTpl->detail=$this->detail;

		$oTpl->tMessage=$tMessage;

		return $oTpl;
	}
	private function process(){
		if(_root::getRequest()->isPost()==false or _root::getParam('formu')!='generate'){
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


		$this->projectMkdir('module/'.$sModuleName);
		/*SOURCE*/$oSourceMain=$this->getObjectSource('example/main.php');
		/*SOURCE*/$oSourceMain->setPattern('#MODULE#',$sModuleName);

		/*SOURCE*/$oSourceMain->setPattern('#privatemodule_action#',$sRedirectModuleAction);
		/*SOURCE*/$oSourceMain->setPattern('#model_example#',$sModelName);
		/*SOURCE*/$oSourceMain->setPattern('#auth_login#',$sViewName);
		/*SOURCE*/$oSourceMain->save();


		$this->projectMkdir('module/'.$sModuleName.'/view');
		/*SOURCE*/$oSourceViewLogin=$this->getObjectSource('example/view/login.php');
		/*SOURCE*/$oSourceViewLogin->setPattern('#MODULE#',$sModuleName);
		/*SOURCE*/$oSourceViewLogin->save();
		
		
		
		$sModuleName=_root::getParam('modulename');
		$this->msg='Cr&eacute;ation du module '.$sModuleName;
		$this->detail=trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModuleName));
		$this->detail.='<br/>'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleName.'/main.php'));
		$this->detail.='<br/>'.trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModuleName));
		$this->detail.='<br/>'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModuleName.'/view/login.php'));
		
		$this->detail.='<br/>';

		$this->detail.='<br/>'.trR('editezVotreFichier',array('#link#'=>'<a target="_blank" href="'._root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'conf/site.ini.php')).'">conf/site.ini.php</a>'));
		
		$this->detail.= '<br/>
		<div style="padding:8px;border:2px dotted gray">
		[auth]<br/>
		enabled=1<br/>
		'.tr('et').'<br/>
		module='.$sModuleName.'::login
		</div>
		';


	}

}