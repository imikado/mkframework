<?php
class module_moduleCrudReadonly{
	
	public function _index(){
	    module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array();
		$tRowMethodes=array();
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
		
		$oTpl= new _Tpl('moduleCrudReadonly::index');
		
		if(_root::getParam('class') !='' ){
		
			$sClass=substr(_root::getParam('class'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');


			$tColumn=module_builder::getTools()->getListColumnFromClass($sClass);
			$oTpl->sClass=$sClass;
			
			$tId=module_builder::getTools()->getIdTabFromClass($sClass);
			foreach($tColumn as $i => $sColumn){
				if(in_array($sColumn, $tId) ){
					unset($tColumn[$i]);
				}
			}
			
			$oTpl->tColumn=$tColumn;
			
			$oTpl->tRowMethodes=$tRowMethodes;

			$oModel=new $sClass;
			$oTpl->sModuleToCreate=$oModel->getTable();
		}
		
		if(_root::getRequest()->isPost() ){
			$sModuleToCreate=_root::getParam('moduleToCreate');
			$sClass=_root::getParam('sClass');
			$tColumn=_root::getParam('tColumn');
			$tType=_root::getParam('tType');
			$tEnable=_root::getParam('tEnable');

			foreach($tColumn as $i => $sColumn){
				if(!in_array($sColumn, $tEnable) ){
					unset($tColumn[$i]);
				}
			}
			
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');
			$oModel=new $sClass;
			$sModule=$sModuleToCreate;
			module_builder::getTools()->projetmkdir('module/'.$sModule );
			module_builder::getTools()->projetmkdir('module/'.$sModule.'/view');

			$this->genModelMainCrudReadonly($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn);
			$this->genModelTplCrudReadonly($sModuleToCreate,$sClass,$tColumn,$oModel->getTable());
			
			$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire module/'.$sModule;
			$detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/list.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/edit.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/new.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/show.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/delete.php';
			
			$detail.='<br/><br/>Pour y acc&eacute;der <a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModule.'::index">cliquer ici (index.php?:nav='.$sModule.'::index)</a>';
			
		}
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		return $oTpl;
	}
	private function genModelMainCrudReadonly($sModule,$sTableName,$sClass,$tColumn){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$oFile=new _file('data/sources/fichiers/module/crudreadonly/main.php');
		$sContent=$oFile->getContent();
		preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
		$sInputSelect=$tMatch[1][0];
	
		$sTable='';
		foreach($tColumn as $i => $sColumn){
			$sType=$tType[$i];
			if(substr($sType,0,7)=='select;'){
				$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInputSelect);
				$sTable.=$sInput;
			}
			
		}
		$sContent=module_builder::getTools()->stringReplaceIn(array(
										'oExamplemodel' => 'o'.ucfirst($sTableName),
										'tExamplemodel' => 't'.ucfirst($sTableName),
										'examplemodule' => $sModule,
										'examplemodel' => $sTableName,
										'\/\/icishow' => $sTable,
										
										'\/\/icilist' => $sTable,
										
										'<\?php\/\*variables(.*)variables\*\/\?>' => '',
									),
									'data/sources/fichiers/module/crudreadonly/main.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	private function genModelTplCrudReadonly($sModule,$sClass,$tColumn,$sTableName){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$tTpl=array('list','show');
		
		foreach($tTpl as $sTpl){
			
			$oFile=new _file('data/sources/fichiers/module/crudreadonly/view/'.$sTpl.'.php');
			$sContent=$oFile->getContent();	
				
				preg_match_all('/#lignetd(.*)?#fin_lignetd/s',$sContent,$tMatch);
				$sLigne=$tMatch[1][0];
				
				preg_match_all('/#input(.*)?#fin_input/s',$sContent,$tMatch);
				$sInputText=$tMatch[1][0];
				
				preg_match_all('/#textarea(.*)?#fin_textarea/s',$sContent,$tMatch);
				$sInputTextarea=$tMatch[1][0];
				
				preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
				$sInputSelect=$tMatch[1][0];

				if($sTpl=='list'){
				//TH
				preg_match_all('/#ligneth(.*)?#fin_ligneth/s',$sContent,$tMatch);
				$sLigneTH=$tMatch[1][0];
				}				

				$sTable='';
				$sTableTh='';
				$sEnctype='';
				foreach($tColumn as $i => $sColumn){
					$sType=$tType[$i];
					if($sType=='text' or $sType=='date'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputText);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					
					}elseif(substr($sType,0,7)=='select;'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputSelect);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
						$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInput);
					
					}
					$sTable.=preg_replace('/examplecolumn/',$sColumn,preg_replace('/exampletd/',$sInput,$sLigne));

					$sTableTh.=preg_replace('/exampleth/',$sColumn,$sLigneTH);
				}
				$sContent=module_builder::getTools()->stringReplaceIn(array(
												'oExamplemodel' => 'o'.ucfirst($sTableName),
												'tExamplemodel' => 't'.ucfirst($sTableName),
												'examplemodule' => $sModule,
												'<\?php \/\/enctype\?>' => $sEnctype,
												'<\?php \/\/ici\?>' => $sTable,
												'<\?php \/\/icith\?>' => $sTableTh,
												'<\?php\/\*variables(.*)variables\*\/\?>' => '',
											),
											'data/sources/fichiers/module/crudreadonly/view/'.$sTpl.'.php'
				);
			
			
			
			
			$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sTpl.'.php' );
			$oFile->setContent($sContent);
			$oFile->save();
			$oFile->chmod(0777);
		}
		
		
		
	}
	
	
	
}
