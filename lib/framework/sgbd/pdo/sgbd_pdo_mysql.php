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
class sgbd_pdo_mysql extends abstract_sgbd_pdo{
	
	public static function getInstance($sConfig){
		return self::_getInstance(__CLASS__,$sConfig);
	}
	
	public function getListColumn($sTable){
		$pRs=$this->query(sgbd_syntax_mysql::getListColumn($sTable));
		$tObj=array();
		
		if(empty($pRs)){
			return null;
		}
		
		while($tRow=$pRs->fetch(PDO::FETCH_NUM)){
			$tObj[]=$tRow[0];
		}
		return $tObj;
	}
	public function getListTable(){
		$pRs=$this->query(sgbd_syntax_mysql::getListTable());
		
		if(empty($pRs)){
			return null;
		}
		
		$tObj=array();
		while($tRow=$pRs->fetch(PDO::FETCH_NUM)){
			$tObj[]=$tRow[0];
		}
		return $tObj;
	}
	public function getInsertFromTab($tProperty){
		
		$sCols='';
		$sVals='';
		
		if($tProperty){
			$tKey=array_keys($tProperty);
			foreach($tKey as $sVar){
				$sCols.='`'.$sVar.'`,';
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
				$sReq.='`'.$sVar.'`'.'=?,';
			}
		}
		
		return substr($sReq,0,-1);
	}
	protected function connect(){
		if(empty($this->_pDb)){
			$this->_pDb=new PDO(
						$this->_tConfig[$this->_sConfig.'.dsn'],
						$this->_tConfig[$this->_sConfig.'.username'],
						$this->_tConfig[$this->_sConfig.'.password']
			);
		}
	}
	
	public function getLastInsertId(){
		return $this->_pDb->lastInsertId();
	}
	
	public function getWhereAll(){
		return '1=1';
	}
	
}
