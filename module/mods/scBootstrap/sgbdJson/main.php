<?php

class module_mods_scBootstrap_sgbdJson extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_sgbdJson';
	protected $sModuleView = 'mods/scBootstrap/sgbdJson';

	public function _index() {
		$msg = '';
		$detail = '';
		if ($this->isPost()) {

			$sTable = _root::getParam('sTable');
			$tField = explode("\n", _root::getParam('sField'));

			$this->projectMkdir('data/json/base/' . $sTable);

			$this->generate($sTable, $tField);

			$msg = trR('baseTableGenereAvecSucces', array('#maTable#' => $sTable, '#listField#' => implode(',', $tField)));

			$detail = trR('creationRepertoire', array('#REPERTOIRE#' => 'data/json/base/' . $sTable));
			$detail .= '<br />' . trR('creationFichier', array('#FICHIER#' => 'data/json/base/' . $sTable . '/structure.csv'));
			$detail .= '<br />' . trR('creationFichier', array('#FICHIER#' => 'data/json/base/' . $sTable . '/max.txt'));
		}

		$oTpl = $this->getView('index');
		$oTpl->msg = $msg;
		$oTpl->detail = $detail;
		return $oTpl;
	}

	private function generate($sTable, $tField) {

		$tNewField = array('id');

		foreach ($tField as $sField) {
			if (trim($sField) == '')
				continue;
			$tNewField[] = trim($sField);
		}
		$sStructure = implode(';', $tNewField);

		$this->projectMkdir('data/json');
		$this->projectMkdir('data/json/base');
		$this->projectMkdir('data/json/base/' . $sTable);

		$sPath = 'data/json/base/' . $sTable . '/';
		$this->projectSaveFile($sStructure, $sPath . 'structure.csv');
		$this->projectSaveFile(1, $sPath . 'max.txt');
	}

}
