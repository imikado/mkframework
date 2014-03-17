<?php
class model_article extends abstract_model{
	
	protected $sClassRow='row_article';
	
	protected $sTable='article';
	protected $sConfig='xmlExple';
	
	protected $tId=array('id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY id ASC');
	}
	public function findAllOrderBy($sField,$sSide){
		if($sSide=='asc'){
			$side='ASC';
		}else{
			$side='DESC';
		}
		
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY ? '.$side,$sField);
	}
	
	public function findAllOrderByPriority(){
		return $this->findMany('SELECT * FROM '.$this->sTable.' ORDER BY priority DESC');
	}
	
	public function findManyByAuteur($auteur_id){
		return $this->findMany('SELECT * FROM '.$this->sTable.' WHERE auteur_id=?',$auteur_id);
	}
	/*
	public function findById($id){
		$smt=$this->getSgbd()->getPdo()->prepare('SELECT * FROM article WHERE id=:id');
		$smt->bindValue(':id',$id);
		$smt->execute();
		$r=$smt->fetch();
		return $r;
	}
	*/
}

class row_article extends abstract_row{
	
	protected $sClassModel='model_article';
	
	public function findAuteur(){
		return model_auteur::getInstance()->findById($this->auteur_id);
	}
	
	/*exemple test validation*/
	private function getCheck(){
		$oPluginValid=new plugin_valid($this->getTab());
		$oPluginValid->isNotEmpty('titre','Le titre doit &ecirc;tre saisi');
		$oPluginValid->matchExpression('auteur_id','/[0-9]/','Vous devez indiquer un auteur');
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
