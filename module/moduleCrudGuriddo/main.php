<?php
class module_moduleCrudGuriddo{
	
	public function _index(){
		
		$bGuriddoExist=false;
		$bGuriddoPublicExist=false;
		$bModelCountExist=false;
		$bModelPaginationExist=false;
		$bModelFilterCountExist=false;
		$bModelFilterPaginationExist=false;
		
		//check guriddo
		if(file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/module/guriddo')){
			$bGuriddoExist=true;
		}
		//check guriddo public
		if(file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/public/guriddo')){
			$bGuriddoPublicExist=true;
		}
		
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
		
		$oTpl= new _Tpl('moduleCrudGuriddo::index');
		$oTpl->bGuriddo=$bGuriddoExist;
		$oTpl->bGuriddoPublicExist=$bGuriddoPublicExist;
		
		$oTpl->pathGenerated=_root::getConfigVar('path.generation')._root::getParam('id');
		$oTpl->pathModule=_root::getConfigVar('path.generation')._root::getParam('id').'/module';
		$oTpl->pathPublic=_root::getConfigVar('path.generation')._root::getParam('id').'/public/';
		
		if(_root::getParam('class') !='' ){
			
			$sClass=substr(_root::getParam('class'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');

			$oTpl->class=$sClass;
			
			$oModel=new $sClass;
			
			if( method_exists( $oModel, 'findTotal')){
				$bModelCountExist=true;
			}
			if( method_exists( $oModel, 'findListLimitOrderBy')){
				$bModelPaginationExist=true;
			}
			if( method_exists( $oModel, 'findTotalFiltered')){
				$bModelFilterCountExist=true;
			}
			if( method_exists( $oModel, 'findListFilteredAndLimitOrderBy')){
				$bModelFilterPaginationExist=true;
			}
			

			$tColumn=module_builder::getTools()->getListColumnFromClass($sClass);
			$oTpl->sClass=$sClass;
			$oTpl->tSortColumn=$tColumn;
			
			$tId=module_builder::getTools()->getIdTabFromClass($sClass);
			foreach($tColumn as $i => $sColumn){
				if(in_array($sColumn, $tId) ){
					unset($tColumn[$i]);
				}
			}
			
			$oTpl->tColumn=$tColumn;
			
			
			$oTpl->tRowMethodes=$tRowMethodes;

			
			$oTpl->sModuleToCreate=$oModel->getTable();
		}
		
		if(_root::getRequest()->isPost() ){
			$sModuleToCreate=_root::getParam('moduleToCreate');
			$sClass=_root::getParam('sClass');
			$tColumn=_root::getParam('tColumn');
			$tLabel=_root::getParam('tLabel');
			$tType=_root::getParam('tType');
			$tEnable=_root::getParam('tEnable');
			$sDefaultField=_root::getParam('defaultSort');
			
			$tTableOption=array();
			
			$tTableOption['width']=_root::getParam('tableWidth');
			$tTableOption['height']=_root::getParam('tableHeight');
			$tTableOption['limit']=_root::getParam('tableLimit');

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
			
			$tCrud= _root::getParam('crud',null);
			
			$bWithPagination=_root::getParam('withPagination');

			$this->genModelMain($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn,$tLabel,$sDefaultField,$tTableOption,$tCrud,$bWithPagination);
			$this->genModelTpl($sModuleToCreate,$sClass,$tColumn,$oModel->getTable(),$tCrud,$tLabel);
			
			$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire module/'.$sModule;
			$detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/list.php';
			
			
			
			$detail.='<br/><br/>Pour y acc&eacute;der <a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModule.'::index">cliquer ici (index.php?:nav='.$sModule.'::index)</a>';
			
		}
		
		$oTpl->bModelFilterCountExist=$bModelFilterCountExist;
		$oTpl->bModelFilterPaginationExist=$bModelFilterPaginationExist;
		$oTpl->bModelCountExist=$bModelCountExist;
		$oTpl->bModelPaginationExist=$bModelPaginationExist;
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		return $oTpl;
	
	}
	
	private function genModelMain($sModule,$sTableName,$sClass,$tColumn,$tLabel,$sDefaultSortField,$tTableOption,$tCrud,$bWithPagination){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$oFile=new _file('data/sources/fichiers/module/crudGuriddo/main.php');
		$sContent=$oFile->getContent();
		
		$oVar=simplexml_load_file('data/sources/fichiers/module/crudGuriddo/main.php.xml');
		
		$uploadsave=null;
		$sMethodList=null;
		$sMethodNew=null;
		$sMethodEdit=null;
		$sMethodShow=null;
		$sMethodDelete=null;
		$sMethodProcessDelete=null;
		
		$sInputSelect=(string)$oVar->select;
		
		if($bWithPagination==1){
			$sMethodList=(string)$oVar->methodPaginationList;
		}else{
			$sMethodList=(string)$oVar->methodList;
		}		
		$sMethodListJson=(string)$oVar->methodListJson;
		
		$sMethodListColumn=(string)$oVar->methodListColumn;
		$sMethodJsonListColumn=(string)$oVar->methodListJsonColumn;
		
		$sCrudEnable=null;
		if(in_array('crudNew',$tCrud)){
			$sMethodNew=(string)$oVar->methodNew;
			
			$sCrudEnable.='$oTable->enableAdd(_root::getLink(\''.$sModule.'::postJson\'));'."\n\t\t";
		}
		
		if(in_array('crudEdit',$tCrud)){
			$sMethodEdit=(string)$oVar->methodEdit;
			
			$sCrudEnable.='$oTable->enableEdit(_root::getLink(\''.$sModule.'::postJson\'));'."\n\t\t";
		}
		
		if(in_array('crudShow',$tCrud)){
			$sMethodShow=(string)$oVar->methodShow;
			
			$sCrudEnable.='$oTable->enableShow();'."\n\t\t";
		}
		
		if(in_array('crudDelete',$tCrud)){
			$sMethodDelete=(string)$oVar->methodDelete;
			$sMethodProcessDelete=(string)$oVar->methodProcessDelete;
			
			$sCrudEnable.='$oTable->enableDelete();'."\n\t\t";
		}
		
		
		$tab="\t\t\t";
		$ret="\n";
		
		$sPaginationList='';
		
		$sJointures='';
		$jsonjointures='';
		$jsonJointureTab='';
		
		$tListSortFieldAllowed=array("'$sDefaultSortField'");
		$sListSortFieldAllowed='';
		$sListColumns='';
		$sJsonColumns='';
		$sTable='';
		$tArrayColumn=array();
		$tArrayColumnUpload=array();
		foreach($tColumn as $i => $sColumn){
			$sType=$tType[$i];
			$sOption='null';
			if(substr($sType,0,7)=='select;'){
				$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInputSelect);
				$sTable.=$sInput;
				
				$tOption['edittype']="'select'";
				// editoptions: {
                //             value: "ALFKI:ALFKI;
				$sJointures.='$tJoin'.substr($sType,7).'='.substr($sType,7).'::getInstance()->getSelect();'."\n";
				$jsonJointureTab.='$tJoin'.substr($sType,7).'='.substr($sType,7).'::getInstance()->getSelect();'."\n";
				
				$jsonjointures.='if(isset($tJoin'.substr($sType,7).'[$val->'.$sColumn.'])){'."\n";
				$jsonjointures.='$val->'.$sColumn.'=$tJoin'.substr($sType,7).'[$val->'.$sColumn.'];'."\n";
				$jsonjointures.='}'."\n";
				
				$sJointures.="\t"."\t".'$tmp=array();'."\n";
				$sJointures.="\t"."\t".'foreach($tJoin'.substr($sType,7).' as $key => $val){'."\n";
					$sJointures.="\t"."\t"."\t".'$tmp[]=$key.\':\'.$val;'."\n";
				$sJointures.="\t"."\t".'}';
				$sJointures.="\t"."\t".'$sJoin'.substr($sType,7).'=implode(\';\',$tmp);'."\n";
				$sJointures.="\t"."\t".'$o'.substr($sType,7).'=new stdclass;';
				$sJointures.="\t"."\t".'$o'.substr($sType,7).'->value=$sJoin'.substr($sType,7).';';
				
				$tOption['editoptions']='$o'.substr($sType,7);

				if($tOption){
					$sOption='array(';
					foreach($tOption as $key => $val){
							$sOption.="'$key'=>$val,";
					}
					$sOption.=')';
				}
			}elseif($sType=='upload'){
				$tArrayColumnUpload[]="'$sColumn'";
				continue;
			}
			$tArrayColumn[]="'$sColumn'";
			
			$tmpLine=$sMethodListColumn;
			$tmpLine=preg_replace('/exampleOption/',$sOption,$tmpLine);
			
			$tmpLine=preg_replace('/examplecolumnLabel/',$tLabel[$i],$tmpLine);
			$tmpLine=preg_replace('/examplecolumn/',$sColumn,$tmpLine);
			$tmpLine.="\n\t\t";
			$sListColumns.=$tmpLine;
			
			$tmpLine=$sMethodJsonListColumn;
			$tmpLine=preg_replace('/examplecolumn/',$sColumn,$tmpLine);
			$tmpLine.="\n";
			
			
			$sJsonColumns.=$tmpLine;
			
			$tListSortFieldAllowed[]="'$sColumn'";
		}
		$sListSortFieldAllowed=implode(',',$tListSortFieldAllowed);
		
		if($jsonjointures){
			$jsonjointures=$jsonJointureTab.'foreach($tData as $key => $val){'.$jsonjointures.'}';
		}
		
		
		$stColumn='array('.implode(',',$tArrayColumn).');';
		$stColumnUpload='array('.implode(',',$tArrayColumnUpload).');';
		
		if($tArrayColumnUpload){
			$uploadsave=(string)$oVar->uploadsave;
			$uploadsave=str_replace('//tColumnUpload',$stColumnUpload,$uploadsave);
		}
		
		$tReplace=array(
				'\/\/iciMethodList' => $sMethodList,
				'\/\/iciMethodJsonList'=>$sMethodListJson,
			
				'\/\/iciMethodNew' => $sMethodNew,
				'\/\/iciMethodEdit' => $sMethodEdit,
				'\/\/iciMethodShow' => $sMethodShow,
				'\/\/iciMethodDelete' => $sMethodDelete,
				
				'\/\/iciMethodProcessDelete' => $sMethodProcessDelete,
				'\/\/iciUpload' => $uploadsave,
		
				'oExamplemodel' => 'o'.ucfirst($sTableName),
				'tExamplemodel' => 't'.ucfirst($sTableName),
				'examplemodule' => $sModule,
				'examplemodel' => $sTableName,
				
				'\/\/icishow' => $sTable,
				'\/\/icinew' => $sTable,
				'\/\/iciedit' => $sTable,
				'\/\/icilist' => $sTable,
			
				'\/\/icisortfieldallowed'=>$sListSortFieldAllowed,
				'\/\/icicolumns' => $sListColumns,
				'\/\/icijsoncolumns' => $sJsonColumns,
				'\/\/crudEnable'=>$sCrudEnable,
				
				'\/\/icijoins'=>$sJointures,
				'\/\/icijsonjointures'=>$jsonjointures,
			
				'\/\/iciDefaultSortField'=>$sDefaultSortField,
			
				'\/\/icitableWidth'=>$tTableOption['width'],
				'\/\/icitableHeight'=>$tTableOption['height'],
				'\/\/icitablelimit'=>$tTableOption['limit'],
			
				'\/\/icipaginationlist' => $sPaginationList,
				'\/\/icitColumn' => $stColumn,
				
			);
		
		
		$sContent=module_builder::getTools()->stringReplaceIn(
									$tReplace,
									'data/sources/fichiers/module/crudGuriddo/main.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}
	private function genModelTpl($sModule,$sClass,$tColumn,$sTableName,$tCrud,$tLabel){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$tCrud[]='list';
		
		$tTpl=array('list');
		
		$tTplCrud=array(
		
				'list' => 'list',
		);
		
		foreach($tTpl as $sTpl){
			//print $sTpl;
			if(!in_array( $tTplCrud[$sTpl],$tCrud)){
				//print "skip $sTpl ";
				continue;
			}
			
			$oFile=new _file('data/sources/fichiers/module/crudGuriddo/view/'.$sTpl.'.php');
			$sContent=$oFile->getContent();	
			
			$oVar=simplexml_load_file('data/sources/fichiers/module/crudGuriddo/view/'.$sTpl.'.php.xml');
			
				
				$sLigne=(string)$oVar->lignetd;
				
				$sInputText=(string)$oVar->input;
				
				$sInputTextarea=(string)$oVar->textarea;
				
				$sInputSelect=(string)$oVar->select;

				$sInputUpload=(string)$oVar->upload;


				$sLinks='';
				$sLinkNew='';

				if($sTpl=='list'){
					//TH
					$sLigneTH=(string)$oVar->ligneth;
					
					//liens
					$tLink['crudNew']=(string)$oVar->linkNew;
					$tLink['crudEdit']=(string)$oVar->linkEdit;
					$tLink['crudShow']=(string)$oVar->linkShow;
					$tLink['crudDelete']=(string)$oVar->linkDelete;
					
					$iMaxCrud=count($tCrud);
					$iMaxCrud-=2;
					foreach($tCrud as $i => $sAction){
						if(in_array($sAction,array('crudNew','list'))){ continue; }
						if(!isset($tLink[$sAction])){ continue; }
						$sLinks.=$tLink[$sAction];
						if($i < $iMaxCrud){
							$sLinks .= '| ';
						}
					}
					if(in_array('crudNew',$tCrud)){
						$sLinkNew=$tLink['crudNew'];
					}
					
				}

				$sTable='';
				$sTableTh='';
				$sEnctype='';
				foreach($tColumn as $i => $sColumn){
					$sLabel=$tLabel[$i];
					
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
					$sTable.=preg_replace('/examplecolumn/',$sLabel,preg_replace('/exampletd/',$sInput,$sLigne));

					$sTableTh.=preg_replace('/exampleth/',$sLabel,$sLigneTH);
				}
				
				$tReplace=array(
					'<\?php \/\/linknew\?>' => $sLinkNew,
					'<\?php \/\/links\?>' => $sLinks,
				
					'oExamplemodel' => 'o'.ucfirst($sTableName),
					'tExamplemodel' => 't'.ucfirst($sTableName),
					'examplemodule' => $sModule,
					
					
					'<\?php \/\/enctype\?>' => $sEnctype,
					'<\?php \/\/ici\?>' => $sTable,
					'<\?php \/\/icith\?>' => $sTableTh,
					
					'<\?php \/\/colspan\?>' => (count($tColumn)+1)
				);
				
				
				$sContent=module_builder::getTools()->stringReplaceIn(
															$tReplace,
															'data/sources/fichiers/module/crudGuriddo/view/'.$sTpl.'.php'
				);
			
			
			
			
			$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sTpl.'.php' );
			$oFile->setContent($sContent);
			$oFile->save();
			$oFile->chmod(0666);
		}
		
		
		
	}
	
	
}
