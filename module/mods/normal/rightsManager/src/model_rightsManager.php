<?php
class #model_examplemodel# extends abstract_model{
	
	protected $sClassRow='#row_examplemodel#';
	
	protected $sTable='#exampleTable#';
	protected $sConfig='#exampleConfig#';
	
	protected $tId=array('#examplePermission_id#');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE #examplePermission_id#=?',$uId );
	}
	public function findAll(){
		return $this->findMany('#exampleRequeteFindAll#');
	}
	
	public function findListByGroup($group_id){
		return $this->findManySimple('#exampleRequeteFindListByGroup#',$group_id);
	}
	
	public function insertGroup($sName){
		$this->execute('INSERT INTO #exampleGroupTable# (#exampleGroup_name#) VALUES(?)',$sName);
	}
	public function insertAction($sName){
		$this->execute('INSERT INTO #exampleActionTable# (#exampleAction_name#) VALUES(?)',$sName);
	}
	public function insertItem($sName){
		$this->execute('INSERT INTO #exampleItemTable# (#exampleItem_name#) VALUES(?)',$sName);
	}
	
	public function findGroupByName($sName){
		return $this->findOneSimple('SELECT #exampleGroup_id# as id FROM #exampleGroupTable# WHERE #exampleGroup_name#=?',$sName);
	}
	public function findActionByName($sName){
		return $this->findOneSimple('SELECT #exampleAction_id# as id FROM #exampleActionTable# WHERE #exampleAction_name#=?',$sName);
	}
	public function findItemByName($sName){
		return $this->findOneSimple('SELECT #exampleItem_id# as id FROM #exampleItemTable# WHERE #exampleItem_name#=?',$sName);
	}
	
	public function findSelectGroup(){
		$tItem=$this->findManySimple('SELECT #exampleGroup_id#,#exampleGroup_name# FROM #exampleGroupTable#');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->#exampleGroup_id# ]=$oItem->#exampleGroup_name#;
			}
		}
		return $tSelect;
	}
	public function findSelectAction(){
		$tItem=$this->findManySimple('SELECT #exampleAction_id#,#exampleAction_name# FROM #exampleActionTable#');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->#exampleAction_id# ]=$oItem->#exampleAction_name#;
			}
		}
		return $tSelect;
	}
	public function findSelectItem(){
		$tItem=$this->findManySimple('SELECT #exampleItem_id#,#exampleItem_name# FROM #exampleItemTable#');
		$tSelect=array();
		if($tItem){
			foreach($tItem as $oItem){
				$tSelect[ $oItem->#exampleItem_id# ]=$oItem->#exampleItem_name#;
			}
		}
		return $tSelect;
	}
	
	public function findListUser(){
		return $this->findManySimple('SELECT #exampleUser_id#,#exampleUser_login#,#exampleUser_groupsId# FROM #exampleUserTable#');
	}
	public function findUserById($user_id){
		return $this->findOneSimple('SELECT #exampleUser_id#,#exampleUser_login#,#exampleUser_groupsId# FROM #exampleUserTable# WHERE #exampleUser_id#=?',$user_id);
	}
	public function updateUserGroup($user_id,$group_id){
		$this->execute('UPDATE #exampleUserTable# SET #exampleUser_groupsId#=? WHERE #exampleUser_id#=?',$group_id,$user_id);
	}
	
	public function loadForUser($oUser){
		//on purge
		_root::getACL()->purge();
		
		$tPermission=$this->findListByGroup($oUser->#exampleUserGroups_id#);
		if($tPermission){
			foreach($tPermission as $oPermission){
				_root::getACL()->allow($oPermission->actionName,$oPermission->itemName);
			}
		}
	}
	
}
class #row_examplemodel# extends abstract_row{
	
	protected $sClassModel='#model_examplemodel#';
	
	/*exemple jointure 
	public function findAuteur(){
		return model_auteur::getInstance()->findById($this->auteur_id);
	}
	*/
	/*exemple test validation*/
	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());
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

	public function isValid(){
		return $this->getCheck()->isValid();
	}
	public function getListError(){
		return $this->getCheck()->getListError();
	}
	public function save(){
		if(!$this->isValid()){
			return false;
		}
		parent::save();
		return true;
	}

}
