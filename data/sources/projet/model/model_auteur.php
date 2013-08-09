<?php
class model_auteur extends abstract_model{
	
	protected $sClassRow='row_auteur';
	
	protected $sTable='auteur';
	protected $sConfig='csvExple';
	
	protected $tId=array('id');
	
	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}
	
	public function findById($tId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE id=?',$tId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY id ASC' );
	}
	
	public function getSelect(){
		$tAuteur=$this->findAll();
		$tSelect=array();
		foreach($tAuteur as $oAuteur){
			$tSelect[$oAuteur->id]=$oAuteur->nom.' '.$oAuteur->prenom;
		}
		return $tSelect;
	}
	
}

class row_auteur extends abstract_row{
	
	protected $sClassModel='model_auteur';
	
	public function findListArticle(){
		return model_article::getInstance()->findManyByAuteur($this->id);
	}
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
