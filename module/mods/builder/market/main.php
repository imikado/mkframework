<?php
class module_mods_builder_market extends abstract_moduleBuilder{

	protected $sModule='mods_builder_market';
	protected $sModuleView='mods/builder/market';

	private $msg=null;
	private $detail=null;
	private $tError=null;
	private $errorZip=null;

	public function _menu(){
		$tLink=array(
			'verifierLesMisesAjour'=>'updates',
			'ajouterUneExtension'=>'install',
		);

		$oTpl= $this->getView('menu');
		
		$oTpl->tLink=$tLink;
		
		return $oTpl;
	}
	
	public function _index(){
		
		$sAction=_root::getParam('action');
		
		if($sAction=='updates'){
			return $this->getActionUpdates();
		}else if($sAction=='install'){
			return $this->getActionPackages();
		}else if($sAction=='installExtBuilder'){
			return $this->getActionInstallExtBuilder();
		}
		return $this->getView('accueil');
	}
	private function getActionUpdates(){

		$message=$this->processUpdates();

		if(!is_writable(_root::getConfigVar('path.module').'mods/')){
			$message=sprintf(tr('builder::new_errorVotreRepertoirePasInscriptible'),_root::getConfigVar('path.module').'mods/');
		}

		$sLang=_root::getConfigVar('language.default');

		$sUrl=_root::getConfigVar('market.builder.versions.url');
		try{
			$sRemoteVersions=file_get_contents($sUrl);
		}catch(Exception $e){
			$oView=$this->getView('error');
			$oView->message=trR('problemeUrlmarket',array('#message#'=>$e->getMessage()));
			return $oView;
		}
		$tRemoteIni=parse_ini_string($sRemoteVersions);

		$tType=array('all','normal','bootstrap','builder');
		$tLinkModule=array();

		$tDetail=array();

		foreach($tType as $sType){
			$sPathModule=_root::getConfigVar('path.module').'/mods/'.$sType;
			
			$tModulesAll=scandir($sPathModule);
			foreach($tModulesAll as $sModule){
				if(file_exists($sPathModule.'/'.$sModule.'/info.ini')){
					if(!isset($tRemoteIni['mods_'.$sType.'_'.$sModule])){
						continue;
					}

					$tIni=parse_ini_file($sPathModule.'/'.$sModule.'/info.ini');
					$priority=999;
					if(isset($tIni['priority'])){
						$priority=$tIni['priority'];
					}
					$sPriority=sprintf('%03d',$priority);

					$tIni['remoteVersion']=$tRemoteIni['mods_'.$sType.'_'.$sModule];
					$tDetail['mods_'.$sType.'_'.$sModule]=$tIni;

					$sup=null;
					if($sType=='bootstrap'){
						$sup='<sup>Bootstrap</sup>';
					}
					$tLinkModule[ $tIni['category'] ][ $tIni['title.'.$sLang].$sup ]=$sPriority.'mods_'.$sType.'_'.$sModule;
				}
			}

		}

		$tTitle=array(
			'market',
			'coucheModel',
			'modules',
			'modulesEmbedded',
			'views',
			'databasesEmbedded',
			'unitTest',
			'builder',
		);
		$tModule=array();
		foreach($tTitle as $sTitle){
			if(isset($tLinkModule[$sTitle])){

				$tLinkModuleCat=$tLinkModule[$sTitle];
				asort($tLinkModuleCat);
			
				$tModule[ tr('menu_'.$sTitle) ]='title';

				foreach($tLinkModuleCat as $sLabel => $sId){
					$idModule=substr($sId,3);

					$localVersion=$tDetail[$idModule]['version'];
					$remoteVersion=$tDetail[$idModule]['remoteVersion'];
					$author=$tDetail[$idModule]['author'];

					$tModule[ $sLabel ]=array('author'=>$author,'local'=>$localVersion,'remote'=>$remoteVersion,'id'=>$idModule);
				}
			}
		}

		

		$oView=$this->getView('updates');
		$oView->tModule=$tModule;
		$oView->message=$message;
		$oView->tDetail=$tDetail;
		return $oView;
	}
	private function processUpdates(){
		if(!_root::getRequest()->isPost()){
			return null;
		}

		$sUrl=_root::getConfigVar('market.builder.versions.url');
		try{
			$sRemoteVersions=file_get_contents($sUrl);
		}catch(Exception $e){
			$oView=$this->getView('error');
			$oView->message=trR('problemeUrlmarket',array('#message#'=>$e->getMessage()));
			return $oView;
		}
		$tRemoteIni=parse_ini_string($sRemoteVersions);

		$sRootUrl=_root::getConfigVar('market.builder.url');

		$tModuleToUpdate=_root::getParam('toUpdate');
		if($tModuleToUpdate){
			foreach($tModuleToUpdate as $sModule){
				$sPathModule=str_replace('_','/',$sModule);
				if(!$this->unzipTo($sRootUrl.'/module/'.$sModule.$tRemoteIni[$sModule].'.zip',_root::getConfigVar('path.module').'/'.$sPathModule)){
					return $this->errorZip;
				}

			}
		}
	}
	private function installExtBuilderInProject($sModule,$sVersion){
		$sRootUrl=_root::getConfigVar('market.builder.url');
		$sPathModule=str_replace('_','/',$sModule);
		if(!$this->unzipTo($sRootUrl.'/module/'.$sModule.$sVersion.'.zip',_root::getConfigVar('path.module').'/'.$sPathModule) ){
			return $this->errorZip;
		}

	}
	private function unzipTo($sUrl,$sTarget){
		try{
			file_put_contents($sTarget.'.zip',file_get_contents($sUrl));
		}
		catch(Exception $e){
			$this->errorZip=trR('urlNonDispo',array('#url#'=>$sUrl));
			return false;
		}

		$zip = new ZipArchive;
		if ($zip->open($sTarget.'.zip')){
		    
		    $zip->extractTo($sTarget);
		    $zip->close();

		    try{
		    	chmod($sTarget,0777);
		    }catch(Exception $e){

		    }
		    //menage
		    unlink($sTarget.'.zip');
		}
		return true;
	}
	/*
	pages/fr/index.xml
	<?xml version="1.0" ?>
	<page>
		<type>content</type>
		<title>test</title>
		<content>toto</content>
		<nav>
			<link href="index">accueil</link>
			<link href="bootstrap_list_1">Bootstrap</link>
		</nav>
	</page>

	pages/fr/bootstrap_list_1.xml
	<?xml version="1.0" ?>
	<page>
		<type>list</type>
		<title>test</title>
		<content>presentation bootstrap</content>
		<nav>
			<link href="index">accueil</link>
			<link href="bootstrap_list_1">Bootstrap</link>
		</nav>
		<data>
			<bloc>
				<title>couche model</title>
				<id>mods_all_model</id>
			</bloc>
			<bloc>
				<title>module crud</title>
				<id>mods_bootstrap_</id>
			</bloc>
		</data>
	</page>
	*/
	private function getActionPackages(){


		$tLocalIni=$this->getListIni();

		$sPage=_root::getParam('market','index');

		$oXml=$this->getRemoteMarketPage($sPage);

		$tType=array('content','list','detail');
		$sType=(string)$oXml->type;
		if(!in_array($sType,$tType)){
			return $this->getView('error');
		}


		$oViewHead=$this->getView('market_head');
		$oViewHead->tNav=$this->getNav($oXml->nav->link);
		$sViewHead=$oViewHead->show();


		$oView=$this->getView('market_'.$sType);
		$oView->sHead=$sViewHead;
		$oView->title=(string)$oXml->title;
		$oView->content=(string)$oXml->content;

		if($sType=='list'){
			$oView->tBloc=$this->getBlocs($oXml->data->bloc);
		}else if($sType=='detail'){
			$oView->id=(string)$oXml->id;
			$oView->version=(string)$oXml->version;
			$oView->author=(string)$oXml->author;
		}

		$oView->tLocalIni=$tLocalIni;

		return $oView;
	}
	private function getActionInstallExtBuilder(){

		$sError= $this->installExtBuilderInProject(_root::getParam('id'),_root::getParam('version'));

		$oView=$this->getView('market_install');
		$oView->error=$sError;

		return $oView;
	}

	private function getNav($tXmlNav){
		$tNav=array();
		foreach($tXmlNav as $oXml){
 			$tNav[ (string)$oXml['href'] ]=(string)$oXml;
		}
		return $tNav;
	}
	private function getBlocs($tXmlBlocs){
		$tBlocs=array();
		foreach($tXmlBlocs as $oXmlBloc){
			$tBlocs[]=array(
				'title'=>(string)$oXmlBloc->title,
				'id'=>(string)$oXmlBloc->id,
				'author'=>(string)$oXmlBloc->author,
				'version'=>(string)$oXmlBloc->version,
				);
		}
		return $tBlocs;
	}
	public function getRemoteMarketPage($sPage){
		$sRootUrl=_root::getConfigVar('market.builder.url');
		$sRootUrl.='pages/';
		$sRootUrl.=_root::getConfigVar('language.default');

		$sXml=file_get_contents($sRootUrl.'/'.$sPage.'.xml');
		$oXml=simplexml_load_string($sXml);

		return $oXml;
	}

	public static function getMarketLink($sAction){
		return _root::getLink('builder::marketBuilder',array('action'=>'install','market'=>$sAction));
	}
	public static function getInstallLink($sId,$sVersion){
		return _root::getLink('builder::marketBuilder',array('action'=>'installExtBuilder','id'=>$sId,'version'=>$sVersion));
	}

	public function getListIni(){
		$tType=array('all','normal','bootstrap','builder');
		$tLinkModule=array();

		$tLocalIni=array();

		foreach($tType as $sType){
			$sPathModule=_root::getConfigVar('path.module').'/mods/'.$sType;
			
			$tModulesAll=scandir($sPathModule);
			foreach($tModulesAll as $sModule){
				if(file_exists($sPathModule.'/'.$sModule.'/info.ini')){
					

					$tIni=parse_ini_file($sPathModule.'/'.$sModule.'/info.ini');

					$tLocalIni['mods_'.$sType.'_'.$sModule]=$tIni['version'];

				}
			}
		}

		return $tLocalIni;
	}

}