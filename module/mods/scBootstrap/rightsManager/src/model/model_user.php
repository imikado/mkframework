<?php

class VARmodel_userENDVAR extends abstract_model implements VARinterfaceModelENDVAR {

	protected $sClassRow = 'VARrow_userENDVAR';
	protected $sTable = 'VARtableUsersENDVAR';
	protected $sConfig = 'VARprofilENDVAR';
	protected $tId = array('VARuser_idENDVAR');

	public static function getInstance() {
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId) {
		return $this->findOne('SELECT * FROM ' . $this->sTable . ' WHERE VARuser_idENDVAR=?', $uId);
	}

	public function findAll() {
		return $this->findMany('SELECT * FROM ' . $this->sTable);
	}

	public function getSelect() {
		$tab = $this->findAll();
		$tSelect = array();
		if ($tab) {
			foreach ($tab as $oRow) {
				$tSelect[$oRow->VARuser_idENDVAR] = $oRow->VARuser_loginENDVAR;
			}
		}
		return $tSelect;
	}

	public function getListAccount() {

		$tAccount = $this->findAll();

		$tLoginPassAccount = array();

		if ($tAccount) {
			foreach ($tAccount as $oAccount) {
				//on cree ici un tableau indexe par nom d'utilisateur et mot de pase
				$tLoginPassAccount[$oAccount->VARuser_loginENDVAR][$oAccount->VARuser_passwordENDVAR] = $oAccount;
			}
		}

		return $tLoginPassAccount;
	}

	public function hashPassword($sPassword) {
		//utiliser ici la methode de votre choix pour hasher votre mot de passe
		return sha1('mySaltMkGed' . $sPassword);
	}

}

class VARrow_userENDVAR extends abstract_row {

	protected $sClassModel = 'VARmodel_userENDVAR';

	/* exemple jointure
	  public function findAuteur(){
	  return model_auteur::getInstance()->findById($this->auteur_id);
	  }
	 */
	/* exemple test validation */

	private function getCheck() {
		$oPluginValid = new plugin_valid($this->getTab());


		/* renseigner vos check ici
		  $oPluginValid->isEqual('champ','valeurB','Le champ n\est pas &eacute;gal &agrave; '.$valeurB);
		  $oPluginValid->isNotEqual('champ','valeurB','Le champ est &eacute;gal &agrave; '.$valeurB);
		  $oPluginValid->isUpperThan('champ','valeurB','Le champ n\est pas sup&eacute; &agrave; '.$valeurB);
		  $oPluginValid->isUpperOrEqualThan('champ','valeurB','Le champ n\est pas sup&eacute; ou &eacute;gal &agrave; '.$valeurB);
		  $oPluginValid->isLowerThan('champ','valeurB','Le champ n\est pas inf&eacute;rieur &agrave; '.$valeurB);
		  $oPluginValid->isLowerOrEqualThan('champ','valeurB','Le champ n\est pas inf&eacute;rieur ou &eacute;gal &agrave; '.$valeurB);
		  $oPluginValid->isEmpty('champ','Le champ n\'est pas vide');
		  $oPluginValid->isNotEmpty('champ','Le champ ne doit pas &ecirc;tre vide');
		  $oPluginValid->isEmailValid('champ','L\email est invalide');
		  $oPluginValid->matchExpression('champ','/[0-9]/','Le champ n\'est pas au bon format');
		  $oPluginValid->notMatchExpression('champ','/[a-zA-Z]/','Le champ ne doit pas &ecirc;tre a ce format');
		 */

		return $oPluginValid;
	}

	public function isValid() {
		return $this->getCheck()->isValid();
	}

	public function getListError() {
		return $this->getCheck()->getListError();
	}

	public function save() {
		throw new Exception('disabled for pro template, please use VARmodel_tableENDVAR::getInstance()->update($object) or VARmodel_tableENDVAR::getInstance()->insert($object) instead');
	}

}
