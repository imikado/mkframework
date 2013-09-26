<?php
class module_moduleCrudEmbeddedReadonly{
	
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
		
		$oTpl= new _Tpl('moduleCrudEmbeddedReadonly::index');
		
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

			$this->genModelMainCrudembeddedReadonly($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn);
			$this->genModelTplCrudembeddedReadonly($sModuleToCreate,$sClass,$tColumn,$oModel->getTable());
			
			$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire module/'.$sModule;
			$detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/list.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/edit.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/new.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/show.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/delete.php';
			
			$sCode='<?php '."\n";
			$sCode.='//instancier le module'."\n";
			$sCode.='$oModule'.ucfirst(strtolower($sModule)).'=new module_'.$sModule.";\n\n";
			
			$sCode.='//si vous souhaitez indiquer au module integrable des informations sur le module parent'."\n";
			$sCode.='//$oModuleExamplemodule->setRootLink(\'module::action\',array(\'parametre\'=>_root::getParam(\'parametre\')));'."\n\n";
			
			$sCode.='//recupere la vue du module'."\n";
			$sCode.='$oView=$oModule'.ucfirst(strtolower($sModule)).'->_index();'."\n";
			$sCode.="\n";
			$sCode.='//assigner la vue retournee a votre layout'."\n";
			$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
			
			$detail.='<br/><br/>Pour l\'utiliser, indiquez:<br />
			'.highlight_string($sCode,1);
			
		}
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		return $oTpl;
	}
	
	
	public function genModelMainCrudembeddedReadonly($sModule,$sTableName,$sClass,$tColumn){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$oFile=new _file('data/sources/fichiers/module/crudembeddedreadonly/main.php');
		$sContent=$oFile->getContent();
		preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
		$sInputSelect=$tMatch[1][0];

		preg_match_all('/#uploadsave(.*)?#fin_uploadsave/s',$sContent,$tMatch);
		$sInputUpload=$tMatch[1][0];
	
		$sInputUpload=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInputUpload);
	
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
										'oModuleExamplemodule' => 'oModule'.ucfirst($sModule),
										'examplemodule' => $sModule,
										'examplemodel' => $sTableName,
										'\/\/icishow' => $sTable,
										'\/\/icinew' => $sTable,
										'\/\/iciedit' => $sTable,
										'\/\/icilist' => $sTable,
										'\/\/iciuploadsave' => $sInputUpload,
										'<\?php\/\*variables(.*)variables\*\/\?>' => '',
									),
									'data/sources/fichiers/module/crudembeddedreadonly/main.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	public function genModelTplCrudembeddedReadonly($sModule,$sClass,$tColumn,$sTableName){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$tTpl=array('list','show');
		
		foreach($tTpl as $sTpl){
			
			$oFile=new _file('data/sources/fichiers/module/crudembeddedreadonly/view/'.$sTpl.'.php');
			$sContent=$oFile->getContent();	
				
				preg_match_all('/#lignetd(.*)?#fin_lignetd/s',$sContent,$tMatch);
				$sLigne=$tMatch[1][0];
				
				preg_match_all('/#input(.*)?#fin_input/s',$sContent,$tMatch);
				$sInputText=$tMatch[1][0];
				
				preg_match_all('/#textarea(.*)?#fin_textarea/s',$sContent,$tMatch);
				$sInputTextarea=$tMatch[1][0];
				
				preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
				$sInputSelect=$tMatch[1][0];

				preg_match_all('/#upload(.*)?#fin_upload/s',$sContent,$tMatch);
				$sInputUpload=$tMatch[1][0];

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
					}elseif($sType=='textarea'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputTextarea);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					}elseif(substr($sType,0,7)=='select;'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputSelect);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
						$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInput);
					}elseif($sType=='upload'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputUpload);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);

						$sEnctype=' enctype="multipart/form-data"';//changement du enctype du formulaire
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
											'data/sources/fichiers/module/crudembeddedreadonly/view/'.$sTpl.'.php'
				);
			
			
			
			
			$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sTpl.'.php' );
			$oFile->setContent($sContent);
			$oFile->save();
			$oFile->chmod(0777);
		}
		
		
		
	}
	
}
