<?php

namespace Model;

class Article extends \abstract_model{
	
	protected $sClassRow='Row\Article';
	
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