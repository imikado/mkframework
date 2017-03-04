<?php

class module_mods_scBootstrap_sgbdCsv extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_sgbdCsv';
	protected $sModuleView = 'mods/scBootstrap/sgbdCsv';

	public function _index() {
		$msg = '';
		$detail = '';
		if ($this->isPost()) {

			$sTable = _root::getParam('sTable');
			$tField = explode("\n", _root::getParam('sField'));

			$this->generate($sTable, $tField);

			$msg = trR('baseTableGenereAvecSucces', array('#maTable#' => $sTable, '#listField#' => implode(',', $tField)));
			$detail .= '<br />' . trR('creationFichier', array('#FICHIER#' => 'data/csv/base/' . $sTable . '.csv'));
		}

		$oTpl = $this->getView('index');
		$oTpl->msg = $msg;
		$oTpl->detail = $detail;
		return $oTpl;
	}

	public function generate($sTable, $tField) {
		$ret = "\n";
		$sep = ';';

		$sFile = '1' . $ret;
		$sFile .= 'id' . $sep;

		foreach ($tField as $sField) {
			if (trim($sField) == '')
				continue;
			$sFile .= trim($sField) . $sep;
		}
		$sFile .= $ret;

		$this->projectSaveFile($sFile, 'data/csv/base/' . $sTable . '.csv');
	}

}
