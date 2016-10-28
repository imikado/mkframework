<?php
class module_mods_bootstrap_crudGuriddo extends abstract_moduleBuilder{

	protected $sModule='mods_bootstrap_crudGuriddo';
	protected $sModuleView='mods/bootstrap/crudGuriddo';

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

		$oTpl= $this->getView('index');
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

		if($this->isPost()){
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

			$tCrud= _root::getParam('crud',null);

			$bWithPagination=_root::getParam('withPagination');

			$this->genModelMain($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn,$tLabel,$sDefaultField,$tTableOption,$tCrud,$bWithPagination);
			$this->genModelTpl($sModuleToCreate,$sClass,$tColumn,$oModel->getTable(),$tCrud,$tLabel);

			$msg=trR('moduleGenereAvecSucces',array('#MODULE#'=>$sModule));
			$detail=trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModule));
			$detail.='<br />'.trR('creationRepertoire',array('#REPERTOIRE#'=>'module/'.$sModule.'/view'));
			$detail.='<br />'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModule.'/main.php'));
			$detail.='<br />'.trR('CreationDuFichierVAR',array('#FICHIER#'=>'module/'.$sModule.'/view/list.php'));

			$detail.='<br/><br/>'.tr('accessibleVia').'<a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModule.'::index">index.php?:nav='.$sModule.'::index</a>';

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

		/*SOURCE*/$oSourceMain=$this->getObjectSource('example/main.php');
		/*SOURCE*/$oSourceMain->setPattern('#MODULE#',$sModule);

		$tReplace=array(
			'#oExamplemodel#' => 'o'.ucfirst($sTableName),
			'#tExamplemodel#' => 't'.ucfirst($sTableName),
			'#examplemodule#' => $sModule,
			'#examplemodel#' => $sTableName,
			'#icitablelimit#'=>$tTableOption['limit'],
			'#icitableWidth#'=>$tTableOption['width'],
			'#icitableHeight#'=>$tTableOption['height'],
			'#CODE#'=>null,
			'#icijoins#'=>null,
		);



		foreach($tReplace as $sKey=>$sValue){
			/*SOURCE*/$oSourceMain->setPattern($sKey,$sValue);
		}

		$uploadsave=null;
		$sMethodList=null;
		$sMethodListJson=null;
		$sMethodNew=null;
		$sMethodEdit=null;
		$sMethodShow=null;
		$sMethodDelete=null;
		$sMethodProcessDelete=null;


		$tab="\t\t\t";
		$ret="\n";

		$sPaginationList='';

		$sJointures='';
		$jsonjointures='';
		$jsonJointureTab='';

		$sJsonGetSelects='';

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
				$sInput=$oSourceMain->getSnippet('codetJoin',array('#examplemodel#'=>substr($sType,7)));
				$sTable.=$sInput;

				$tOption['edittype']="'select'";
				$jsonJointureTab.=$oSourceMain->getSnippet('codetJoin',array('#examplemodel#'=>substr($sType,7)));

				$jsonjointures.=$oSourceMain->getSnippet('jsonjointures',array('#examplemodel#'=>substr($sType,7),'#column#'=>$sColumn));

				$sJointures.=$oSourceMain->getSnippet('sJointures',array('#examplemodel#'=>substr($sType,7),'#column#'=>$sColumn));

				$sJsonGetSelects.= $oSourceMain->getSnippet('jsonGetSelect',array('#examplemodel#'=>substr($sType,7)));

				$tOption['editoptions']='array(\'value\'=>'.substr($sType,7).'::getInstance()->getSelect())';

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

			$tmpLine=$oSourceMain->getSnippet('methodListColumn',array(
																'#exampleOption#'=>$sOption,
																'#examplecolumnLabel#'=>$tLabel[$i],
																'#examplecolumn#'=>$sColumn,
																)
			);

			$tmpLine.="\n\t\t";
			$sListColumns.=$tmpLine;

			$tmpLine=$oSourceMain->getSnippet('methodJsonListColumn',array(
															'#examplecolumn#'=>$sColumn,
															)
			);

			$tmpLine.="\n";

			$sJsonColumns.=$tmpLine;

			$tListSortFieldAllowed[]="'$sColumn'";
		}
		$sListSortFieldAllowed=implode(',',$tListSortFieldAllowed);

		$stColumn='array('.implode(',',$tArrayColumn).');';
		$stColumnUpload='array('.implode(',',$tArrayColumnUpload).');';

		if($tArrayColumnUpload){
			$uploadsave=$oSourceMain->getSnippet('uploadsave',array(
											'#tColumnUpload#'=>$stColumnUpload,
											'#oExamplemodel#'=>'o'.ucfirst($sTableName),
											));
		}




		$sCrudEnable=null;
		if(in_array('crudNew',$tCrud)){
			$sMethodNew=$oSourceMain->getSnippet('methodNew',$tReplace);

			$sCrudEnable.='$oTable->enableAdd(_root::getLink(\''.$sModule.'::postJson\'));'."\n\t\t";
		}

		if(in_array('crudEdit',$tCrud)){
			$sMethodEdit=$oSourceMain->getSnippet('methodEdit',$tReplace);

			$sCrudEnable.='$oTable->enableEdit(_root::getLink(\''.$sModule.'::postJson\'));'."\n\t\t";
		}

		if(in_array('crudShow',$tCrud)){
			$sMethodShow=$oSourceMain->getSnippet('methodShow',$tReplace);

			$sCrudEnable.='$oTable->enableShow();'."\n\t\t";
		}

		if(in_array('crudDelete',$tCrud)){
			$sMethodDelete=$oSourceMain->getSnippet('methodDelete',$tReplace);
			$sMethodProcessDelete=$oSourceMain->getSnippet('methodProcessDelete',$tReplace);

			$sCrudEnable.='$oTable->enableDelete();'."\n\t\t";
		}

		if($jsonjointures!=''){

			$sJsonJointure= $oSourceMain->getSnippet('foreachJsonData',array(
				'#icijsonline#'=>$jsonjointures,
				'#iciJsonGetSelect#'=>$sJsonGetSelects,
			));
		}else{
			$sJsonJointure=$jsonjointures;

		}

		$tReplaceJson=$tReplace;
		$tReplaceJson['#icisortfieldallowed#']=$sListSortFieldAllowed;
		$tReplaceJson['#icicolumns#']=$sListColumns;
		$tReplaceJson['#icijsoncolumns#']=$sJsonColumns;
		$tReplaceJson['#crudEnable#']=$sCrudEnable;
		$tReplaceJson['#iciDefaultSortField#']=$sDefaultSortField;
		$tReplaceJson['#icijsonjointures#']=$sJsonJointure;

		$tReplace['#CODE#']=$sTable;
		if($bWithPagination==1){
			$sMethodList=$oSourceMain->getSnippet('methodPaginationList',$tReplaceJson);
		}else{
			$sMethodList=$oSourceMain->getSnippet('methodList',$tReplaceJson);
		}


		$sMethodListJson=$oSourceMain->getSnippet('methodListJson',$tReplaceJson);


		/*SOURCE*/$oSourceMain->setPattern('#icisortfieldallowed#',$sListSortFieldAllowed);

		/*SOURCE*/$oSourceMain->setPattern('#iciMethodList#',$sMethodList);
		/*SOURCE*/$oSourceMain->setPattern('#iciMethodNew#',$sMethodNew);
		/*SOURCE*/$oSourceMain->setPattern('#iciMethodEdit#',$sMethodEdit);
		/*SOURCE*/$oSourceMain->setPattern('#iciMethodShow#',$sMethodShow);
		/*SOURCE*/$oSourceMain->setPattern('#iciMethodDelete#',$sMethodDelete);

		/*SOURCE*/$oSourceMain->setPattern('#iciMethodJsonList#',$sMethodListJson);



		/*SOURCE*/$oSourceMain->setPattern('#iciMethodProcessDelete#',$sMethodProcessDelete);
		/*SOURCE*/$oSourceMain->setPattern('#iciUpload#',$uploadsave);

		/*SOURCE*/$oSourceMain->setPattern('#icitColumn#',$stColumn);

		/*SOURCE*/$oSourceMain->save();


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

			/*SOURCE*/$oSourceView=$this->getObjectSource('example/view/'.$sTpl.'.php');
			/*SOURCE*/$oSourceView->setPattern('#examplemodule#',$sModule);


				$sLinks='';
				$sLinkNew='';

				if($sTpl=='list'){

					$tReplace=array(
						'#oExamplemodel#' => 'o'.ucfirst($sTableName),
						'#examplemodule#' => $sModule
					);

					//liens
					$tLink['crudNew']=$oSourceView->getSnippet('linkNew',$tReplace);
					$tLink['crudEdit']=$oSourceView->getSnippet('linkEdit',$tReplace);
					$tLink['crudShow']=$oSourceView->getSnippet('linkShow',$tReplace);
					$tLink['crudDelete']=$oSourceView->getSnippet('linkDelete',$tReplace);

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
						$sInput=$oSourceView->getSnippet('input',array(
													'#examplecolumn#'=>$sColumn,
													'#oExamplemodel#'=>'o'.ucfirst($sTableName),
													));

					}elseif($sType=='textarea'){
						$sInput=$oSourceView->getSnippet('textarea',array(
													'#examplecolumn#'=>$sColumn,
													'#oExamplemodel#'=>'o'.ucfirst($sTableName),
													));

					}elseif(substr($sType,0,7)=='select;'){
						$sInput=$oSourceView->getSnippet('select',array(
													'#examplecolumn#'=>$sColumn,
													'#oExamplemodel#'=>'o'.ucfirst($sTableName),
													'#examplemodel#'=>substr($sType,7),
													));

					}elseif($sType=='upload'){
						$sInput=$oSourceView->getSnippet('upload',array(
													'#examplecolumn#'=>$sColumn,
													'#oExamplemodel#'=>'o'.ucfirst($sTableName),
													'#examplemodel#'=>substr($sType,7),
													));

						$sEnctype=' enctype="multipart/form-data"';//changement du enctype du formulaire
					}
					$sTable.=$oSourceView->getSnippet('lignetd',array(
								'#examplecolumn#'=>$sLabel,
								'#exampletd#'=>$sInput,
					));

					$sTableTh.=$oSourceView->getSnippet('ligneth',array(
								'#exampleth#'=>$sLabel,
								'#examplecolumn#'=>$sLabel,
					));

				}

				$tReplace=array(
					'#MODULE#'=>$sModule,

					'#linknew#' => $sLinkNew,
					'#links#' => $sLinks,

					'#oExamplemodel#' => 'o'.ucfirst($sTableName),
					'#tExamplemodel#' => 't'.ucfirst($sTableName),
					'#examplemodule#' => $sModule,


					'#enctype#' => $sEnctype,
					'#ici#' => $sTable,
					'#icith#' => $sTableTh,

					'#colspan#' => (count($tColumn)+1)
				);

				foreach($tReplace as $key => $val){
					$oSourceView->setPattern($key,$val);
				}
				$oSourceView->save();

		}



	}


}
