<?php

/*
  This file is part of Mkframework.

  Mkframework is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License.

  Mkframework is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License
  along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

 */

class module_builder extends abstract_module {

	public static $oTools;
	public static $sLayout;

	public static function setLayout($sLayout) {
		if (!in_array($sLayout, array('templateProjet', 'templateProjetLight'))) {
			return;
		}
		self::$sLayout = $sLayout;
	}

	public static function getTools() {
		return self::$oTools;
	}

	public function before() {
		self::$oTools = new module_builderTools();

		$this->oLayout = new _layout('template1');

		$this->oLayout->addModule('menu', 'menu::index');
	}

	private function getList() {
		$oProjetModel = new model_mkfbuilderprojet;
		$tProjet = $oProjetModel->findAll();

		sort($tProjet); //tri par ordre alphabetique

		$oTpl = new _tpl('builder::list');
		$oTpl->tProjet = $tProjet;

		return $oTpl;
	}

	public function _index() {
		_root::redirect('builder::new');
	}

	public function _list() {

		$oTpl = $this->getList();

		$this->oLayout->add('main', $oTpl);
	}

	public function _new() {
		if (_root::getRequest()->isPost()) {

			$sProject = _root::getParam('projet');
			$sOpt = _root::getParam('opt');

			if ($sOpt == 'withexamples') {
				model_mkfbuilderprojet::getInstance()->create(_root::getParam('projet'));
				self::getTools()->updateLayoutTitle(_root::getParam('projet'));
			} else if ($sOpt == 'withBootstrap') {
				model_mkfbuilderprojet::getInstance()->createEmpty($sProject);

				//copy bootstrap
				model_mkfbuilderprojet::getInstance()->copyFromTo('data/sources/fichiers/layout/bootstrap.php', _root::getConfigVar('path.generation') . $sProject . '/layout/bootstrap.php');

				//update title
				self::getTools()->updateFile(_root::getParam('projet'), array('examplesite' => $sProject), 'layout/bootstrap.php');

				//update layout
				self::getTools()->updateFile(_root::getParam('projet'), array('template1' => 'bootstrap'), 'module/default/main.php');
			} else if ($sOpt == 'scWithBootstrap') {
				model_mkfbuilderprojet::getInstance()->createScWithBootstrap($sProject);
			} else {
				model_mkfbuilderprojet::getInstance()->createEmpty(_root::getParam('projet'));
				self::getTools()->updateLayoutTitle(_root::getParam('projet'));
			}
			_root::redirect('builder::list');
		}

		$oTpl = new _tpl('builder::new');
		$oTpl->iswritable = is_writable(_root::getConfigVar('path.generation'));

		$this->oLayout->add('main', $oTpl);
	}

	public function _marketBuilder() {
		$this->oLayout->addModule('main', 'mods_builder_market::menu');
		$this->oLayout->addModule('main', 'mods_builder_market::index');
	}

	public function _export() {
		$tReturn = $this->processExport();

		$this->oLayout->setLayout('templateProjet');

		$oTplList = $this->getList();

		$this->oLayout->add('list', $oTplList);

		$this->oLayout->addModule('nav', 'menu::export');

		$oTpl = new _tpl('builder::export');
		$oTpl->tReturn = $tReturn;


		$this->oLayout->add('main', $oTpl);
	}

	private function processExport() {
		if (!_root::getRequest()->isPost()) {
			return array();
		}


		$sFrom = _root::getConfigVar('path.generation') . _root::getParam('from') . '/';
		$sTo = _root::getParam('to') . '/' . _root::getParam('from');

		$oDir = new _dir($sTo);
		if ($oDir->exist()) {
			return array('error' => 'Repertoire ' . $sTo . ' existe deja');
		}

		if (!in_array(_root::getParam('lib'), array('link', 'copy'))) {
			return array('error' => 'Veuillez s&eacute;lectionner un choix pour la librairie du framework');
		}

		$oModelProject = model_mkfbuilderprojet::getInstance()->copyFromTo($sFrom, $sTo);

		if (_root::getParam('lib') == 'link') {

			$sLib = __DIR__;
			$sLib = str_replace('module/builder', 'lib/framework/', $sLib);

			$this->updateLibPathInConf($sTo, $sLib);

			$detail = 'Projet cr&eacute;e dans ' . $sTo;
			$detail .= '<br/>Dans votre projet, la librairie du framework pointe sur ' . $sLib;

			return array('ok' => 'Projet bien export&eacute; sur ' . $sTo, 'detail' => $detail);
		} else if (_root::getParam('lib') == 'copy') {

			$oDir = new _dir($sTo . '/lib/');
			$oDir->save();
			//copy du framework
			$oModelProject = model_mkfbuilderprojet::getInstance()->copyFromTo('lib/framework', $sTo . '/lib/mkframework');

			$sLib = '../lib/mkframework/';

			$this->updateLibPathInConf($sTo, $sLib);

			$detail = 'Projet cr&eacute;e dans ' . $sTo;
			$detail .= '<br/>Dans votre projet, la librairie du framework a ete copie dans ' . $sLib;

			return array('ok' => 'Projet bien export&eacute; sur ' . $sTo, 'detail' => $detail);
		}
	}

	private function updateLibPathInConf($sProject, $sLib) {
		//replace link library
		$oIniFile = new _file($sProject . '/conf/site.ini.php');
		$tIni = $oIniFile->getTab();

		$tNewIni = array();

		$bSection = 0;
		foreach ($tIni as $line) {
			if (preg_match('/\[path\]/', $line)) {
				$bSection = 1;
			} else if ($bSection && substr($line, 0, 3) == 'lib') {
				$line = 'lib=' . $sLib;
			}

			$tNewIni[] = $line;
		}

		$oIniFile->setContent(implode($tNewIni, ""));
		$oIniFile->save();
	}

	public function _edit() {

		self::setLayout('templateProjet');
		//$this->oLayout->setLayout('templateProjet');
		//$oTplList=$this->getList();
		//$this->oLayout->add('list',$oTplList);

		$this->oLayout->addModule('nav', 'mods_builder_menu::project');

		if (_root::getParam('action')) {
			$oTpl = new _tpl('builder::edit');
			$this->oLayout->add('main', $oTpl);
		}

		if (_root::getParam('action')) {
			$this->oLayout->addModule('main', _root::getParam('action'));
		}

		$this->oLayout->setLayout(self::$sLayout);
	}

	public function _editembedded() {
		$this->oLayout->setLayout('templateProjetEmbedded');

		$this->oLayout->addModule('nav', 'menu::projetEmbedded');

		$oTpl = new _tpl('builder::edit');
		$this->oLayout->add('main', $oTpl);

		if (_root::getParam('action')) {
			$this->oLayout->addModule('main', _root::getParam('action'));
		}
	}

	public function _lang() {
		$sLang = _root::getParam('switch');

		$bChange = false;
		$iswritable = true;
		$messageOK = null;
		$messageNOK = null;
		$message = null;

		if (_root::getConfigVar('language.default') != $sLang) {
			$bChange = true;

			$ret = "\n";

			$sContent = null;
			$sContent .= '[language]' . $ret;
			$sContent .= ';fr / en...' . $ret;
			$sContent .= 'default=' . $sLang . $ret;
			$sContent .= 'allow=fr,en' . $ret;

			//check writable
			$iswritable = is_writable(_root::getConfigVar('path.conf') . 'language.ini.php');
			if ($iswritable) {
				file_put_contents(_root::getConfigVar('path.conf') . 'language.ini.php', $sContent);

				_root::redirect('builder::new');
			} else {
				$messageNOK = sprintf(tr('builder::new_errorVotreRepertoirePasInscriptible'), _root::getConfigVar('path.conf') . 'language.ini.php');

				$message = sprintf(tr('builder::langVousPouvezEcrire'), $sContent, _root::getConfigVar('path.conf') . 'language.ini.php');
			}
		} else {
			$message = sprintf(tr('builder::langVotreLangueEstDeja'), $sLang);
		}

		$oTpl = new _tpl('builder::lang');
		$oTpl->bChange = $bChange;
		$oTpl->messageOK = $messageOK;
		$oTpl->messageNOK = $messageNOK;
		$oTpl->message = $message;

		$this->oLayout->add('main', $oTpl);
	}

	public function after() {
		$this->oLayout->show();
	}

	//------------------------------------------------
}

?>
