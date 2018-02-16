<?php

namespace Model;

class Auteur extends \abstract_model{
	
	protected $sClassRow='Row\Auteur';
	
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