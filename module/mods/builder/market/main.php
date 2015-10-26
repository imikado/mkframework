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
					$tLinkModule[ $tIni['category'] ][ $tIni['title.'.$sLang] ]=$sPriority.'mods_'.$sType.'_'.$sModule;
				}
			}

		}

		$tTitle=array(
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
	}

	private function getActionPackages(){

		$oView=$this->getView('packages');
		return $oView;
	}

}