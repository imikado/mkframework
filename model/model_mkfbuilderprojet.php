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

class model_mkfbuilderprojet extends abstract_model {

	private $sSource = 'data/sources/projet/';
	private $sSourceEmpty = 'data/sources/projet_vide/';
	private $sSourceScBootstrap = 'data/sources/projet_vide_sc_bootstrap/';
	private $sGenere = null;

	public function __construct() {
		$this->sGenere = _root::getConfigVar('path.generation');
	}

	public static function getInstance() {
		return self::_getInstance(__CLASS__);
	}

	public function create($sProjet) {
		self::copyFromTo($this->sSource, $this->sGenere . $sProjet);
	}

	public function createEmpty($sProjet) {
		self::copyFromTo($this->sSourceEmpty, $this->sGenere . $sProjet);
	}

	public function createScWithBootstrap($sProjet) {
		self::copyFromTo($this->sSourceScBootstrap, $this->sGenere . $sProjet);
	}

	public function findAll() {
		$oDir = new _dir($this->sGenere);
		$tProjet = array();
		foreach ($oDir->getListDir() as $oDir) {
			$tProjet[] = $oDir->getName();
		}
		return $tProjet;
	}

	public function copyFromTo($sFrom, $sTo) {
		if (preg_match('/test$/', $sFrom) or preg_match('/script/', $sFrom)) {
			return;
		}

		if (is_dir($sFrom)) {

			$oDir = new _dir($sFrom);
			try {
				mkdir($sTo);
			} catch (Exception $e) {
				throw new Exception(
				'Erreur creation repertoire ' . $sTo . '
				Verifier les droits du repertoire ' . $this->sGenere . ' du mkf4builder
				On doit pouvoir ecrire dedans (generation de projet)
				'
				);
			}
			chmod($sTo, 0777);

			foreach ($oDir->getList() as $oFile) {
				if (preg_match('/example/', $oFile->getAdresse())) {
					continue;
				}
				self::copyFromTo($oFile->getAdresse(), $sTo . '/' . $oFile->getName());
			}
		} else {
			copy($sFrom, $sTo);
			chmod($sTo, 0666);
		}
	}

}

?>
