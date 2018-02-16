<?php

namespace Model;

class Account extends \abstract_model{
	
	protected $sClassRow='Row\Account';
	
	protected $sTable='account';
	protected $sConfig='csvExple';
	
	protected $tId=array('id');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE id=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable);
	}
	
	public function getListAccount(){
	
		$tAccount=$this->findAll();
		
		$tLoginPassAccount=array();
		
		foreach($tAccount as $oAccount){
			$tLoginPassAccount[$oAccount->login][$oAccount->pass]=$oAccount;
		}
	
		return $tLoginPassAccount;
	}
}