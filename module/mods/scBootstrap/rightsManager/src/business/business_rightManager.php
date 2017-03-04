<?php
class business_VARmoduleRightsManagerENDVAR extends business_abstract {

	protected $_oModel;
	protected $_oACL;
	protected $_oI18n;
	protected $_oValid;
	protected $_tColumn = array('VARpermission_fk_group_idENDVAR','VARpermission_fk_action_idENDVAR','VARpermission_fk_item_idENDVAR');

	public function __construct(VARinterfaceModelRightmanagerENDVAR $oModel_, VARinterfaceACLENDVAR $oACL_, interface_i18n $oI18n_, interface_valid $oValid_) {
		$this->_oModel = $oModel_;
		$this->_oACL = $oACL_;
		$this->_oI18n=$oI18n_;
		$this->_oValid=$oValid_;
	}

	public function tr($sTag_){
		return $this->_oI18n->tr($sTag_);
	}

	public function allowList($tPermmission_){
		if($tPermmission_){
			foreach($tPermmission_ as $oPermission){
				$this->_oACL->allow($oPermission->actionName,$oPermission->itemName);
			}
		}
	}

	public function loadForUser($oAccount_){
		//on purge
		$this->_oACL->purge();

		$tPermission=$this->_oModel->findListByGroup($oAccount_->VARuser_fk_group_idENDVAR);
		$this->allowList($tPermission);
	}

	public function getCheck($tParam_) {
		$this->_oValid->load($tParam_);

		foreach ($this->_tColumn as $sColumn) {
			$this->_oValid->isNotEmpty($sColumn, $this->tr('errorIsEmpty'));
		}
		return $this->_oValid;
	}

	public function getGroupByName($sGroupText){
		$oGroup=$this->_oModel->findGroupByName($sGroupText);
		if($oGroup){
			return $oGroup;
		}else{
			$this->_oModel->insertGroup($sGroupText);
			return $this->_oModel->findGroupByName($sGroupText);
		}
	}
	public function getActionByName($sActionText){
		$oAction=$this->_oModel->findActionByName($sActionText);
		if($oAction){
			return $oAction;
		}else{
			$this->_oModel->insertAction($sActionText);
			return $this->_oModel->findActionByName($sActionText);
		}
	}
	public function getItemByName($sItemText){
		$oItem=$this->_oModel->findItemByName($sItemText);
		if($oItem){
			return $oItem;
		}else{
			$this->_oModel->insertItem($sItemText);
			return $this->_oModel->findItemByName($sItemText);
		}
	}
	public function updateParamWithJoin($tParam_){
		$sGroupText=trim($tParam_['VARpermission_fk_group_idENDVAR'] );
		$sActionText=trim($tParam_['VARpermission_fk_action_idENDVAR'] );
		$sItemText=trim($tParam_['VARpermission_fk_item_idENDVAR'] );

		$tParam=$tParam_;

		if($sGroupText!=''){
			$oGroup=$this->getGroupByName($sGroupText);
			$tParam['VARpermission_fk_group_idENDVAR']=$oGroup->id;
		}
		if($sActionText!=''){
			$oAction=$this->getActionByName($sGroupText);
			$tParam['VARpermission_fk_action_idENDVAR']=$oAction->id;
		}
		if($sItemText!=''){
			$oItem=$this->getItemByName($sItemText);
			$tParam['VARpermission_fk_item_idENDVAR']=$oItem->id;
		}

		return $tParam;
	}

	public function updatePermission($id_,$tParam_){
		$oValid = $this->getCheck($tParam_);
		if (!$oValid->isValid()) {
			return $this->sendReturn(false, array('tError' => $oValid->getListError()));
		}

		$oPermission=$this->_oModel->findById($id_);
		foreach ($this->_tColumn as $sColummn) {
			$oPermission->$sColummn = $tParam_[$sColummn];
		}

		$this->_oModel->update($oPermission);

		return true;
	}

	public function insertPermission($oPermission_,$tParam_){

		$tParam=$this->updateParamWithJoin($tParam_);

		$oValid = $this->getCheck($tParam);
		if (!$oValid->isValid()) {
			return $this->sendReturn(false, array('tError' => $oValid->getListError()));
		}

		foreach ($this->_tColumn as $sColummn) {
			$oPermission->$sColummn = $tParam[$sColummn];
		}

		$this->_oModel->insert($oPermission);
	}

	public function deletePermission($id_){
		$oPermission=$this->oModel_->findById($id_);
		if($oPermission){
			$oPermission->delete();
		}else{
			return $this->sendReturn(false,array('tError'=>array('message'=>$this->_oI18n->tr('permissionDoesNotExists'))) );
		}


	}

}
