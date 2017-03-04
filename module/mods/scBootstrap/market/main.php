<?php

class module_mods_scBootstrap_market extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_market';
	protected $sModuleView = 'mods/scBootstrap/market';
	private $msg = null;
	private $detail = null;
	private $tError = null;
	private $errorZip = null;
	public static $idModule = null;

	public static function formate($sText) {

		$sRootUrl = _root::getConfigVar('market.builder.url');
		$sRootUrl = str_replace('Builder', 'Application', $sRootUrl);
		$sRootUrl .= 'pages/';

		list($foo, $sModule) = explode('.', self::$idModule);

		$sText2 = str_replace('marketImage/', $sRootUrl . '/images/' . $sModule . '/', $sText);

		return $sText2;
	}

	public function _menu() {
		$tLink = array(
		    'ajouterUneExtension' => 'install',
		);

		$oTpl = $this->getView('menu');

		$oTpl->tLink = $tLink;

		return $oTpl;
	}

	public function _index() {

		$sAction = _root::getParam('saction', 'install');
		if ($sAction == 'install') {
			return $this->getActionPackages();
		} else if ($sAction == 'installExtModule') {
			return $this->getActionInstallExtModule();
		} else if ($sAction == 'installExtPlugin') {
			return $this->getActionInstallExtPlugin();
		}

		return $this->getView('accueil');
	}

	private function copyTo($sUrl, $sTarget) {
		$sDir = dirname($sTarget);
		if (is_writable($sDir) == false) {
			$this->errorZip = trR('repertoireNonWritable', array('#repertoire#' => $sDir));
			return false;
		}

		try {
			file_put_contents($sTarget, file_get_contents($sUrl . '.down'));

			return true;
		} catch (Exception $e) {
			$this->errorZip = trR('urlNonDispo', array('#url#' => $sUrl)) . $e->getMessage();
			return false;
		}
	}

	private function unzipTo($sUrl, $sTarget) {
		$sDir = dirname($sTarget);
		if (is_writable($sDir) == false) {
			$this->errorZip = trR('repertoireNonWritable', array('#repertoire#' => $sDir));
			return false;
		}

		try {
			file_put_contents($sTarget . '.zip', file_get_contents($sUrl));
		} catch (Exception $e) {
			$this->errorZip = trR('urlNonDispo', array('#url#' => $sUrl)) . $e->getMessage();
			return false;
		}

		list($foo, $sTargetDirZip) = explode('.', $sTarget);
		$sTargetDir = $sDir . '/' . $sTargetDirZip;

		$zip = new ZipArchive;
		if ($zip->open($sTarget . '.zip')) {

			$zip->extractTo($sTargetDir);
			$zip->close();

			try {
				chmod($sTargetDir, 0777);
			} catch (Exception $e) {

			}
			//menage
			unlink($sTarget . '.zip');
		}
		return true;
	}

	/*
	  pages/fr/index.xml
	  <?xml version="1.0" ?>
	  <page>
	  <type>content</type>
	  <title>test</title>
	  <content>toto</content>
	  <nav>
	  <link href="index">accueil</link>
	  <link href="bootstrap_list_1">Bootstrap</link>
	  </nav>
	  </page>

	  pages/fr/bootstrap_list_1.xml
	  <?xml version="1.0" ?>
	  <page>
	  <type>list</type>
	  <title>test</title>
	  <content>presentation bootstrap</content>
	  <nav>
	  <link href="index">accueil</link>
	  <link href="bootstrap_list_1">Bootstrap</link>
	  </nav>
	  <data>
	  <bloc>
	  <title>couche model</title>
	  <id>mods_all_model</id>
	  </bloc>
	  <bloc>
	  <title>module crud</title>
	  <id>mods_bootstrap_</id>
	  </bloc>
	  </data>
	  </page>
	 */

	private function getActionPackages() {


		$tLocalIni = $this->getListIni();

		$sPage = _root::getParam('market', 'index');

		$oXml = $this->getRemoteMarketPage($sPage);

		$tType = array('content', 'list', 'detail_module', 'detail_plugin');
		$sType = (string) $oXml->type;
		if (!in_array($sType, $tType)) {
			return $this->getView('error');
		}


		$oViewHead = $this->getView('market_head');
		$oViewHead->tNav = $this->getNav($oXml->nav->link);
		$sViewHead = $oViewHead->show();


		$oView = $this->getView('market_' . $sType);
		$oView->sHead = $sViewHead;
		$oView->title = (string) $oXml->title;
		$oView->content = (string) $oXml->content;

		if ($sType == 'list') {
			$oView->tBloc = $this->getBlocs($oXml->data->bloc);
		} else if (in_array($sType, array('detail', 'detail_module', 'detail_plugin'))) {
			$oView->id = (string) $oXml->id;
			$oView->version = (string) $oXml->version;
			$oView->author = (string) $oXml->author;

			$oView->presentation = (string) $oXml->presentation;
			$oView->utilisation = (string) $oXml->utilisation;
			$oView->actualites = (string) $oXml->actualites;
		}

		$oView->tLocalIni = $tLocalIni;

		return $oView;
	}

	//install
	private function getActionInstallExtModule() {

		$sError = $this->installExtModuleInProject(_root::getParam('sid'), _root::getParam('version'));

		$oView = $this->getView('market_install');
		$oView->error = $sError;

		return $oView;
	}

	private function installExtModuleInProject($sModule, $sVersion) {
		$sRootUrl = _root::getConfigVar('market.builder.url');
		$sRootUrl = str_replace('Builder', 'Application', $sRootUrl);

		$sPathModule = $sModule;

		$sRootPathModule = module_builder::getTools()->getRootWebsite() . 'module';
		$sRootPathApplication = module_builder::getTools()->getRootWebsite();

		if (!$this->unzipTo($sRootUrl . '/module/' . $sModule . $sVersion . '.zip', $sRootPathModule . '/' . $sPathModule)) {
			return $this->errorZip;
		}
		list($foo, $sModuleDir) = explode('.', $sPathModule);
		if (file_exists($sRootPathModule . '/' . $sModuleDir . '/install.ini')) {
			$tInstallIni = parse_ini_file($sRootPathModule . '/' . $sModuleDir . '/install.ini', true);
			foreach ($tInstallIni as $tCopy) {
				$sFrom = $sRootPathModule . '/' . $sModuleDir . '/' . $tCopy['from'];
				$sTo = $sRootPathApplication . '/' . $tCopy['to'];

				$sDir = dirname($sTo);
				if (!is_writable($sDir)) {
					return trR('repertoireNonWritable', array('#repertoire#' => $sDir));
				}
				if (file_exists($sTo)) {
					return trR('fichierExisteDeja', array('#fichier#' => $sTo));
				}

				rename($sFrom, $sTo);
			}
		}
	}

	//install
	private function getActionInstallExtPlugin() {

		$sError = $this->installExtPluginInProject(_root::getParam('sid'), _root::getParam('version'));

		$oView = $this->getView('market_install');
		$oView->error = $sError;

		return $oView;
	}

	private function installExtPluginInProject($sPlugin, $sVersion) {
		$sRootUrl = _root::getConfigVar('market.builder.url');
		$sRootUrl = str_replace('Builder', 'Application', $sRootUrl);


		$sRootPathPlugin = module_builder::getTools()->getRootWebsite() . 'plugin/';

		if (!$this->copyTo($sRootUrl . '/plugin/' . $sPlugin, $sRootPathPlugin . '/' . $sPlugin)) {
			return $this->errorZip;
		}
		if (!$this->copyTo($sRootUrl . '/plugin/' . $sPlugin . '.ini', $sRootPathPlugin . '/' . $sPlugin . '.ini')) {
			return $this->errorZip;
		}
	}

	private function getNav($tXmlNav) {
		$tNav = array();
		foreach ($tXmlNav as $oXml) {
			$tNav[(string) $oXml['href']] = (string) $oXml;
		}
		return $tNav;
	}

	private function getBlocs($tXmlBlocs) {
		$tBlocs = array();
		if ($tXmlBlocs) {
			foreach ($tXmlBlocs as $oXmlBloc) {
				$tBlocs[] = array(
				    'title' => (string) $oXmlBloc->title,
				    'id' => (string) $oXmlBloc->id,
				    'author' => (string) $oXmlBloc->author,
				    'version' => (string) $oXmlBloc->version,
				);
			}
		}
		return $tBlocs;
	}

	public function getRemoteMarketPage($sPage) {
		$sRootUrl = _root::getConfigVar('market.builder.url');
		$sRootUrl = str_replace('Builder', 'Application', $sRootUrl);
		$sRootUrl .= 'pages/';
		$sRootUrl .= _root::getConfigVar('language.default');
		print $sRootUrl . '/' . $sPage . '.xml';
		$sXml = file_get_contents($sRootUrl . '/' . $sPage . '.xml');
		$oXml = simplexml_load_string($sXml);

		return $oXml;
	}

	public static function getMarketLink($sAction) {
		return _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'install', 'market' => $sAction));
	}

	public static function getInstallLinkModule($sId, $sVersion) {
		return _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'installExtModule', 'sid' => $sId, 'version' => $sVersion));
	}

	public static function getInstallLinkPlugin($sId, $sVersion) {
		return _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'installExtPlugin', 'sid' => $sId, 'version' => $sVersion));
	}

	public function getListIni() {
		$tLinkModule = array();

		$tLocalIni = array();

		$sPathModule = module_builder::getTools()->getRootWebsite() . 'module';

		$tModulesAll = scandir($sPathModule);
		foreach ($tModulesAll as $sModule) {
			if (file_exists($sPathModule . '/' . $sModule . '/info.ini')) {


				$tIni = parse_ini_file($sPathModule . '/' . $sModule . '/info.ini');

				$tLocalIni[$tIni['id']] = $tIni['version'];
			}
		}

		return $tLocalIni;
	}

}
