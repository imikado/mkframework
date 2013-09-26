<?php
class module_moduleModule{
	
	public function _index(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			$sModule=_root::getParam('module');
			$sActions=_root::getParam('actions');
			
			$tAction=explode("\n",$sActions);

			
			if(module_builder::getTools()->projetmkdir('module/'.$sModule)==true){
			  $detail='Cr&eacute;ation repertoire module/'.$sModule;
			}else{
			  $detail='Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule;
			}
			
			if(module_builder::getTools()->projetmkdir('module/'.$sModule.'/view')==true){
			  $detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			}else{
			  $detail.='<br />Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule.'/view';
			}

			$this->genModuleMain($sModule,$tAction);
			
			$msg='Module '.$sModule.' (actions: '.implode(',',$tAction).') genere avec succes';
			
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			foreach($tAction as $sAction){
				$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sAction.'.php';
			}
			
			$detail.='<br />Accessible via';
			foreach($tAction as $sAction){
				$detail.='<br />- <a href="data/genere/'._root::getParam('id').'/public/index.php?:nav='.$sModule.'::'.$sAction.'">index.php?:nav='.$sModule.'::'.$sAction.'</a>';
			}
		}
	
		$oTpl= new _Tpl('moduleModule::index');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	private function genModuleMain($sModule,$tAction){
		$sContent=module_builder::getTools()->stringReplaceIn(array(
											'_examplemodule' => '_'.$sModule,
											
										),
										'data/sources/projet/module/example/main.php'
		);
		preg_match_all('/#debutaction#(.*)?#finaction#/s',$sContent,$tMatch);
		
		$sMethodeSource=$tMatch[1][0];
		
		$sMethodes='';
		foreach($tAction as $sAction){
			$sAction=trim($sAction);
			if($sAction=='') continue;
			$sMethodes.=preg_replace('/examplemodule/',$sModule,
					  preg_replace('/exampleaction/',$sAction,
														  $sMethodeSource));
														  
		  $oFileTpl=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sAction.'.php');
		  $oFileTpl->setContent('vue '.$sModule.'::'.$sAction);
		  $oFileTpl->save();
		  $oFileTpl->chmod(0666);
		}
		
		$sContent=preg_replace('/\/\/ICI--/',$sMethodes,$sContent);
		
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}
	
}
