<?php

class module_mods_scBootstrap_sgbdSqlite extends abstract_moduleBuilder {

	protected $sModule = 'mods_scBootstrap_sgbdSqlite';
	protected $sModuleView = 'mods/scBootstrap/sgbdSqlite';

	public function _index() {
		module_builder::getTools()->rootAddConf('conf/connexion.ini.php');

		$tConnexion = _root::getConfigVar('db');

		$tSqlite = array();
		foreach ($tConnexion as $sConfig => $val) {
			if (substr($val, 0, 6) == 'sqlite') {
				$tSqlite[substr($sConfig, 0, -4)] = $val;
			}
		}


		$msg = '';
		$detail = '';
		if ($this->isPost()) {

			$sDbFilename = _root::getParam('sDbFilename');

			$sTable = _root::getParam('sTable');
			$tField = _root::getParam('tField');
			$tType = _root::getParam('tType');
			$tSize = _root::getParam('tSize');

			try {
				$oDb = new PDO($sDbFilename);
			} catch (PDOException $exception) {
				die($exception->getMessage());
			}
			$oDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sSql = 'CREATE TABLE IF NOT EXISTS ' . $sTable . '(';
			$sSql .= 'id  INTEGER PRIMARY KEY AUTOINCREMENT';
			foreach ($tField as $i => $sField) {
				$sSql .= ',';
				$sSql .= $sField . ' ' . $tType[$i];
				if ($tType[$i] == 'VARCHAR') {
					$sSql .= '(' . $tSize[$i] . ')';
				}
			}
			$sSql .= ')';


			try {
				$oDb->exec($sSql);
			} catch (PDOException $exception) {
				die($exception->getMessage());
			}

			$msg = trR('baseTableGenereAvecSucces', array('#maTable#' => $sTable, '#listField#' => implode(',', $tField)));
			$detail = trR('creationFichier', array('#FICHIER#' => ' sqlite ' . $sDbFilename));
		}

		$oTpl = $this->getView('index');
		$oTpl->msg = $msg;
		$oTpl->detail = $detail;
		$oTpl->tSqlite = $tSqlite;
		return $oTpl;
	}

}
