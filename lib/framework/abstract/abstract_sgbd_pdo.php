<?php
/*
This file is part of Mkframework.

Mkframework is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License.

Mkframework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

*/
/**
* classe abstract_sgbd
* @author Mika
* @link http://mkf.mkdevs.com/
*/
abstract class abstract_sgbd_pdo{

	protected $_tConfig;
	protected $_sConfig;
	protected $_sClassRow;
	protected $_pDb;
	protected $_sReq;
	protected $_tId;
	
	private static $_tInstance=array();
	
	/**
	* @access public
	* @return une object
	*/
	protected static function _getInstance($class,$sConfig) {
		if ( !isset(self::$_tInstance[$class][$sConfig]) ){
			$oSgbd = new $class();
			$oSgbd->chooseConfig($sConfig);
			self::$_tInstance[$class][$sConfig]=$oSgbd;
			
		}
		return self::$_tInstance[$class][$sConfig];
	}
	
	/**
	* force la classe row
	* @access public
	* @param string $sClassRow
	*/
	public function setClassRow($sClassRow){
		$this->_sClassRow=$sClassRow;
	}
	/**
	* choisit le profile de connection
	* @param string $sConfig
	*/
	public function chooseConfig($sConfig){
		$this->_sConfig=$sConfig;
	}
	/**
	* definir le tableau de connection
	* @param array $tConfig
	*/
	public function setConfig($tConfig){
		$this->_tConfig=$tConfig;
	}
	/**
	* retourne la requete
	*/
	public function getRequete(){
		return $this->_sReq;
	}
	
	public function getInsertFromTab($tProperty){
		
		$sCols='';
		$sVals='';
		
		if($tProperty){
			$tKey=array_keys($tProperty);
			foreach($tKey as $sVar){
				$sCols.=$sVar.',';
				$sVals.='?,';
			}
		}
		return '('.substr($sCols,0,-1).') VALUES ('.substr($sVals,0,-1).') ';
	}
	public function getUpdateFromTab($tProperty){
		$sReq='';
		if($tProperty){
			$tKey=array_keys($tProperty);
			foreach($tKey as $sVar){
				$sReq.=$sVar.'=?,';
			}
		}
		return substr($sReq,0,-1);
	}
	public function setId($tId){
		$this->_tId=$tId;
	}
	public function getWhereFromTab($tId){
		$sWhere='';
		if(is_array($tId)){
			$tKeyId=array_keys($tId);
			foreach($tKeyId as $sVar){
				if($sWhere!=''){ $sWhere.=' AND '; }
				$sWhere.=$sVar.'=?';
			}
		}
		
		return $sWhere;
	}


	private function getRequestAndParam($tReq){
		$sReq=null;
		$tParam=null;
		if(is_array($tReq)){
			$sReq=$tReq[0];
			if(isset($tReq[1]) and is_array($tReq[1])){
				$tParam=$tReq[1];
			}else{
				unset($tReq[0]);
				$tParam=array_values($tReq);
			}
		}else{
			$sReq=$tReq;
		}

		return array($sReq,$tParam);
	}
	
	public function findManySimple($tSql,$sClassRow){
		list($sReq,$tParam)=$this->getRequestAndParam($tSql);

		$pRs=$this->query($sReq,$tParam);
		
		if(!$pRs){
			return null;
		}
		
		return $pRs->fetchAll(PDO::FETCH_OBJ);
	}
	public function findMany($tSql,$sClassRow){
		list($sReq,$tParam)=$this->getRequestAndParam($tSql);

		$pRs=$this->query($sReq,$tParam);
		
		if(!$pRs){
			return null;
		}
			
		$tObj=array();
		while($tRow=$pRs->fetch(PDO::FETCH_ASSOC)){
			$oRow=new $sClassRow($tRow);
			$tObj[]=$oRow;
		}
		return $tObj;
	}
	public function findOne($tSql,$sClassRow){
		list($sReq,$tParam)=$this->getRequestAndParam($tSql);

		$pRs=$this->query($sReq,$tParam);

		$tRow=$pRs->fetch(PDO::FETCH_ASSOC);
		
		if(empty($tRow) ){
			return null;
		}
			
		$oRow=new $sClassRow($tRow);
		
		return $oRow;
	}
	public function findOneSimple($tSql,$sClassRow){
		list($sReq,$tParam)=$this->getRequestAndParam($tSql);

		$pRs=$this->query($sReq,$tParam);

		$oRow=$pRs->fetch(PDO::FETCH_OBJ);
		
		if(empty($oRow) ){
			return null;
		}
		
		return $oRow;
	}
	public function execute($tSql){
		list($sReq,$tParam)=$this->getRequestAndParam($tSql);

		return $this->query($sReq,$tParam);
	}
	
	public function update($sTable,$tProperty,$tWhere){

		$sReq='UPDATE '.$sTable.' SET '.$this->getUpdateFromTab($tProperty).' WHERE '.$this->getWhereFromTab($tWhere);

		$tPropertyAndWhere=array_merge($tProperty,$tWhere);
		$tParam=array_values($tPropertyAndWhere);

		$this->query($sReq,$tParam);
	}
	public function insert($sTable,$tProperty){
		$sReq='INSERT INTO '.$sTable.' '.$this->getInsertFromTab($tProperty);
		$tParam=array_values($tProperty);

		$this->query($sReq,$tParam);
		
		return $this->getLastInsertId();
	}

	public function delete($sTable,$tWhere){

		$sReq='DELETE FROM '.$sTable.' WHERE '.$this->getWhereFromTab($tWhere);
		$tParam=array_values($tWhere);

		$this->query($sReq,$tParam);
	}

	public function getPdo(){
		$this->connect();
		return $this->_pDb;
	}
	
	protected function query($sReq,$tParam=null){
		if($tParam==null){
			$tParam=array();
		}
		
		$tATTRERRMODE=array(
			'SILENT' => PDO::ERRMODE_SILENT,
			'WARNING' => PDO::ERRMODE_WARNING,
			'EXCEPTION' => PDO::ERRMODE_EXCEPTION,
		);
		$tATTRCASE=array(
			'LOWER' => PDO::CASE_LOWER,
			'NATURAL' => PDO::CASE_NATURAL,
			'UPPER' => PDO::CASE_UPPER,
		);
		
		
		$this->connect();
		$this->_sReq=$sReq.' [ '.implode(' | ',$tParam).' ]';
		$this->_pDb->setAttribute(PDO::ATTR_ERRMODE, $tATTRERRMODE[ trim(_root::getConfigVar('pdo.ATTR_ERRMODE','WARNING')) ] ); 
		$this->_pDb->setAttribute(PDO::ATTR_CASE, $tATTRCASE[ trim(_root::getConfigVar('pdo.ATTR_CASE','NATURAL')) ] ); 
		$sth = $this->_pDb->prepare($sReq);
		if(is_array($tParam)){
			$sth->execute($tParam);
		}else{
			$sth->execute();
		}
		return $sth;
	}

	public function erreur($sErreur){
		throw new Exception($sErreur);
	}
	
}
