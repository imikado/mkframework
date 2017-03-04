<?php

function module_builderFormAutoload($sClass_) {
	if (false === preg_match('/_/', $sClass_)) {
		return false;
	}
	$oTools = new module_builderTools();

	$tIni = parse_ini_file($oTools->getRootWebsite() . 'conf/path.ini.php', true);

	list($sPrefix, $foo) = explode('_', $sClass_);
	if (in_array($sPrefix, array('model', 'business', 'interface'))) {
		$sFilename = $oTools->getRootWebsite() . 'public/' . $tIni['path'][$sPrefix] . '/' . $sClass_ . '.php';
		if (file_exists($sFilename)) {
			require_once $sFilename;

			return true;
		} elseif ($sPrefix == 'interface') {
			eval('interface ' . $sClass_ . ' {}');
		}
	}

	return false;
}

spl_autoload_register('module_builderFormAutoload');

class module_builderForm {

	private $_sPath = null;
	private $_tParam = null;
	private $_oXml = null;
	private $_iMaxStep = 0;
	private $_tNavPath = null;
	private $_iStep = 0;
	private $_iNextStep = 0;
	private $_oEngine = null;
	private $_getSelectModelGetSelect = null;

	public function run() {

		//pre check
		if ($this->check() === false) {
			return $this->build();
		}

		//pre process
		$tReturn = $this->_oEngine->preProcess($this->getParam('step'), $this->getListParams());
		if (isset($tReturn['tParam'])) {
			$this->loadParams($tReturn['tParam']);
		}

		if ($tReturn['status'] === false) {
			return $this->show($this->getPage());
		}

		if ($this->getParam('finish')) {
			$tReturn = $this->getFinish($this->_tSource);
		}

		if (isset($tReturn['getPage'])) {
			$tReturn['data'] = $this->getPage();
		}

		if ($tReturn['status'] === false or isset($tReturn['data'])) {
			return $this->show($tReturn['data']);
		} elseif ($tReturn['status'] === true) {
			$this->nextStep();

			return $this->build();
		}
	}

	public function loadSource($tSource_) {
		$this->_tSource = $tSource_;
	}

	public function loadEngine($oEngine_) {
		$this->_oEngine = $oEngine_;
	}

	public function load($sPath_) {
		$this->_sPath = $sPath_;

		$this->_oXml = simplexml_load_file('module/' . $this->_sPath . '/view/form.xml');
	}

	public function getObjectSource($sSource_) {
		return module_builder::getTools()->getSource('module/' . $this->_sPath, _root::getConfigVar('path.generation') . _root::getParam('id'), $sSource_);
	}

	public function getParam($sName_, $default_ = null) {
		$sName_ = (string) $sName_;
		if (isset($this->_tParam[$sName_])) {
			return $this->_tParam[$sName_];
		} else {
			return $default_;
		}
	}

	public function paramExist($sName_) {
		return array_key_exists($sName_, $this->_tParam);
	}

	public function getListParams() {
		return $this->_tParam;
	}

	public function loadParams($tParam_) {
		unset($tParam_['id']);
		unset($tParam_['action']);
		unset($tParam_[':nav']);

		$this->_tParam = $tParam_;

		$this->_iStep = $this->getParam('step', 1);
		$this->_iNextStep = $this->getParam('nextStep', 1);
	}

	public function nextStep() {
		$this->_iStep = $this->getParam('nextStep', 1);
	}

	public function getApplicationPath() {
		return _root::getConfigVar('path.generation') . _root::getParam('id');
	}

	public function getSelectPlugin() {
		$sPath = $this->getApplicationPath() . '/plugin/';

		$tFile = scandir($sPath);
		$tIndexFile = array();
		if ($tFile) {
			foreach ($tFile as $sFile) {

				if(is_dir($sPath.$sFile)){
					$tFile2 = scandir($sPath.'/'.$sFile);
					if ($tFile2) {
						foreach ($tFile2 as $sFile2) {
							if (substr($sFile2, -4) != '.php' or substr($sFile2,0,7)!='plugin_') {
								continue;
							}
							$tIndexFile[$sFile2] = $sFile2;
						}
					}

				}

				if (substr($sFile, -4) != '.php' or substr($sFile,0,7)!='plugin_') {
					continue;
				}
				$tIndexFile[$sFile] = $sFile;
			}
		}

		return $tIndexFile;
	}

	public function getSelectModuleParent() {
		$sPath = $this->getApplicationPath() . '/module/';

		$tFile = scandir($sPath);
		$tIndexFile = array();
		if ($tFile) {
			foreach ($tFile as $sFile) {
				if (substr($sFile, 0, 1) == '.') {
					continue;
				}
				$tIndexFile[$sFile] = $sFile;
			}
		}

		return $tIndexFile;
	}

	public function getSelectModuleChild($sParentModule) {
		$sPath = $this->getApplicationPath() . '/module/' . $sParentModule;

		$tFile = scandir($sPath);
		$tIndexFile = array();
		if ($tFile) {
			foreach ($tFile as $sFile) {
				if (substr($sFile, 0, 1) == '.') {
					continue;
				}
				if (false === is_dir($sPath . '/' . $sFile)) {
					continue;
				}
				$tIndexFile[$sFile] = $sFile;
			}
		}

		return $tIndexFile;
	}

	public function getSelectModelGetSelect() {

		if ($this->_getSelectModelGetSelect) {
			return $this->_getSelectModelGetSelect;
		}

		$tIndex = array();

		$tModelFilename = scandir($this->getApplicationPath() . '/model/');
		foreach ($tModelFilename as $sFilename) {

			if (substr($sFilename, -4) != '.php')
				continue;

			include_once($this->getApplicationPath() . '/model/' . $sFilename);

			$sClass=substr($sFilename,0, strpos($sFilename,'.'));
			$oClass = new $sClass;
			$tMethodList = get_class_methods($oClass);
			foreach ($tMethodList as $sMethod) {
				if ($sMethod != 'getSelect')
					continue;
				$tIndex['select.' . $sClass] = ' ' . $sClass . '::getSelect()';
			}
		}

		$this->_getSelectModelGetSelect = $tIndex;

		return $tIndex;
	}

	public function getSelectActionList($sModule_) {
		$tModule = explode('_', $sModule_);
		$sParentModulePath = $this->getApplicationPath() . '/' . $tModule[0] . '/' . $tModule[1] . '/main.php';

		require_once $sParentModulePath;

		$sModulePath = $this->getApplicationPath() . '/' . implode('/', $tModule) . '/main.php';

		require_once $sModulePath;

		$oClass = new $sModule_();

		$tMethod = get_class_methods($oClass);

		$tIndex = array();
		foreach ($tMethod as $sMethod) {
			if (substr($sMethod, 0, 1) != '_' or substr($sMethod, 0, 2) == '__') {
				continue;
			}
			$tIndex[$sMethod] = $sMethod;
		}

		return $tIndex;
	}

	public function getSelectModel() {
		$sPath = $this->getApplicationPath() . '/model/';

		$tFile = scandir($sPath);
		$tIndexFile = array();
		if ($tFile) {
			foreach ($tFile as $sFile) {
				if (substr($sFile, 0, 1) == '.') {
					continue;
				}

				$tIndexFile[$sFile] = $sFile;
			}
		}

		return $tIndexFile;
	}

	public function getSelectProfil(){
		$tIni=parse_ini_file($this->getApplicationPath() . '/conf/connexion.ini.php',true);

		$tIniVal=array_values($tIni);

		$tProfil=array();
		foreach($tIniVal[0] as $sKey => $sValue){

			list($sProfil,$foo)=explode('.',$sKey);

			$tProfil[ $sProfil ] =$sProfil;
		}

		return $tProfil;

	}

	public function getSelectTable($sProfil){
		$oTools = new module_builderTools;
		$oTools->rootAddConf('conf/connexion.ini.php');

		$tTables=$oTools->getListTablesFromConfig( $sProfil );
		$tIndex=array();
		foreach($tTables as $sTable){
			$tIndex[$sTable]=$sTable;
		}


		return $tIndex;

	}

	public function getSelectFieldModel($sModel) {
		$oTools = new module_builderTools();
		$oTools->rootAddConf('conf/connexion.ini.php');

		$tColumn = $oTools->getListColumnFromClass( substr($sModel,0,-4));

		$tIndexFile = array();
		if ($tColumn) {
			foreach ($tColumn as $sColumn) {
				$tIndexFile[$sColumn] = $sColumn;
			}
		}

		return $tIndexFile;
	}

	public function getSelectFieldByProfilAndTable($sProfil,$sTable){
		$oTools = new module_builderTools;
		$oTools->rootAddConf('conf/connexion.ini.php');

		$tColumn=$oTools->getListColumnFromConfigAndTable( $sProfil,$sTable );
		$tIndex=array();
		foreach($tColumn as $sColumn){
			$tIndex[$sColumn]=$sColumn;
		}


		return $tIndex;
	}

	public function getFormSelect($sName_, $tOption_) {
		$sHtml = null;
		$sHtml .= '<select name="' . $sName_ . '">';
		$sHtml .= '<option></option>';
		foreach ($tOption_ as $sKey => $sVal) {
			$sHtml .= '<option ';
			if ($this->getParam($sName_) == $sKey) {
				$sHtml .= ' selected="selected" ';
			}
			$sHtml .= 'value="' . $sKey . '">' . $sVal . '</option>';
		}
		$sHtml .= '</select>';

		return $sHtml;
	}

	public function getFinish($tSource) {
		$tParam = $this->getListParams();

		$tSourceCreated = array();

		$tConcatVar = array();

		foreach ($tSource as $uSource) {


			if (is_array($uSource)) {
				$tDetailSource = $uSource;

				$sField = $tDetailSource['field'];
				$sTag = $tDetailSource['tag'];

				foreach ($tParam[$sField] as $sValue) {
					$tParam2 = $tParam;

					$tParam2[$sTag] = $sValue;

					$oSourceMain = $this->processSourceFile($tDetailSource['file'], $tParam2);

					$bStatus = $oSourceMain->save();
					$tSourceCreated[] = array($oSourceMain->getFilenameCreated(), $bStatus);
				}
			} else {

				$tParam2 = $tParam;

				$oSourceMain = $this->processSourceFile($uSource, $tParam2);

				$bStatus = $oSourceMain->save();

				$tSourceCreated[] = array($oSourceMain->getFilenameCreated(), $bStatus);
			}
		}

		$sText = $this->getPage();

		$sData = $this->getFinishData($tSourceCreated, $sText);

		return array('status' => true, 'data' => $sData);
	}

	private function processSourceFile($sSource_, $tParam_) {
		$tParam = $tParam_;
		$sSource = $sSource_;

		$sXmlFile = 'module/' . $this->_sPath . '/src/' . $sSource . '.xml';

		$tSourceCreated = array();

		$tConcatVar = array();

		$tVar = array();
		if ($tConcatVar) {
			foreach ($tConcatVar as $sConcatVar) {
				unset($tParam[$sConcatVar]);
			}
		}
		$tConcatVar = array();

		/* SOURCE */$oSourceMain = $this->getObjectSource($sSource);

		if (file_exists($sXmlFile)) {
			$oXmlFile = simplexml_load_file($sXmlFile);
			if (isset($oXmlFile->formules)) {
				foreach ($oXmlFile->formules->formu as $oFormu) {




					if ((string) $oFormu['type'] == 'loopWithKey') {
						if ((string) $oFormu['source'] == 'params') {
							foreach ($tParam[(string) $oFormu['param']] as $keyParam => $sParam) {

								if (isset($oFormu['keyField'])) {
									$tVar[(string) $oFormu['keyField']] = $keyParam;
								}

								if (isset($oFormu->action)) {
									foreach ($oFormu->action as $oAction) {
										if ((string) $oAction['type'] == 'setVariable') {
											if (isset($oAction['source']) and (string) $oAction['source'] == 'params' and isset($oAction['useKey'])) {
												$tVar[(string) $oAction['name']] = $tParam[(string) $oAction['param']][$this->getVarInTab($tVar, (string) $oAction['useKey'])];
											} else if ((string) $oAction['source'] == 'snippet') {

												$sSnippet = $this->getVarInTab($tVar, (string) $oAction['param']);
												$tReplace = array();
												if (isset($oAction->pattern)) {
													foreach ($oAction->pattern as $oPattern) {
														$tReplace[(string) $oPattern['tag']] = $this->getVarInTab($tVar, (string) $oPattern['value']);
													}
												}
												$tVar[(string) $oAction['name']] = $oSourceMain->getSnippet($sSnippet, $tReplace);
											}
										} else if ((string) $oAction['type'] == 'splitVariable') {

											/* <action type="splitVariable" source="$sTypeRaw" pattern=".">
											  <var name="sType"></var>
											  <var name="sModel"></var>
											  </action> */

											$sSourceTmp = $this->getVarInTab($tVar, (string) $oAction['source']);

											$tVarTmp = explode((string) $oAction['pattern'], $sSourceTmp);
											if (false == is_array($tVarTmp)) {
												$tVarTmp = array($tVarTmp);
											}
											$i = 0;
											foreach ($oAction->var as $oVar) {


												$tVar[(string) $oVar['name']] = null;
												if (isset($tVarTmp[$i])) {
													$tVar[(string) $oVar['name']] = $tVarTmp[$i];
												}
												$i++;
											}
										} else if ((string) $oAction['type'] == 'concatParam') {
											if (!isset($tParam[(string) $oAction['name']])) {
												$tParam[(string) $oAction['name']] = null;
											}

											$tConcatVar[] = (string) $oAction['name'];

											$tParam[(string) $oAction['name']] .= $this->getVarInTab($tVar, (string) $oAction['value']);
										}
									}
								}
							}
						}
					}
				}
			}
		}


		/* SOURCE */$oSourceMain = $this->getObjectSource($sSource);
		foreach ($tParam as $sTag => $sValue) {
			if (is_array($sValue)) {
				continue;
			}

			$sValue = $this->replaceTab($tParam, $sValue);

			/* SOURCE */$oSourceMain->setPattern('VAR' . $sTag . 'ENDVAR', $sValue);
		}


		/* SOURCE */

		return $oSourceMain;
	}

	public function replaceTab($tParam_, $sValue_) {
		foreach ($tParam_ as $sTag => $sValue) {
			if (is_array($sValue)) {
				continue;
			}

			$sValue_ = str_replace('VAR' . $sTag . 'ENDVAR', $sValue, $sValue_);
		}

		return $sValue_;
	}

	public function getFinishData($tSource_, $sText_) {
		$sHtml = $this->tr('moduleCreeAvecSucess');

		$sHtml .= '<h2>' . $this->tr('resumeGeneration') . '</h2>';
		$sHtml .= '<ul>';

		if ($tSource_) {
			foreach ($tSource_ as $tDetailSource) {
				list($sSource, $bStatus) = $tDetailSource;
				$sStyle = null;
				if ($bStatus === false) {
					$sStyle = 'style="color:red"';
				}
				$sHtml .= '<li ' . $sStyle . '>' . str_replace('#FICHIER#', $sSource, $this->tr('CreationDuFichierVAR')) . '</li>';
			}
		}
		$sHtml .= '</ul>';

		$sHtml .= $sText_;

		return $sHtml;
	}

	public function check() {
		foreach ($this->_oXml as $oStep) {
			if ((int) $oStep['id'] === (int) $this->_iStep or (int) $oStep['id'] === ((int) $this->_iStep + 1)) {
				foreach ($oStep->form->row as $row) {
					$sName = (string) $row['name'];
					$sRequired = (string) $row['required'];

					if ($this->paramExist($sName) and $sRequired === 'true' and $this->getParam($sName) == '') {
						return false;
					}
				}
			}
		}

		return true;
	}

	public function getPage() {
		$iStep = $this->_iStep;

		$tLang = plugin_i18n::getList();

		foreach ($this->_oXml as $oStep) {
			if ((int) $oStep['id'] === (int) $iStep) {
				$sHtml = (string) $oStep->page->html;

				//traductions

				foreach ($tLang as $sTag => $sVal) {
					$sHtml = str_replace('TR' . $sTag . 'ENDTR', $sVal, $sHtml);
				}

				if (preg_match('/TR([a-zA-Z]*)ENDTR/', $sHtml)) {
					preg_match_all('/(TR[a-zA-Z]*ENDTR)/', $sHtml, $tLangReplace);
					throw new Exception('Erreur Builder: il manque des traductions pour le step :' . $this->_iStep . ' la liste:' . implode(',', $tLangReplace[1]));
				}

				if (isset($oStep->page->codes)) {
					foreach ($oStep->page->codes->children() as $oCode) {
						$sHtml = str_replace('#' . (string) $oCode['id'] . '#', '<div class="code">' . highlight_string((string) $oCode, 1) . '</div>', $sHtml);
					}
				}

				return $sHtml;
			}
		}
	}

	public function show($data_) {
		$sForm = null;
		$tPrevPost = array();

		$oData = new stdClass();
		$oData->step = $this->_iStep;
		$oData->nextStep = $this->_iStep + 1;

		$oForm = new plugin_form($oData);

		foreach ($this->_oXml as $oStep) {
			$this->_iMaxStep = $oStep['id'];

			if ((int) $oStep['id'] <= $this->_iStep) {
				$this->_tNavPath[] = $this->tr($oStep['label']);
			}
		}

		foreach ($this->_tParam as $sTag => $sReplace) {
			if (is_array($sReplace)) {
				continue;
			}
			$data_ = str_replace('VAR' . $sTag . 'ENDVAR', $sReplace, $data_);
		}

		if (preg_match_all('/(VAR[a-zA-Z]*ENDVAR)/', $data_, $tPatternNeeded)) {
			throw new Exception('Erreur Builder, sur page step ' . $this->_iStep . ', il y a des pattern qui n\'ont pas &eacute;t&eacute; remplac&eacute;: ' . implode(',', $tPatternNeeded[1]));
		}

		if (preg_match_all('/LINK([a-zA-Z0-9\/\.]*)ENDLINK/', $data_, $tPatternLink)) {
			foreach ($tPatternLink[1] as $sTag) {
				$tLinkOption = array('project' => _root::getParam('id'), 'file' => $sTag);
				$sReplace = '<a target="_blank" href="' . _root::getLink('code::index', $tLinkOption) . '">' . $sTag . '</a>';

				$data_ = str_replace('LINK' . $sTag . 'ENDLINK', $sReplace, $data_);
			}
		}

		$sForm = $data_;

		$sHiddenFormBack = '<input type="hidden" id="inputStep" name="step" value="' . $oData->step . '" />';
		$sHiddenFormBack .= '<input type="hidden" id="inputNextStep" name="nextStep" value="' . $oData->nextStep . '" />';

		$sHiddenForm = '<input type="hidden" name="step" value="' . $oData->step . '" />';
		$sHiddenForm .= '<input type="hidden"  name="nextStep" value="' . $oData->nextStep . '" />';

		if ($this->_tParam) {
			foreach ($this->_tParam as $sParam => $sValue) {
				if (in_array($sParam, array('step', 'nextStep', 'finish'))) {
					continue;
				}

				if (is_array($sValue)) {
					continue;
				}

				$sHiddenFormBack .= '<input type="hidden" name="' . $sParam . '" value="' . $sValue . '" />';

				$sHiddenForm .= '<input type="hidden" name="' . $sParam . '" value="' . $sValue . '" />';
			}
		}

		$oView = new _view('builderForm::index');
		$oView->tNavPath = $this->_tNavPath;
		$oView->sHiddenFormBack = $sHiddenFormBack;
		$oView->sHiddenForm = $sHiddenForm;
		$oView->sForm = $sForm;
		$oView->iMaxStep = $this->_iMaxStep;
		$oView->iStep = $this->_iStep;

		return $oView;
	}

	public function getVarInTab($tVar, $sVar) {
		if (substr($sVar, 0, 1) == '$' and isset($tVar[substr($sVar, 1)])) {
			return $tVar[substr($sVar, 1)];
		}

		return $sVar;
	}

	public function build() {
		$tLang = plugin_i18n::getList();

		$sForm = null;
		$tPrevPost = array();

		$oData = new stdClass();
		$oData->step = $this->_iStep;
		$oData->nextStep = $this->_iStep + 1;

		$oForm = new plugin_form($oData);

		foreach ($this->_oXml as $oStep) {
			$this->_iMaxStep = $oStep['id'];

			if ((int) $oStep['id'] < (int) $this->_iStep) {
				foreach ($oStep->form->row as $row) {
					$tPrevPost[] = $row['name'];
				}
			}

			if ((int) $oStep['id'] === (int) $this->_iStep) {
				foreach ($oStep->form->row as $row) {


					if (isset($row['type']) and (string) $row['type'] == 'html') {

						$sHtml=(string)$row->html;
						if (isset($row->codes)) {
							foreach ($row->codes->children() as $oCode) {
								$sHtml = str_replace('#' . (string) $oCode['id'] . '#', '<div class="code">' . highlight_string((string) $oCode, 1) . '</div>', $sHtml);
							}
						}


						foreach ($tLang as $sTag => $sVal) {
							$sHtml = str_replace('TR' . $sTag . 'ENDTR', $sVal, $sHtml);
						}
						$sForm.=$sHtml;

					}else if (isset($row['type']) and (string) $row['type'] == 'loop') {
						$tVar = array();

						if (isset($row['source']) and (string) $row['source'] == 'modelFieldList') {
							$sParam = (string) $row['param'];
							if (substr($sParam, 0, 1) === '$') {
								$sParam = $this->getParam(substr($sParam, 1));
							}
							$tIndex = $this->getSelectFieldModel($sParam);

							$sForm .= '<table>';
							$sForm .= '<tr>';

							foreach ($row->col as $oCol) {
								$sForm .= '<th>' . (string) $oCol->label . '</th>';
							}

							$sForm .= '</tr>';

							$i = -1;
							foreach ($tIndex as $sIndex_key => $sIndex_value) {
								++$i;

								$tVar[(string) $row['keyVar']] = $i;
								$tVar[(string) $row['valueVar']] = $sIndex_value;

								$sForm .= '<tr>';

								foreach ($row->col as $oCol) {
									$sName = (string) $oCol['name'];
									$sForm .= '<td>';

									if ($oCol->input['type'] == 'input') {
										$sValue = $this->getVarInTab($tVar, (string) $oCol->input['value']);

										$sForm .= '<input type="text" name="' . $sName . '[' . $i . ']" value="' . $sValue . '" />';
									} elseif ($oCol->input['type'] == 'textarea') {
										$sValue = $this->getVarInTab($tVar, (string) $oCol->input['value']);

										$sForm .= '<textarea  name="' . $sName . '[' . $i . ']" >' . $sValue . '</textarea>';
									} elseif ($oCol->input['type'] == 'select') {
										$sForm .= '<select name="' . $sName . '[' . $i . ']">';

										if ($oCol->input->option) {
											foreach ($oCol->input->option as $oOption) {
												$sForm .= '<option value="' . $oOption['value'] . '">' . tr((string) $oOption) . '</option>';
											}
										}

										if ($oCol->input->listOption) {
											if ($oCol->input->listOption['source'] == 'modelGetSelectList') {
												$tListModelGetSelect = $this->getSelectModelGetSelect();
												foreach ($tListModelGetSelect as $sKey => $sLabel) {
													$sForm .= '<option value="' . $sKey . '">' . $sLabel . '</option>';
												}
											}
										}

										$sForm .= '</select>';
									}

									$sForm .= '</td>';
								}

								$sForm .= '</tr>';
							}

							$sForm .= '</table>';
						}
					} else {
						$sName = (string) $row['name'];
						$sLabel = (string) $row->label;

						$sForm .= '<div class="row">';
						$sForm .= '<div class="label">' . $this->tr($sLabel) . '</div>';
						$sForm .= '<div class="input">';

						if ((string) $row->input['type'] === 'selectAutomatic') {
							if ((string) $row->input['source'] === 'moduleParentList') {
								$tIndex = $this->getSelectModuleParent();

								$sForm .= $this->getFormSelect($sName, $tIndex);
							} elseif ((string) $row->input['source'] === 'moduleChildList') {
								$sParam = (string) $row->input['param'];
								if (substr($sParam, 0, 1) === '$') {
									$sParam = $this->getParam(substr($sParam, 1));
								}

								$tIndex = $this->getSelectModuleChild($sParam);

								$sForm .= $this->getFormSelect($sName, $tIndex);
							} elseif ((string) $row->input['source'] === 'moduleActionList') {
								$sParam = (string) $row->input['param'];
								if (substr($sParam, 0, 1) === '$') {
									$sParam = $this->getParam(substr($sParam, 1));
								}

								$tIndex = $this->getSelectActionList($sParam);

								$sForm .= $this->getFormSelect($sName, $tIndex);
							} elseif ((string) $row->input['source'] === 'modelList') {
								$tIndex = $this->getSelectModel();

								$sForm .= $this->getFormSelect($sName, $tIndex);

							} elseif ((string) $row->input['source'] === 'profilList') {
								$tIndex = $this->getSelectProfil();

								$sForm .= $this->getFormSelect($sName, $tIndex);

							} elseif ((string) $row->input['source'] === 'tableList') {
								$sParam = (string) $row->input['param'];
								if (substr($sParam, 0, 1) === '$') {
									$sParam = $this->getParam(substr($sParam, 1));
								}
								$tIndex = $this->getSelectTable($sParam);

								$sForm .= $this->getFormSelect($sName, $tIndex);


							} elseif ((string) $row->input['source'] === 'modelFieldList') {
								$sParam = (string) $row->input['param'];
								if (substr($sParam, 0, 1) === '$') {
									$sParam = $this->getParam(substr($sParam, 1));
								}
								$tIndex = $this->getSelectFieldModel($sParam);

								$sForm .= $this->getFormSelect($sName, $tIndex);

							} elseif ((string) $row->input['source'] === 'tableFieldList') {
								$sParamProfil = (string) $row->input['param'];
								if (substr($sParamProfil, 0, 1) === '$') {
									$sParamProfil = $this->getParam(substr($sParamProfil, 1));
								}
								$sParamTable = (string) $row->input['param2'];
								if (substr($sParamTable, 0, 1) === '$') {
									$sParamTable = $this->getParam(substr($sParamTable, 1));
								}

								$tIndex = $this->getSelectFieldByProfilAndTable($sParamProfil,$sParamTable);

								$sForm .= $this->getFormSelect($sName, $tIndex);


							} elseif ((string) $row->input['source'] === 'pluginList') {
								$tIndex = $this->getSelectPlugin();

								$sForm .= $this->getFormSelect($sName, $tIndex);
							}
						} elseif ((string) $row->input['type'] === 'input') {
							$sValue = (string) $row->input['value'];

							if (substr($sValue, 0, 1) === '$') {
								$sValue = $this->getParam(substr($sValue, 1));
							}
							$sForm .= '<input type="text" name="' . $sName . '" value="' . $sValue . '" />';
						} elseif ((string) $row->input['type'] === 'textarea') {
							$sValue = (string) $row->input['value'];

							if (substr($sValue, 0, 1) === '$') {
								$sValue = $this->getParam(substr($sValue, 1));
							}
							$sForm .= '<textarea class="textarea" name="' . $sName . '" >' . $sValue . '</textarea>';
						} elseif ((string) $row->input['type'] === 'multiInput') {
							$sValue = (string) $row->input['value'];

							if (substr($sValue, 0, 1) === '$') {
								$sValue = $this->getParam(substr($sValue, 1));
							}
							$sForm .= '<div id="multi' . $sName . '"><input type="text" name="' . $sName . '[]" /><br/></div>';
							$sForm .= '<br/><input type="button" onclick="addInput(\'multi' . $sName . '\',\'' . $sName . '\')" value="' . tr('ajouter') . '"/>';
							$sForm .= '';
						} elseif ((string) $row->input['type'] === 'read') {
							$sParam = (string) $row->input['value'];
							if (substr($sParam, 0, 1) === '$') {
								$sParam = $this->getParam(substr($sParam, 1));
							}
							$sForm .= $sParam;
						}

						if ($this->paramExist($sName) and $this->getParam($sName) == '') {
							$sForm .= '<p class="error">required</p>';
						}

						$sForm .= '</div>';

						$sForm .= '<div class="clear"></div>';
						$sForm .= '</div>';
					}
				}
			}

			if ((int) $oStep['id'] <= $this->_iStep) {
				$this->_tNavPath[] = $this->tr($oStep['label']);
			}
		}

		if ($oData->nextStep > $this->_iMaxStep) {
			$oData->nextStep = $this->_iMaxStep;
		}

		$sHiddenFormBack = '<input type="hidden" id="inputStep" name="step" value="' . $oData->step . '" />';
		$sHiddenFormBack .= '<input type="hidden" id="inputNextStep" name="nextStep" value="' . $oData->nextStep . '" />';

		$sHiddenForm = '<input type="hidden" name="step" value="' . $oData->step . '" />';
		$sHiddenForm .= '<input type="hidden" name="nextStep" value="' . $oData->nextStep . '" />';

		if ($this->_tParam) {
			foreach ($this->_tParam as $sParam => $sValue) {
				if (in_array($sParam, array('step', 'nextStep'))) {
					continue;
				}
				if (false === is_array($sValue)) {
					$sHiddenFormBack .= '<input type="hidden" name="' . $sParam . '" value="' . $sValue . '" />';

					$sHiddenForm .= '<input type="hidden" name="' . $sParam . '" value="' . $sValue . '" />';
				}
			}
		}

		$oView = new _view('builderForm::index');
		$oView->tNavPath = $this->_tNavPath;
		$oView->sHiddenForm = $sHiddenForm;
		$oView->sHiddenFormBack = $sHiddenFormBack;
		$oView->sForm = $sForm;
		$oView->iMaxStep = $this->_iMaxStep;
		$oView->iStep = $this->_iStep;

		return $oView;
	}

	public function tr($sTag_) {
		return tr((string) $sTag_);
	}

}
