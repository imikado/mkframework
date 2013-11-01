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
class sgbd_json extends abstract_sgbd{
	
	public static function getInstance($sConfig){
		return self::_getInstance(__CLASS__,$sConfig);
	}
	
	public function findMany($tSql,$sClassRow){
		$tRows=$this->query($this->bind($tSql),$sClassRow);
		
		if(!$tRows){
			return null;
		}
		
		return $tRows;
	}
	public function findManySimple($tSql,$sClassRow){
		return $this->findMany($tSql,$sClassRow);
	}
	public function findOne($tSql,$sClassRow){
		$tRs=$this->query($this->bind($tSql),$sClassRow);
		
		if(empty($tRs)){
			return null;
		}
		
		return $tRs[0];
	}
	public function findOneSimple($tSql,$sClassRow){
		return $this->findOne($tSql,$sClassRow);
	}
	public function execute($tSql){
		return $this->query($this->bind($tSql));
	}
	
	public function update($sTable,$tProperty,$tWhere){
		$iId=$this->getIdFromTab($tWhere);
		
		$sFile=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/'.$iId.'.json';
		$oJson=$this->json_decode($sFile);
		$tJson=(array) $oJson;

		//remove index
		$this->removeRowFromAllIndex($sTable,$tJson);

		foreach($tProperty as $sVar => $sVal){
			$oJson->$sVar=$sVal;
		}

		//add in index
		$this->addRowInAllIndex($sTable,$tJson);
		
		$this->save($oJson,$sFile);
	}
	public function insert($sTable,$tProperty){
		$iId=$this->getMaxId($sTable);
		
		$iMax=($iId+1);
				
		$oJson=new stdclass;
		$oJson->id=$iId;
		foreach($tProperty as $sVar => $sVal){
			$oJson->$sVar=$sVal;
		}
		
		
		$sFile=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/'.$iId.'.json';
		$this->save($oJson,$sFile);
		
		$sFileMax=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/max.txt';
		file_put_contents($sFileMax,$iMax);
		
		$this->addRowInAllIndex($sTable,$tProperty);

		return $iId;
	}
	public function delete($sTable,$tWhere){
		$iId=$this->getIdFromTab($tWhere);

		$sFile=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/'.$iId.'.json';
		$oJson=$this->json_decode($sFile);
		$tJson=(array) $oJson;

		//remove index
		$this->removeRowFromAllIndex($sTable,$tJson);

		unlink($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/'.$iId.'.json');
	}

	public function getListColumn($sTable){
		
		$sFile=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/structure.csv';
		$sColumns=trim(file_get_contents($sFile));
		
		$tColumn=explode(';',$sColumns);
		
		return $tColumn;
		
	}
	public function getListTable(){
		$oDir=new _dir( $this->_tConfig[$this->_sConfig.'.database']);
		$tDir=$oDir->getList();
		$tSDir=array();
		foreach($tDir as $oDir){
			$tSDir[]= $oDir->getName();
		}
		return $tSDir;
	}
	
	private function query($sReq,$sClassRow){
		//traitement de la requete $sReq
		$sReq=trim($sReq);
		$this->_sReq=$sReq;
		
		if(substr($sReq,0,6)== 'SELECT'){
			
			$tReq=$this->explainSql($sReq);
			
			//count
			$bCount=false;
			$iCount=0;
			if(isset($tReq['select']) and preg_match('/COUNT\(/i',$tReq['select'])){
					$bCount=true;
			}
			
			$tCritere=$this->findListCritere($tReq);

			$sTable=trim($tReq['from']);
			
			//UTILISATION D UN INDEX
			$tSqlFieldEqual=array_keys($tCritere);

			$sIndexToUse=$this->findIndexForTable($sTable,$tSqlFieldEqual);

			$tObj=array();
			//UTILISATION D UN INDEX
			if($sIndexToUse!=''){
				$tObj=$this->findWithTableIndex($sClassRow,$sTable,$sIndexToUse,$tCritere);
				
			}elseif($tSqlFieldEqual==array('=id')){
				$sFilename=$this->_tConfig[$this->_sConfig.'.database'];
				$sFilename.=$sTable.'/'.(string)$tCritere['=id'].'.json';
				
				$tRow=(array)$this->json_decode($sFilename);

				$oRow=new $sClassRow($tRow);
				$tObj[]=$oRow;
			}else{
				
				$tObj=$this->findInTableWithCritere($sClassRow,$sTable,$tCritere);
				
				
			}
			//count
			if($bCount){
				$iCount=count($tObj);
				return array($iCount);
			}else if(isset($tReq['order']) and $tObj!=null){
				return $this->sortResult($tObj,$tReq);
			}else{
				return $tObj;
			}
		}
		
		
		
	}
	private function explainSql($sReq){
		if(
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)WHERE(?<where>.*)ORDER BY(?<order>.*)/i'
			,$sReq,$tResult,PREG_SET_ORDER)
			or
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)ORDER BY(?<order>.*)/i',$sReq,$tResult,PREG_SET_ORDER)
			or
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)WHERE(?<where>.*)/i',$sReq,$tResult,PREG_SET_ORDER)
			or
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)/i',$sReq,$tResult,PREG_SET_ORDER)
		){
			if(isset($tResult[0]['where']) and preg_match('/ or /i',$tResult[0]['where'])){
				$this->erreur('Requete non supportee : '.$sReq.$msg);
			}elseif(isset($tResult[0]['order']) and !preg_match('/\s[ASC|DESC]/i',trim($tResult[0]['order'])) ){
				$this->erreur('Il faut definir un sens de tri: ASC ou DESC dans la requete'.$sReq.$msg);
			}else{
				return $tResult[0];
			}
		}else{
			$msg="\n\n";
			$msg.="Le driver xml gere les requetes de type : \n";
			$msg.="- SELECT liste_des_champs FROM ma_table WHERE champ=valeur ORDER BY champ DESC/ASC \n";
			$msg.="- SELECT liste_des_champs FROM ma_table ORDER BY champ DESC/ASC \n";
			$msg.="- SELECT liste_des_champs FROM ma_table WHERE champ=valeur \n";
			$msg.="- SELECT liste_des_champs FROM ma_table  \n";
			$msg.=" la clause where accepte uniquement champ=valeur, champ!=valeur et AND \n";
			
			$this->erreur('Requete non supportee : '.$sReq.$msg);
		}
	}
	private function findListCritere($tReq){
		$tCritere=array();
		
		if(isset($tReq['where'])){
			if(preg_match('/ and /i',$tReq['where'])){
				$tWhere=preg_split('/ AND /i',$tReq['where']);
				foreach($tWhere as $sWhereVal){
					if(preg_match('/!=/',$sWhereVal)){ 
						list($sVar,$sVal)=preg_split('/!=/',$sWhereVal);
						$tCritere[trim($sVar)]='!'.trim($sVal);
					}elseif(preg_match('/=/',$sWhereVal)){
						list($sVar,$sVal)=preg_split('/=/',$sWhereVal);
						$tCritere[trim($sVar)]='='.trim($sVal);
					}
				}
			}else{
				if(preg_match('/!=/',$tReq['where'])){
					list($sVar,$sVal)=preg_split('/!=/',$tReq['where']);
					$tCritere[trim($sVar)]='!'.trim($sVal);
				}elseif(preg_match('/=/',$tReq['where'])){
					list($sVar,$sVal)=preg_split('/=/',$tReq['where']);
					$tCritere[trim($sVar)]='='.trim($sVal);
				}
			}
			
		}
		return $tCritere;
	}
	private function sortResult($tObj,$tReq){
		
		list($sChamp,$sSens)=preg_split('/ /',trim($tReq['order']));
		
		$tTri=array();
		$tIdObj=array();
		foreach($tObj as $i => $oObj){
			$tIdObj[ $i ]=$oObj;
			$tTri[ $i ]=(string)$oObj->$sChamp;
		}
		
		if($sSens=='DESC'){
			arsort($tTri);
		}else{
			asort($tTri);
		}
		
		$tOrderedObj=array();
		$tId= array_keys($tTri);
		foreach($tId as $id){
			$tOrderedObj[]=$tIdObj[$id];
		}

		return $tOrderedObj;
	}
	private function findIndexForTable($sTable,$tSqlFieldEqual){	
		$oDirIndex=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index');
		if($oDirIndex->exist()){
			$tFileIndex=$oDirIndex->getListDir();
			foreach($tFileIndex as $oFileIndex){
				$tFieldIndex=$this->getFieldsFromIndex($oFileIndex->getName());

				foreach($tSqlFieldEqual as $sSqlFieldEqual){
					if(
						$sSqlFieldEqual[0]=='=' and !in_array(substr($sSqlFieldEqual,1),$tFieldIndex) 
						or
						$sSqlFieldEqual[0]=='!' and in_array(substr($sSqlFieldEqual,1),$tFieldIndex) 
					){
						continue 2;
					}
				}
				
				return $oFileIndex->getName();
			}
		}
		return null;
	}
	private function findWithTableIndex($sClassRow,$sTable,$sIndexToUse,$tCritere){
		$sDirIndex=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndexToUse;

		$tFieldIndex=preg_split('/\./',$sIndexToUse);

		$oDirIndex=new _dir($sDirIndex);
		$tFileIndex=$oDirIndex->getListFile();
		
		$tObj=array();	
		foreach($tFileIndex as $oFileIndex){
			$sFileIndex=trim($oFileIndex->getName());
			
			$tRow=$this->getRowValueFromIndex($sFileIndex,$tFieldIndex);

			foreach($tCritere as $sCritereField => $sCritereVal){
				
				if(!isset($tRow[$sCritereField]) or 
					(
						($sCritereVal[0]=='=' and (string)$sCritereVal!=(string)'='.$tRow[$sCritereField])
						
						or
						
						($sCritereVal[0]=='!' and (string)$sCritereVal==(string)'!'.$tRow[$sCritereField])
					)
				){
					continue 2;
				}
			}
				
			$tMatchedFile=file( $sDirIndex.'/'.$sFileIndex );
			foreach($tMatchedFile as $sMatchedFile){
				$sMatchedFile=trim($sMatchedFile);
				$sFilename=$this->_tConfig[$this->_sConfig.'.database'].$sTable.'/'.$sMatchedFile;
				$tRow=(array)$this->json_decode($sFilename);
				
				$oRow=new $sClassRow($tRow);
				$tObj[]=$oRow;
			}
			
		}
		return $tObj;
	}
	private function findInTableWithCritere($sClassRow,$sTable,$tCritere){
		$oDir=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable);
		$tFile=$oDir->getListFile();

			
		$tObj=array();
		foreach($tFile as $oFile){
			if( in_array($oFile->getName(),array('structure.csv','max.txt'))){ continue; }
			$tRow=(array)$this->json_decode($oFile->getAdresse());

			
			foreach($tCritere as $sCritereField => $sCritereVal){
				
				if(!isset($tRow[$sCritereField]) or 
					(
						($sCritereVal[0]=='=' and (string)$sCritereVal!=(string)'='.$tRow[$sCritereField])
						
						or
						
						($sCritereVal[0]=='!' and (string)$sCritereVal==(string)'!'.$tRow[$sCritereField])
					)
				){
					continue 2;
				}
			}
			
			
			$oRow=new $sClassRow($tRow);
			$tObj[]=$oRow;
			
		}
			
		return $tObj;
		
		
	}
		
	public function quote($sVal){
		return $sVal;
	}
	public function getWhereAll(){
		return '1=1';
	}
	
	private function getFieldsFromIndex($sIndex){
		$tFields=preg_split('/\./',substr($sIndex,0,-6));//field.field.index
		return $tFields;
	}
	private function getRowValueFromIndex($sFileIndex,$tFieldIndex){
		$tValue=preg_split('/####/',substr($sFileIndex,0,-4) );
		$tRow=array();
		foreach($tFieldIndex as $i => $var){
			$tRow[$var]=$tValue[$i];
		}
		return $tRow;
	}
	private function getFileIndexFromTab($sIndex,$tRow){
		$tFields=$this->getFieldsFromIndex($sIndex);
		$sFileIndex='';
		foreach($tFields as $sField){
			$sFileIndex.=$tRow[$sField];
			$sFileIndex.='####';
		}
		return $sFileIndex.'.csv';
	}
	public function generateIndexForTable($sTable,$sIndex){
		$tFields=$this->getFieldsFromIndex($sIndex);

		$oDir=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable);
		$tFile=$oDir->getListFile();

		$tIndexContent=array();

		foreach($tFile as $oFile){
			if($oFile->getName() == 'structure.csv'){ continue;}
			if($oFile->getName() == 'max.txt'){ continue;}
		
			$tRow=(array)$this->json_decode($oFile->getAdresse());

			
			
			$sKey='';
			foreach($tFields as $sField){
				$sKey.=$tRow[$sField];
				$sKey.='####';
			}
			$tIndexContent[$sKey][]=$tRow['id'].'.json';
			
		}

		$oDir=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex);
		foreach($oDir->getListFile() as $oFile){
			$oFile->delete();
		}
		
		foreach($tIndexContent as $sKey => $tFile){
			$oFile=new _file($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sKey.'.csv');
			$oFile->setContent(implode($tFile,"\n"));
			$oFile->save();
		}
	}
	private function addRowInAllIndex($sTable,$tProperty){
		
		$oDir=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index');
		if($oDir->exist()){
		$tDirIndex=$oDir->getListDir();
		foreach($tDirIndex as $oDirIndex){
			$this->addRowInIndex($sTable,$tProperty,$oDirIndex->getName());
		}
		}
	}
	private function addRowInIndex($sTable,$tProperty,$sIndex){

		$sFileIndex=$this->getFileIndexFromTab($sIndex,$tProperty);

		$oFile=new _file($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$oFile->addContent($tProperty['id'].'.json');
		$oFile->save('a');
	}
	private function removeRowFromAllIndex($sTable,$tProperty){
		
		$oDir=new _dir($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index');
		if($oDir->exist()){
			$tDirIndex=$oDir->getListDir();
			foreach($tDirIndex as $oDirIndex){
				$this->removeRowFromIndex($sTable,$tProperty,$oDirIndex->getName());
			}
		}
	}
	private function removeRowFromIndex($sTable,$tProperty,$sIndex){

		$sFileIndex=$this->getFileIndexFromTab($sIndex,$tProperty);
		
		if(!file_exists($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex)){ 
			return;
		}

		$tLine=file($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$tContent=array();
		foreach($tLine as $sLine){
			$sLine=trim($sLine);
			if($sLine==$tProperty['id'].'.json'){ 
				continue;
			}
			$tContent[]=$sLine;
		}

		$oFile=new _file($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/index/'.$sIndex.'/'.$sFileIndex);
		$oFile->setContent(implode("\n",$tContent));
		$oFile->save();
	}
	
	private function getIdFromTab($tId){
		if(is_array($tId)){
			return current($tId);
		}else{
			return $tId;
		}
	}
	private function save($oJson,$sFichier){
		
		$sJson=json_encode($oJson);
		
		file_put_contents($sFichier,$sJson);
	}
	private function getMaxId($sTable){
		$iMax=trim(file_get_contents($this->_tConfig[$this->_sConfig.'.database'].$sTable.'/max.txt'));
		return (int)$iMax;
		
	}


	private function json_decode($sFile){
		return json_decode(file_get_contents($sFile));
	}
	
	
}
