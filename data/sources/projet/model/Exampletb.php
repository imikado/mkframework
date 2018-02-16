<?php

namespace Model;

class Exampletb extends \abstract_model{

	protected $sClassRow='Row\Eampletb';

	protected $sTable='exampletb';
	protected $sConfig='exampleconfig';

	protected $tId=array('exampleid');

	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function findById($uId){
		return $this->findOne('SELECT * FROM '.$this->sTable.' WHERE exampleid=?',$uId );
	}
	public function findAll(){
		return $this->findMany('SELECT * FROM '.$this->sTable);
	}

	//ICI
	//sSaveDuplicateKey
}
