<?php

class module_mods_bootstrap_crudMulti extends abstract_moduleBuilder {

	protected $sModule = 'mods_bootstrap_crudMulti';
	protected $sModuleView = 'mods/bootstrap/crudMulti';

	public function _index() {
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		$msg = '';
		$detail = '';
		$oDir = new _dir(_root::getConfigVar('path.generation') . _root::getParam('id') . '/model/');
		$tFile = array();
		$tRowMethodes = array();
		foreach ($oDir->getListFile() as $oFile) {
			if (preg_match('/.sample.php/', $oFile->getName()) or ! preg_match('/.php$/', $oFile->getName()))
				continue;
			$tFile[] = $oFile->getName();
			require_once( $oFile->getAdresse() );
			$sClassFoo = substr($oFile->getName(), 0, -4);
			$oModelFoo = new $sClassFoo;

			if (method_exists($oModelFoo, 'getSelect')) {
				$tRowMethodes[substr($oFile->getName(), 0, -4)] = substr($oFile->getName(), 0, -4) . '::getSelect()';
			}
		}

		$oTpl = $this->getView('index');

		if (_root::getParam('class') != '') {

			$sClass = substr(_root::getParam('class'), 0, -4);
			require_once(_root::getConfigVar('path.generation') . _root::getParam('id') . '/model/' . $sClass . '.php');


			$tColumn = module_builder::getTools()->getListColumnFromClass($sClass);
			$oTpl->sClass = $sClass;

			$tId = module_builder::getTools()->getIdTabFromClass($sClass);
			foreach ($tColumn as $i => $sColumn) {
				if (in_array($sColumn, $tId)) {
					unset($tColumn[$i]);
				}
			}

			$oTpl->tColumn = $tColumn;

			$oTpl->tRowMethodes = $tRowMethodes;

			$oModel = new $sClass;
			$oTpl->sModuleToCreate = $oModel->getTable();
			$oTpl->sgbd = _root::getConfigVar('db.' . $oModel->getConfig() . '.sgbd');
		}

		if ($this->isPost()) {
			$sModuleToCreate = _root::getParam('moduleToCreate');
			$sClass = _root::getParam('sClass');
			$tColumn = _root::getParam('tColumn');
			$tLabel = _root::getParam('tLabel');
			$tType = _root::getParam('tType');
			$tEnable = _root::getParam('tEnable');

			foreach ($tColumn as $i => $sColumn) {
				if (!in_array($sColumn, $tEnable)) {
					unset($tColumn[$i]);
				}
			}

			require_once(_root::getConfigVar('path.generation') . _root::getParam('id') . '/model/' . $sClass . '.php');
			$oModel = new $sClass;
			$sModule = $sModuleToCreate;
			$this->projectMkdir('module/' . $sModule);
			$this->projectMkdir('module/' . $sModule . '/view');

			$tCrud = array('crudNew', 'crudEdit', 'crudDelete');

			$bWithPagination = _root::getParam('withPagination');

			$this->genModelMain($sModuleToCreate, $oModel->getTable(), $sClass, $tColumn, $tCrud, $bWithPagination, $tId);
			$this->genModelTpl($sModuleToCreate, $sClass, $tColumn, $oModel->getTable(), $tCrud, $tLabel, $tId);

			$msg = trR('moduleGenereAvecSucces', array('#MODULE#' => $sModule));
			$detail = trR('creationRepertoire', array('#REPERTOIRE#' => 'module/' . $sModule));
			$detail.='<br />' . trR('creationRepertoire', array('#REPERTOIRE#' => 'module/' . $sModule . '/view'));
			$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/main.php'));
			$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/view/list.php'));

			if (in_array('crudEdit', $tCrud)) {
				$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/view/edit.php'));
			}
			if (in_array('crudNew', $tCrud)) {
				$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/view/new.php'));
			}
			if (in_array('crudShow', $tCrud)) {
				$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/view/show.php'));
			}
			if (in_array('crudDelete', $tCrud)) {
				$detail.='<br />' . trR('CreationDuFichierVAR', array('#FICHIER#' => 'module/' . $sModule . '/view/delete.php'));
			}

			$detail.='<br/><br/>' . tr('accessibleVia') . ' <a href="' . _root::getConfigVar('path.generation') . _root::getParam('id') . '/public/index.php?:nav=' . $sModule . '::index">index.php?:nav=' . $sModule . '::index</a>';
		}

		$oTpl->msg = $msg;
		$oTpl->detail = $detail;
		$oTpl->tFile = $tFile;
		return $oTpl;
	}

	private function genModelMain($sModule, $sTableName, $sClass, $tColumn, $tCrud, $bWithPagination, $tId) {

		$sIdTable = implode('::', $tId);
		//$tColumn=_root::getParam('tColumn');
		$tType = _root::getParam('tType');

		/* SOURCE */$oSourceMain = $this->getObjectSource('example/main.php');
		/* SOURCE */$oSourceMain->setPattern('#MODULE#', $sModule);

		$tReplace = array(
			'#oExamplemodel#' => 'o' . ucfirst($sTableName),
			'#tExamplemodel#' => 't' . ucfirst($sTableName),
			'#examplemodule#' => $sModule,
			'#examplemodel#' => $sTableName,
		);

		foreach ($tReplace as $sKey => $sValue) {
			/* SOURCE */$oSourceMain->setPattern($sKey, $sValue);
		}

		$uploadsave = null;
		$sMethodList = null;
		$sMethodNew = null;
		$sMethodEdit = null;
		$sMethodShow = null;
		$sMethodDelete = null;
		$sMethodProcessDelete = null;

		$tab = "\t\t\t";
		$ret = "\n";

		$sPaginationList = '';
		if ($bWithPagination == 1) {
			$sPaginationList = $oSourceMain->getSnippet('oModulePagination', array(
				'#sModule#' => $sModule,
				'#tTablename#' => 't' . ucfirst($sTableName)
			));
		}


		$sTable = '';
		$tArrayColumn = array();
		$tArrayColumnUpload = array();
		foreach ($tColumn as $i => $sColumn) {
			$sType = $tType[$i];
			if (substr($sType, 0, 7) == 'select;') {
				$sInput = $oSourceMain->getSnippet('codetJoin', array('#examplemodel#' => substr($sType, 7)));
				$sTable.=$sInput;
			} elseif ($sType == 'upload') {
				$tArrayColumnUpload[] = "'$sColumn'";
				continue;
			}
			$tArrayColumn[] = "'$sColumn'";
		}

		$stColumn = 'array(' . implode(',', $tArrayColumn) . ');';
		$stColumnUpload = 'array(' . implode(',', $tArrayColumnUpload) . ');';

		if ($tArrayColumnUpload) {
			$uploadsave = $oSourceMain->getSnippet('uploadsave', array(
				'#tColumnUpload#' => $stColumnUpload,
				'#oExamplemodel#' => 'o' . ucfirst($sTableName),
			));
		}

		$tReplace['#CODE#'] = $sTable;

		$sMethodEditList = $oSourceMain->getSnippet('methodEditList', $tReplace);

		if ($bWithPagination == 1) {
			$sMethodList = $oSourceMain->getSnippet('methodPaginationList', $tReplace);
		} else {
			$sMethodList = $oSourceMain->getSnippet('methodList', $tReplace);
		}

		if (in_array('crudNew', $tCrud)) {
			$sMethodNew = $oSourceMain->getSnippet('methodNew', $tReplace);
		}

		if (in_array('crudEdit', $tCrud)) {
			$sMethodEdit = $oSourceMain->getSnippet('methodEdit', $tReplace);
		}

		if (in_array('crudShow', $tCrud)) {
			$sMethodShow = $oSourceMain->getSnippet('methodShow', $tReplace);
		}

		if (in_array('crudDelete', $tCrud)) {
			$sMethodDelete = $oSourceMain->getSnippet('methodDelete', $tReplace);
			$sMethodProcessDelete = $oSourceMain->getSnippet('methodProcessDelete', $tReplace);
		}


		/* SOURCE */$oSourceMain->setPattern('#iciMethodList#', $sMethodList);
		/* SOURCE */$oSourceMain->setPattern('#iciMethodEditList#', $sMethodEditList);

		/* SOURCE */$oSourceMain->setPattern('#iciMethodNew#', $sMethodNew);
		/* SOURCE */$oSourceMain->setPattern('#iciMethodEdit#', $sMethodEdit);
		/* SOURCE */$oSourceMain->setPattern('#iciMethodShow#', $sMethodShow);
		/* SOURCE */$oSourceMain->setPattern('#iciMethodDelete#', $sMethodDelete);

		/* SOURCE */$oSourceMain->setPattern('#iciMethodProcessDelete#', $sMethodProcessDelete);
		/* SOURCE */$oSourceMain->setPattern('#iciUpload#', $uploadsave);

		/* SOURCE */$oSourceMain->setPattern('#icitColumn#', $stColumn);

		/* SOURCE */$oSourceMain->setPattern('#examplerow_id#', $sIdTable);

		/* SOURCE */$oSourceMain->save();
	}

	private function genModelTpl($sModule, $sClass, $tColumn, $sTableName, $tCrud, $tLabel, $tId) {

		$sIdTable = implode('::', $tId);
		//$tColumn=_root::getParam('tColumn');
		$tType = _root::getParam('tType');

		$tCrud[] = 'list';
		$tCrud[] = 'editList';

		$tTpl = array('list', 'new', 'delete', 'editList');

		$tTplCrud = array(
			'new' => 'crudNew',
			'delete' => 'crudDelete',
			'list' => 'list',
			'editList' => 'editList',
		);

		foreach ($tTpl as $sTpl) {
			//print $sTpl;
			if (!in_array($tTplCrud[$sTpl], $tCrud)) {
				//print "skip $sTpl ";
				continue;
			}

			/* SOURCE */$oSourceView = $this->getObjectSource('example/view/' . $sTpl . '.php');
			/* SOURCE */$oSourceView->setPattern('#examplemodule#', $sModule);


			$tReplace = array(
				'#oExamplemodel#' => 'o' . ucfirst($sTableName),
				'#examplemodule#' => $sModule
			);

			$sLinks = $oSourceView->getSnippet('linkDelete', $tReplace);
			$sLinkNew = $oSourceView->getSnippet('linkNew', $tReplace);
			$sLinkEditList = $oSourceView->getSnippet('linkEditList', $tReplace);
			$sLinkList = $oSourceView->getSnippet('linkList', $tReplace);


			$sTable = '';
			$sTableTh = '';
			$sEnctype = '';
			foreach ($tColumn as $i => $sColumn) {
				$sLabel = $tLabel[$i];

				$sType = $tType[$i];
				if ($sType == 'text' or $sType == 'date') {
					$sInput = $oSourceView->getSnippet('input', array(
						'#examplecolumn#' => $sColumn,
						'#oExamplemodel#' => 'o' . ucfirst($sTableName),
					));
				} elseif ($sType == 'textarea') {
					$sInput = $oSourceView->getSnippet('textarea', array(
						'#examplecolumn#' => $sColumn,
						'#oExamplemodel#' => 'o' . ucfirst($sTableName),
					));
				} elseif (substr($sType, 0, 7) == 'select;') {
					$sInput = $oSourceView->getSnippet('select', array(
						'#examplecolumn#' => $sColumn,
						'#oExamplemodel#' => 'o' . ucfirst($sTableName),
						'#examplemodel#' => substr($sType, 7),
					));
				} elseif ($sType == 'upload') {
					$sInput = $oSourceView->getSnippet('upload', array(
						'#examplecolumn#' => $sColumn,
						'#oExamplemodel#' => 'o' . ucfirst($sTableName),
						'#examplemodel#' => substr($sType, 7),
					));

					$sEnctype = ' enctype="multipart/form-data"'; //changement du enctype du formulaire
				}
				$sTable.=$oSourceView->getSnippet('lignetd', array(
					'#examplecolumn#' => $sLabel,
					'#exampletd#' => $sInput,
				));

				$sTableTh.=$oSourceView->getSnippet('ligneth', array(
					'#exampleth#' => $sLabel,
					'#examplecolumn#' => $sLabel,
				));
			}

			$tReplace = array(
				'#MODULE#' => $sModule,
				'#linknew#' => $sLinkNew,
				'#links#' => $sLinks,
				'#linkEditList#' => $sLinkEditList,
				'#linkList#' => $sLinkList,
				'#links#' => $sLinks,
				'#oExamplemodel#' => 'o' . ucfirst($sTableName),
				'#tExamplemodel#' => 't' . ucfirst($sTableName),
				'#examplemodule#' => $sModule,
				'#enctype#' => $sEnctype,
				'#ici#' => $sTable,
				'#icith#' => $sTableTh,
				'#colspan#' => (count($tColumn) + 1),
				'#pkey#' => $sIdTable,
			);

			foreach ($tReplace as $key => $val) {
				$oSourceView->setPattern($key, $val);
			}
			$oSourceView->save();
		}
	}

}
