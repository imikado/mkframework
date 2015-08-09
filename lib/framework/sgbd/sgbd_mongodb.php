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
class sgbd_mongodb extends abstract_sgbd{
		
	public static function getInstance($sConfig){
		return self::_getInstance(__CLASS__,$sConfig);
	}
	
	public function getDb(){
		$this->connect();
		return $this->_pDb;
	}
	
	public function findMany($tSql,$sClassRow){
            
		$oCursor=$this->query($tSql);
		
		if(empty($oCursor)){
			return null;
		}
		
		$tObj=array();
		foreach($oCursor as $oDoc){
			$oRow=new $sClassRow($oDoc);
			$tObj[]=$oRow;
		}
		return $tObj;
	}
	public function findManySimple($tSql,$sClassRow){
		$oCursor=$this->query($tSql);
		
		if(empty($oCursor)){
			return null;
		}
				
		return $oCursor;
	}
	public function findOne($tSql,$sClassRow){
		$oDoc=$this->queryOne($tSql);
		
		if(empty($oDoc)){
			return null;
		}
		
		$oRow=new $sClassRow($oDoc);
		
		return $oRow;
		
	}
	public function findOneSimple($tSql,$sClassRow){
		$oDoc=$this->queryOne($tSql);
		
		if(empty($oDoc)){
			return null;
		}
				
		return $oDoc;
	}
	public function execute($tSql){
		$this->connect();
		$this->_pDb->execute($tSql);
	}
	
	public function update($sCollection,$tProperty,$twhere){
		$this->connect();
		$oCollection = new MongoCollection($this->_pDb, $sCollection);
		
		$oCollection->save(array_merge($tProperty,$twhere));
	}
	public function insert($sCollection,$tProperty){
		$this->connect();
		$oCollection = new MongoCollection($this->_pDb, $sCollection);
		
		$oCollection->insert($tProperty);
	}
	
	public function delete($sCollection,$twhere){
		$this->connect();
		$oCollection = new MongoCollection($this->_pDb, $sCollection);
		$oCollection->remove($twhere);
	}
	
	public function getListColumn($sTable){
		return array('_id');
	}
	public function getListTable(){
		$this->connect();
		$tTable= $this->_pDb->getCollectionNames();
		
		return $tTable;
	}
	
	private function connect(){
		if(empty($this->_pDb)){
                    
			try{
				if(isset($this->_tConfig[$this->_sConfig.'.dsn']) and isset($this->_tConfig[$this->_sConfig.'.options']) and isset($this->_tConfig[$this->_sConfig.'.driverOptions']) ){
					$oMongoDb = new MongoClient($this->_tConfig[$this->_sConfig.'.dsn'],$this->_tConfig[$this->_sConfig.'.options'],$this->_tConfig[$this->_sConfig.'.driverOptions']); 
				}else if(isset($this->_tConfig[$this->_sConfig.'.dsn']) and isset($this->_tConfig[$this->_sConfig.'.options']) ){
					$oMongoDb = new MongoClient($this->_tConfig[$this->_sConfig.'.dsn'],$this->_tConfig[$this->_sConfig.'.options']);
				}else if(isset($this->_tConfig[$this->_sConfig.'.dsn']) ){
					$oMongoDb = new MongoClient($this->_tConfig[$this->_sConfig.'.dsn']);
				}
                            
				$this->_pDb= $oMongoDb->selectDB($this->_tConfig[$this->_sConfig.'.database']);
                
                if($this->_pDb==null){
					throw new Exception($oMongoDb->lastError());
				}
                        
             }catch(Exception $e ) {
				throw new Exception('Probleme de connexion mongoDb , message '.$e->getMessage());
             }	
		}
	}
	public function getLastInsertId(){
		return null;
	}
	
	private function query($tReq){
		$this->connect();
		$this->sReq='mongoDb:'.print_r($tReq,1);
		
		$sCollection=$tReq[0];
		$oCollection = new MongoCollection($this->_pDb, $sCollection);

		$nb=count($tReq);
		if($nb==4){
			return $oCollection->find($tReq[1],$tReq[2],$tReq[3]);
		}else if($nb==3){
			return $oCollection->find($tReq[1],$tReq[2]);
		}else if($nb==2){
			return $oCollection->find($tReq[1]);
		}else if($nb==1){
			return $oCollection->find();
		}
		
		throw new Exception('Vous devez fournir au moins la collection en parametre');
		
	}
	private function queryOne($tReq){
		$this->connect();
		$this->sReq='mongoDb:'.print_r($tReq,1);
		
		$sCollection=$tReq[0];
		$oCollection = new MongoCollection($this->_pDb, $sCollection);

		$nb=count($tReq);
		if($nb==4){
			return $oCollection->findOne($tReq[1],$tReq[2],$tReq[3]);
		}else if($nb==3){
			return $oCollection->findOne($tReq[1],$tReq[2]);
		}else if($nb==2){
			return $oCollection->findOne($tReq[1]);
		}else if($nb==1){
			return $oCollection->findOne();
		}
		
		throw new Exception('Vous devez fournir au moins la collection en parametre');
		
	}
	public function quote($sVal){
		return $sVal;
	}
	public function getWhereAll(){
		return '1=1';
	}

	
}
