<?php
class VARmodel_rightManagerENDVAR extends abstract_model implements VARinterfaceModelRightmanagerENDVAR{

	protected $sClassRow='VARrow_rightManagerENDVAR';

	protected $sTable='VARtablePermissionsENDVAR';
	protected $sConfig='VARprofilENDVAR';

	protected $tId=array('VARpermission_idENDVAR');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE VARpermission_idENDVAR=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT
			VARtableActionsENDVAR.VARaction_nameENDVAR as actionName,
			VARtableItemsENDVAR.VARitem_nameENDVAR as itemName,
			VARtableGroupsENDVAR.VARgroup_nameENDVAR as groupName,
			VARtablePermissionsENDVAR.VARpermission_idENDVAR

		FROM VARtablePermissionsENDVAR
			INNER JOIN VARtableActionsENDVAR
				ON VARtableActionsENDVAR.VARaction_idENDVAR=VARtablePermissionsENDVAR.VARpermission_fk_action_idENDVAR
			INNER JOIN VARtableItemsENDVAR
				ON VARtableItemsENDVAR.VARitem_idENDVAR=VARtablePermissionsENDVAR.VARpermission_fk_item_idENDVAR
			INNER JOIN VARtableGroupsENDVAR
				ON VARtableGroupsENDVAR.VARgroup_idENDVAR=VARtablePermissionsENDVAR.VARpermission_fk_group_idENDVAR');
	}

	public function findListByGroup($group_id){
		return $this->findManySimple('SELECT
			VARtableActionsENDVAR.VARaction_nameENDVAR as actionName,
			VARtableItemsENDVAR.VARitem_nameENDVAR as itemName
		FROM VARtablePermissionsENDVAR
			INNER JOIN VARtableActionsENDVAR
				ON VARtableActionsENDVAR.VARaction_idENDVAR=VARtablePermissionsENDVAR.VARpermission_fk_action_idENDVAR
			INNER JOIN VARtableItemsENDVAR
				ON VARtableItemsENDVAR.VARitem_idENDVAR=VARtablePermissionsENDVAR.VARpermission_fk_item_idENDVAR
		WHERE VARtablePermissionsENDVAR.VARpermission_fk_group_idENDVAR=?',$group_id);
	}

	public function insertGroup($sName){
		$this->execute('INSERT INTO VARtableGroupsENDVAR (VARgroup_nameENDVAR) VALUES(?)',$sName);
	}
	public function insertAction($sName){
		$this->execute('INSERT INTO VARtableActionsENDVAR (VARaction_nameENDVAR) VALUES(?)',$sName);
	}
	public function insertItem($sName){
		$this->execute('INSERT INTO VARtableItemsENDVAR (VARitem_nameENDVAR) VALUES(?)',$sName);
	}

	public function findGroupByName($sName){
		return $this->findOneSimple('SELECT VARgroup_idENDVAR as id FROM VARtableGroupsENDVAR WHERE VARgroup_nameENDVAR=?',$sName);
	}
	public function findActionByName($sName){
		return $this->findOneSimple('SELECT VARaction_idENDVAR as id FROM VARtableActionsENDVAR WHERE VARaction_nameENDVAR=?',$sName);
	}
	public function findItemByName($sName){
		return $this->findOneSimple('SELECT VARitem_idENDVAR as id FROM VARtableItemsENDVAR WHERE VARitem_nameENDVAR=?',$sName);
	}

	public function findSelectGroup(){
		$tItem=$this->findManySimple('SELECT VARgroup_idENDVAR,VARgroup_nameENDVAR FROM VARtableGroupsENDVAR');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->VARgroup_idENDVAR ]=$oItem->VARgroup_nameENDVAR;
			}
		}
		return $tSelect;
	}
	public function findSelectAction(){
		$tItem=$this->findManySimple('SELECT VARaction_idENDVAR,VARaction_nameENDVAR FROM VARtableActionsENDVAR');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->VARaction_idENDVAR ]=$oItem->VARaction_nameENDVAR;
			}
		}
		return $tSelect;
	}
	public function findSelectItem(){
		$tItem=$this->findManySimple('SELECT VARitem_idENDVAR,VARitem_nameENDVAR FROM VARtableItemsENDVAR');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->VARitem_idENDVAR ]=$oItem->VARitem_nameENDVAR;
			}
		}
		return $tSelect;
	}

	public function findListUser(){
		return $this->findManySimple('SELECT VARuser_idENDVAR,VARuser_loginENDVAR,VARuser_fk_group_idENDVAR FROM VARtableUsersENDVAR');
	}
	public function findUserById($user_id){
		return $this->findOneSimple('SELECT VARuser_idENDVAR,VARuser_loginENDVAR,VARuser_fk_group_idENDVAR FROM VARtableUsersENDVAR WHERE VARuser_idENDVAR=?',$user_id);
	}
	public function updateUserGroup($user_id,$group_id){
		$this->execute('UPDATE VARtableUsersENDVAR SET VARuser_fk_group_idENDVAR=? WHERE VARuser_idENDVAR=?',$group_id,$user_id);
	}

}
class VARrow_rightManagerENDVAR extends abstract_row{
	protected $sClassModel = 'VARmodel_rightManagerENDVAR';

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

}
