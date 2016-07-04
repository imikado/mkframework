<?php
class module_mods_bootstrap_viewForm extends abstract_moduleBuilder{

	protected $sModule='mods_bootstrap_viewForm';
	protected $sModuleView='mods/bootstrap/viewForm';

	private $msg=null;
	private $detail=null;
	private $tError=null;

	public function _index(){

		$msg='';
		$detail='';
		
		list($msg,$detail)=$this->processSimple();
		
	    module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array();
		$tRowMethodes=array();
		
		$tModule=module_builder::getTools()->getListModule();
		
		foreach($oDir->getListFile() as $oFile){
			if(preg_match('/.sample.php/',$oFile->getName()) or !preg_match('/.php$/',$oFile->getName())) continue;
			$tFile[]=$oFile->getName();
			require_once( $oFile->getAdresse() );
			$sClassFoo=substr($oFile->getName(),0,-4);
			$oModelFoo=new $sClassFoo;
			
			if( method_exists( $oModelFoo, 'getSelect')){
				$tRowMethodes[substr($oFile->getName(),0,-4)]=substr($oFile->getName(),0,-4).'::getSelect()';
			}
		}
		
		$oTpl= $this->getView('index');
		
		if(_root::getParam('class') !='' ){
		
			$sClass=substr(_root::getParam('class'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');


			$tColumn=module_builder::getTools()->getListColumnFromClass($sClass);
			$oTpl->sClass=$sClass;
			
			$sId=null;
			$tId=module_builder::getTools()->getIdTabFromClass($sClass);
			foreach($tColumn as $i => $sColumn){
				if(in_array($sColumn, $tId) ){
					unset($tColumn[$i]);
					$sId=$sColumn;
				}
			}
			
			$oTpl->tColumn=$tColumn;
			$oTpl->sId=$sId;
			
			$oTpl->tRowMethodes=$tRowMethodes;

			$oModel=new $sClass;
			 
			
			
		}
		
	 
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		$oTpl->tModule=$tModule;
		return $oTpl;
		
	}
	
	private function processSimple(){
		if(!_root::getRequest()->isPost()){
			return array(null,null);
		}
		
		$ret="\n";
		$t="\t";
		
		$sModule=_root::getParam('module');
		$sView=_root::getParam('view');
		$tEnable=_root::getParam('tEnable');
		$tColumn=_root::getParam('tColumn');
		$tLabel=_root::getParam('tLabel');
		$tType=_root::getParam('tType');
		$sModel=_root::getParam('class');
		$sModel=str_replace('.php','',$sModel);
		$sId=_root::getParam('sId');
		
		
		/*SOURCE*/$oSourceView=$this->getObjectSource('example/view/example.php');
		
		$colonnestr=null;

		foreach($tColumn as $i => $sColumn){
			
			if(!in_array($sColumn,$tEnable)){
				continue;
			}
			
			$tVar=array();
			
			if($tType[$i]=='text'){
				$sFormInput=$oSourceView->getSnippet('getInputText',array('#column#'=>$sColumn));
			}else if($tType[$i]=='textarea'){
				$sFormInput=$oSourceView->getSnippet('getInputTextarea',array('#column#'=>$sColumn));
			}else if(substr($tType[$i],0,6)=='select'){
				$tVar[]=$t.'$oView->tJoin'.$sColumn.'='.substr($tType[$i],7).'::getInstance()->getSelect();';
				$sFormInput=$oSourceView->getSnippet('getSelect',array('#column#'=>$sColumn));
			}else if($tType[$i]=='upload'){
				$sFormInput=$oSourceView->getSnippet('upload',array('#column#'=>$sColumn));
			}else{
				$sFormInput=$oSourceView->getSnippet('getInputText',array('#column#'=>$sColumn));
			}

			$colonnestr.=$oSourceView->getSnippet('ligneTrTd',array('#th#'=>$tLabel[$i],'#td#'=>$sFormInput));
			
		}
		
		/*SOURCE*/$oSourceView->setPattern('#colonnestr#',$colonnestr);

		/*SOURCE*/$oSourceView->setPattern('#examplemodule#',$sModule);
		/*SOURCE*/$oSourceView->setPattern('#exampleview#',$sView);

		
		if($oSourceView->exist()){
			return array('Vue deja existante',null);
		}
		
		/*SOURCE*/$oSourceView->save();		
		
		
		$sModel=str_replace('.php','',$sModel);
		
		$msg='Vue '.$sView.'.php'.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
		$detail='';
		$detail.='Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sView.'.php ';
		
		$sCode='<?php '."\n";
		
		$sCode.='public function _edit(){'.$ret;
		
			$sCode.=$t.'$tMessage=$this->processSave();'.$ret;
		
			$sCode.=$t.'//recupere l\'enregistrement'."\n";
			$sCode.=$t.'$oData='.$sModel.'::getInstance()->findById(_root::getParam(\'id\'));'.$ret.$ret;
			
			$sColumn='';
			if($tEnable){
				foreach($tEnable as $sEnable){
					$sColumn.=','."'".$sEnable."'";
				}
				$sColumn=substr($sColumn,1);
			}
	
			$sCode.=$t.'//recupere la vue du module'."\n";
			$sCode.=$t.'$oView=new _view(\''.$sModule.'::'.$sView.'\');'."\n";
			$sCode.=$t.'$oView->oData=$oData;'.$ret;
			$sCode.=$t.'$oView->tMessage=$tMessage;'.$ret;
			$sCode.=$ret;
			$sCode.=$t.'$oPluginXsrf=new plugin_xsrf();'.$ret;
			$sCode.=$t.'$oView->token=$oPluginXsrf->getToken();'.$ret;
			
			if($tVar){
				foreach($tVar as $sVar){
					$sCode.=$sVar.$ret;
				}
			}
		
			$sCode.="\n";
			$sCode.=$t.'//assigner la vue retournee a votre layout'."\n";
			$sCode.=$t.'$this->oLayout->add(\'main\',$oView);'."\n";
		
		$sCode.='}'.$ret;
		$sCode.='private function processSave(){'.$ret;
			//post
			$sCode.=$t.'if(!_root::getRequest()->isPost()){'.$ret;
				$sCode.=$t.$t.'return null;'.$ret;
			$sCode.=$t.'}'.$ret;
			
			$sCode.=$ret;
			
			$sCode.=$t.'$oPluginXsrf=new plugin_xsrf();'.$ret;
			$sCode.=$t.'if(!$oPluginXsrf->checkToken( _root::getParam(\'token\') ) ){ //on verifie que le token est valide'.$ret;
				$sCode.=$t.$t.'return array(\'token\'=>$oPluginXsrf->getMessage() );'.$ret;
			$sCode.=$t.'}'.$ret;
			
			$sCode.=$t.'$oData='.$sModel.'::getInstance()->findById(_root::getParam(\'id\'));'.$ret.$ret;
		
			$sCode.=$t.'$tColumn=array('.$sColumn.');'.$ret;
			$sCode.=$t.'foreach($tColumn as $sColumn){'.$ret;
				$sCode.=$t.$t.'$oData->$sColumn=_root::getParam($sColumn);'.$ret;
			$sCode.=$t.'}'.$ret;
			$sCode.=$t.'if($oData->save() ){'.$ret;
				$sCode.=$t.$t.'//redirection vers la page d\'apres'.$ret;
			$sCode.=$t.'}else{'.$ret;
				$sCode.=$t.$t.'$tMessage=$oData->getListError();'.$ret;
			$sCode.=$t.'}'.$ret;
		$sCode.='}'.$ret.$ret;
		
		
		$detail.='<br/><br/>Pour l\'utiliser, indiquez dans votre module:<br />
		'.highlight_string($sCode,1);
		
		 
		
		return array($msg,$detail);
	}

}