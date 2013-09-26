<?php
class module_moduleMenu{
	
	public function _index(){
	    
		$detail=null;
		$tError=array();
		$tModuleAndMethod=array();
		$bExist=0;
		if(_root::getRequest()->isPost()){
		    
			$sModule=_root::getParam('modulename');
			$tMethod=_root::getParam('tMethod');
			$tLabel=_root::getParam('tLabel');
		    
		    
		    $ok=1;
		    
		    //check formulaire
		    foreach($tMethod as $i => $sMethod){
				if($tLabel[$i]==''){
					$tError[$i]='Remplissez le libell&eacute; du lien';
					$ok=0;
				}
			}
			
			if($ok){
				
				if(module_builder::getTools()->projetmkdir('module/'.$sModule)==true){
					$detail='Cr&eacute;ation repertoire module/'.$sModule;

					if(module_builder::getTools()->projetmkdir('module/'.$sModule.'/view')==true){
						$detail.='<br />Cr&eacute;ation r&eacute;pertoire module/'.$sModule.'/view';
					
						$this->genModuleMenuMain($sModule,$tMethod,$tLabel);

						$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';

						$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
						$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/index.php';


						$sCode='<?php '."\n";
						$sCode.='//assignez le menu a l\'emplacement menu'."\n";
						$sCode.='$this->oLayout->addModule(\'menu\',\''.$sModule.'::index\');'."\n";

						$detail.='<br/><br/>Pour l\'utiliser, ajoutez dans votre methode before():<br />
						'.highlight_string($sCode,1);
					
					}else{
						$detail.='<br />Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule.'/view';
					}

					
				}else{
				  $detail='Warning: repertoire module/'.$sModule.' d&eacute;j&agrave; existant: modifiez le nom du module menu';
				}
				
			}
			
		    
		}else{
	    
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
			
			$oDir=new _dir(module_builder::getTools()->getRootWebsite().'module/menu');
			$bExist=$oDir->exist();
		}
	    
		$oView=new _view('moduleMenu::index');
		$oView->tModuleAndMethod=$tModuleAndMethod;
		$oView->detail=$detail;
		$oView->bExist=$bExist;
		$oView->tError=$tError;
		return $oView;
	}
	private function genModuleMenuMain($sModuleMenuName,$tMethod,$tLabel){
	    
	    $sData=null;
	    foreach($tMethod as $i => $sLink){
		$sData.='\''.$tLabel[$i].'\' => \''.$sLink.'\','."\n";
	    }
	    
	    $oMainFile=new _file('data/sources/fichiers/module/menu/main.php');
	    $sContentMain=$oMainFile->getContent();
	    
	    $sContentMain=preg_replace('/\/\/TABLEAUICI/',$sData,$sContentMain);
	    $sContentMain=preg_replace('/examplemenu/',$sModuleMenuName,$sContentMain);
	    
	    
	    $oFileTpl=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleMenuName.'/main.php');
	    $oFileTpl->setContent($sContentMain);
	    $oFileTpl->save();
	    $oFileTpl->chmod(0666);
	    
	    $oViewFile=new _file('data/sources/fichiers/module/menu/view/index.php');
	    $sContentView=$oViewFile->getContent();
	    
	    $oFileTpl2=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleMenuName.'/view/index.php');
	    $oFileTpl2->setContent($sContentView);
	    $oFileTpl2->save();
	    $oFileTpl2->chmod(0666);
	    
	}
	
}
