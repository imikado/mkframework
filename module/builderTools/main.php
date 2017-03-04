<?php

class module_builderTools {

	public function getListTablesFromConfig($sConfig) {

		if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'xml') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'csv') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'json') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		}

		$oModelFactory = new model_mkfbuilderfactory();
		$oModelFactory->setConfig($sConfig);
		return $oModelFactory->getListTable();
	}

	public function getListColumnFromConfigAndTable($sConfig, $sTable) {

		if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'xml') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'csv') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'json') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		}

		$oModelFactory = new model_mkfbuilderfactory();
		$oModelFactory->setConfig($sConfig);
		$oModelFactory->setTable($sTable);
		return $oModelFactory->getListColumn();
	}

	public function getListRowsFromConfigAndTable($sConfig, $sTable) {

		if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'xml') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'csv') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'json') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		}

		$oModelFactory = new model_mkfbuilderfactory();
		$oModelFactory->setConfig($sConfig);
		$oModelFactory->setTable($sTable);
		return $oModelFactory->findMany('SELECT * FROM ' . $sTable);
	}




	public function getListColumnFromClass($sClass) {

		$sPath = _root::getConfigVar('path.generation') . _root::getParam('id') . '/model/' . $sClass . '.php';
		require_once( $sPath );

		$oModel = new $sClass;

		$sConfig = $oModel->getConfig();

		if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'xml') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'csv') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'json') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		}

		return $oModel->getListColumn();
	}

	public function loadConfig($sClass) {
		$sPath = _root::getConfigVar('path.generation') . _root::getParam('id') . '/model/' . $sClass . '.php';
		require_once( $sPath );
		$oModel = new $sClass;

		$sConfig = $oModel->getConfig();

		if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'xml') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'csv') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		} else if (_root::getConfigVar('db.' . $sConfig . '.sgbd') == 'json') {
			if (!file_exists(_root::getConfigVar('db.' . $sConfig . '.database'))) {
				$sBuilderDbPath = _root::getConfigVar('path.data') . 'genere/' . _root::getParam('id') . '/public/' . _root::getConfigVar('db.' . $sConfig . '.database');
				if (file_exists($sBuilderDbPath)) {
					_root::setConfigVar('db.' . $sConfig . '.database', $sBuilderDbPath);
				} else {
					throw new Exception('Base inexistante ' . _root::getConfigVar('db.' . $sConfig . '.database') . ' ni ' . $sBuilderDbPath);
				}
			}
		}

		return $oModel->getListColumn();
	}

	public function getIdTabFromClass($sClass) {
		$sPath = _root::getConfigVar('path.generation') . _root::getParam('id') . '/model/' . $sClass . '.php';
		require_once( $sPath );
		$oModel = new $sClass;

		return $oModel->getIdTab();
	}

	public function rootAddConf($sConf) {
		_root::addConf(_root::getConfigVar('path.generation') . _root::getParam('id') . '/' . $sConf);
		_root::loadConf();
	}

	public function updateLayoutTitle($sProject) {

		$sContent = $this->stringReplaceIn(array(
		    'examplesite' => $sProject
			), _root::getConfigVar('path.generation') . $sProject . '/layout/template1.php'
		);

		$oFile = new _file(_root::getConfigVar('path.generation') . $sProject . '/layout/template1.php');
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}

	public function updateFile($sProject, $tMatch, $sFile) {
		$sContent = $this->stringReplaceIn($tMatch, _root::getConfigVar('path.generation') . $sProject . '/' . $sFile
		);

		$oFile = new _file(_root::getConfigVar('path.generation') . $sProject . '/' . $sFile);
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}

	public function stringReplaceIn($tMatch, $sFile) {
		$oFile = new _file($sFile);
		$sContent = $oFile->getContent();
		foreach ($tMatch as $sPattern => $sReplace) {
			$sContent = preg_replace('/' . $sPattern . '/s', $sReplace, $sContent);
		}
		return $sContent;
	}

	public function stringReplaceInContent($tMatch, $sContent) {

		foreach ($tMatch as $sPattern => $sReplace) {
			$sContent = preg_replace('/' . $sPattern . '/s', $sReplace, $sContent);
		}
		return $sContent;
	}

	public function getListModule() {
		$oDir = new _dir(_root::getConfigVar('path.generation') . _root::getParam('id') . '/module/');
		$tDir = $oDir->getListDir();
		$tNewDir = array();
		foreach ($tDir as $oModule) {
			$sModuleName = $oModule->getName();
			if (in_array($sModuleName, array('menu', 'builder', 'example', 'exampleembedded'))) {
				continue;
			}
			if (!file_exists(module_builder::getTools()->getRootWebsite() . 'module/' . $sModuleName . '/main.php')) {
				continue;
			}

			$tNewDir[] = $oModule;
		}
		return $tNewDir;
	}

	public function getListModuleAndSousModule() {
		$sRoot = _root::getConfigVar('path.generation') . _root::getParam('id') . '/module/';
		$oDir = new _dir($sRoot);
		$tDir = $oDir->getListDir();
		$tNewDir = array();
		foreach ($tDir as $oModule) {
			$sModuleName = $oModule->getName();
			if (in_array($sModuleName, array('menu', 'builder', 'example', 'exampleembedded', 'view','i18n'))) {
				continue;
			}
			if (!file_exists(module_builder::getTools()->getRootWebsite() . 'module/' . $sModuleName . '/main.php')) {
				continue;
			}

			$tNewDir[] = $sModuleName;

			$tDir2 = $oModule->getListDir();
			foreach ($tDir2 as $oModule2) {
				$sModuleName2 = $oModule2->getName();
				if (in_array($sModuleName2, array('view','i18n'))) {
					continue;
				}
				$tNewDir[] = $sModuleName . '/' . $sModuleName2;
			}
		}
		return $tNewDir;
	}

	public function getRootWebsite() {
		return _root::getConfigVar('path.generation') . _root::getParam('id') . '/';
	}

	public function projetmkdir($sRep) {
		$oDir = new _dir(_root::getConfigVar('path.generation') . _root::getParam('id') . '/' . $sRep);
		try {
			$oDir->save();
			$oDir->chmod(0777);
		} catch (Exception $e) {
			//pas grave si repertoire existe deja, mais on avertir quand meme
			return false;
		}
		return true;
	}

	public function getSource($sModulePath, $sProjectPath, $sFilename) {
		return new builderSource($sModulePath, $sProjectPath, $sFilename);
	}

}

class builderSource {

	private $sModulePath;
	private $sProjectPath;
	private $sSource;
	private $sSourceXml;
	private $oSourceXml;
	private $tReplace;
	private $sFilenameCreated;

	public function __construct($sModulePath, $sProjectPath, $sSource) {
		$this->sModulePath = $sModulePath . '/src';
		$this->sProjectPath = $sProjectPath;

		$this->sSource = $sSource;
		$sSourceXml = $sSource . '.xml';
		if (file_exists($this->getPath($sSourceXml))) {
			$this->sSourceXml = $sSourceXml;
			$this->oSourceXml = simplexml_load_file($this->getPath($sSourceXml));
		} else {
			throw new Exception("ERREUR Builder, il manque le fichier xml '" . $this->getPath($sSourceXml) . "'", 1);
		}
	}

	public function getFilenameCreated() {
		return $this->sFilenameCreated;
	}

	public function setPattern($sPattern, $sValue) {
		$this->tReplace[$sPattern] = $sValue;
	}

	public function setListPattern($tPattern) {
		foreach ($tPattern as $sPattern => $sValue) {
			$this->tReplace[$sPattern] = $sValue;
		}
	}

	public function save() {
		$tReplaceNeed = array();
		if (isset($this->oSourceXml->patterns)) {
			$tReplaceNeed = (array) $this->oSourceXml->patterns;
			if (!isset($tReplaceNeed['pattern'])) {
				throw new Exception("ERREUR Builder, Il manque le xml pattern (" . $this->sSource . ")");
			}

			$tReplaceNeed = $tReplaceNeed['pattern'];
			if ($tReplaceNeed and is_array($tReplaceNeed)) {
				foreach ($tReplaceNeed as $var) {
					$var = (string) $var;
					if (!array_key_exists($var, $this->tReplace)) {
						throw new Exception("ERREUR Builder, Il manque le pattern '$var' (" . $this->sSource . "), pattern renseignes:" . implode(',', array_keys($this->tReplace)));
					}
				}
			}
		}


		$sContent = $this->stringReplaceIn($this->tReplace, $this->getPath($this->sSource));

		if (false === isset($this->oSourceXml->patterns)) {
			if (preg_match_all('/(VAR[a-zA-Z]*ENDVAR)/', $sContent, $tPattern)) {

				throw new Exception('Erreur Builder, sur ' . $this->getPath($this->sSource) . ' il y a des pattern qui n\'ont pas &eacute;t&eacute; remplac&eacute;: ' . implode(',', $tPattern[1]));
			}
		}

		if (isset($this->oSourceXml->target)) {
			if (isset($this->oSourceXml->path)) {
				//mkdir si necessaire
				$tPath = (array) $this->oSourceXml->path;
				$tDirectory = $tPath['directory'];
				if (!is_array($tDirectory)) {
					$tDirectory = array($tDirectory);
				}
				if ($tDirectory and is_array($tDirectory)) {
					foreach ($tDirectory as $sDirectory) {
						$this->projetmkdir($this->stringReplaceInContent($this->tReplace, $sDirectory));
					}
				} else {
					throw new Exception('Pb xml path/directory');
				}
			}

			$sTargetFilename = $this->stringReplaceInContent($this->tReplace, (string) $this->oSourceXml->target);

			$sTargetFilename = $this->getProjectPath($sTargetFilename);

			$this->sFilenameCreated = $sTargetFilename;

			if (file_exists($sTargetFilename)) {
				return false;
			}

			file_put_contents($sTargetFilename, $sContent);
			chmod($sTargetFilename, 0666);


			return true;
		}
	}

	public function exist() {
		$sTargetFilename = $this->stringReplaceInContent($this->tReplace, (string) $this->oSourceXml->target);
		$sTargetFilename = $this->getProjectPath($sTargetFilename);
		return file_exists($sTargetFilename);
	}

	private function getPath($sFilename) {
		return $this->sModulePath . '/' . $sFilename;
	}

	public function getSnippet($sSnippet, $tReplace = null) {

		if (isset($this->oSourceXml->snippets) and isset($this->oSourceXml->snippets->$sSnippet) and isset($this->oSourceXml->snippets->$sSnippet->code) and isset($this->oSourceXml->snippets->$sSnippet->patterns)) {
			$tReplaceNeed = (array) $this->oSourceXml->snippets->$sSnippet->patterns;
			$tReplaceNeed = $tReplaceNeed['pattern'];
			if ($tReplaceNeed and is_array($tReplaceNeed)) {
				foreach ($tReplaceNeed as $var) {
					$var = (string) $var;
					if ($tReplace and ! array_key_exists($var, $tReplace)) {
						throw new Exception("ERREUR Builder, Il manque le pattern '$var' (snippet $sSnippet, source:" . $this->sSource . "), pattern renseignes:" . implode(',', array_keys($tReplace)));
					}

					if (!$tReplace) {
						throw new Exception("ERREUR Builder, Il manque les patterns  (snippet $sSnippet, source:" . $this->sSource . "), pattern necessaires:" . implode(',', $tReplaceNeed));
					}
				}
			}

			return $this->stringReplaceInContent($tReplace, (string) $this->oSourceXml->snippets->$sSnippet->code);
		} else if (isset($this->oSourceXml->snippets) and isset($this->oSourceXml->snippets->$sSnippet) and isset($this->oSourceXml->snippets->$sSnippet->code)) {
			return $this->stringReplaceInContent($tReplace, (string) $this->oSourceXml->snippets->$sSnippet->code);
		}
	}

	private function stringReplaceInContent($tMatch, $sContent) {
		if ($tMatch) {
			foreach ($tMatch as $sPattern => $sReplace) {
				$sContent = preg_replace('/' . $sPattern . '/s', $sReplace, $sContent);
			}
		}
		return $sContent;
	}

	private function stringReplaceIn($tMatch, $sFile) {
		$oFile = new _file($sFile);
		$sContent = $oFile->getContent();
		if ($tMatch and is_array($tMatch)) {
			foreach ($tMatch as $sPattern => $sReplace) {
				$sContent = preg_replace('/' . $sPattern . '/s', $sReplace, $sContent);
			}
		}
		return $sContent;
	}

	private function getProjectPath($sFilename) {
		return $this->sProjectPath . '/' . $sFilename;
	}

	private function projetmkdir($sRep) {
		$oDir = new _dir($this->sProjectPath . '/' . $sRep);
		try {
			$oDir->save();
			$oDir->chmod(0777);
		} catch (Exception $e) {
			//pas grave si repertoire existe deja, mais on avertir quand meme
			return false;
		}
		return true;
	}

}
