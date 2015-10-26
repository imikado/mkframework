<?php
class module_mods_bootstrap_moduleEmbedded extends abstract_moduleBuilder{

	protected $sModule='mods_bootstrap_moduleEmbedded';
	protected $sModuleView='mods/bootstrap/moduleEmbedded';
	
	public function _index(){
		$msg='';
		$detail='';
		if($this->isPost()){
			$sModule=_root::getParam('module');
			$sActions=_root::getParam('actions');
			
			$tAction=explode("\n",$sActions);

			
			if($this->projectMkdir('module/'.$sModule)==true){
			  $detail=trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModule));
			}else{
			  $detail=trR('repertoireDejaExistant',array('#REPERTOIRE#'=>'module/'.$sModule));
			}
			
			if($this->projectMkdir('module/'.$sModule.'/view')==true){
			  $detail.='<br />'.trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModule.'/view'));
			}else{
			  $detail.='<br />'.trR('repertoireDejaExistant',array('#REPERTOIRE#'=>'module/'.$sModule.'/view'));
			}

			$this->genModuleMain($sModule,$tAction);
			
			$msg=trR('moduleGenereAvecSucces',array('#MODULE#'=>$sModule,'#listACTION#'=>implode(',',$tAction)));
			
			$detail.='<br />'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModule.'/main.php'));
			foreach($tAction as $sAction){
				$detail.='<br />'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModule.'/view/'.$sAction.'.php'));
			}
			

			$sCode='<?php '."\n";
			$sCode.='//instancier le module'."\n";
			$sCode.='$oModule'.ucfirst(strtolower($sModule)).'=new module_'.$sModule.";\n";
			$sCode.='//recupere la vue du module'."\n";
			$sCode.='$oView=$oModule'.ucfirst(strtolower($sModule)).'->_index();'."\n";
			$sCode.="\n";
			$sCode.='//assigner la vue retournee a votre layout'."\n";
			$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
			
			$detail.='<br/><br/>'.tr('pourLutiliserIndiquez').':<br />
			'.highlight_string($sCode,1);
		}
	
		$oTpl= $this->getView('index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	private function genModuleMain($sModule,$tAction){

		/*SOURCE*/$oSourceMain=$this->getObjectSource('example/main.php');
		/*SOURCE*/$oSourceMain->setPattern('#MODULE#',$sModule);
		
		$sMethodes='';
		foreach($tAction as $sAction){
			$sAction=trim($sAction);
			if($sAction=='') continue;

			/*SOURCE*/$oSourceView=$this->getObjectSource('example/view/exampletpl.php');
			/*SOURCE*/$oSourceView->setPattern('#MODULE#',$sModule);
			/*SOURCE*/$oSourceView->setPattern('#VIEW#',$sAction);

			$sMethodes.=$oSourceMain->getSnippet(
						'methodAction',
						array(
							'#MODULE#'=>$sModule,
							'#ACTION#'=>$sAction
						)
			);

			$oSourceView->save();

		}
				
		/*SOURCE*/$oSourceMain->setPattern('#METHODS#',$sMethodes);
		/*SOURCE*/$oSourceMain->save();
	}
	
}
