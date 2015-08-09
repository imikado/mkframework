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
*classe abstract_modelVirtual
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class abstract_modelVirtual{
    
    protected $tColumn;
    protected $tLine;
    protected $oCache;
    
    public static $_toInstance=null;
    
    public static function _getInstance($sClass){
        if(isset(self::$_toInstance[$sClass])==false){
            self::$_toInstance[$sClass]=new $sClass();
        }
        return self::$_toInstance[$sClass];
    }
    
    public function isCached(){
        $this->oCache=new _cacheVar();
        if($this->bCacheEnabled==true and $this->oCache->isCached($this->sCacheId,$this->iCacheLifetime)){
            $this->tLine=$this->oCache->getCached($this->sCacheId);
            return true;
        }
        return false;
    }
    
    public function storeCache(){
        if($this->bCacheEnabled==true){
            $this->oCache->setCache($this->sCacheId,$this->tLine);
        }
    }

    public function findMany($tSql,$sClassRow=null){
		$tRows=$this->query($this->bind($tSql),$sClassRow);

		if(!$tRows){
				return null;
		}

		return $tRows;
    }
    public function findManySimple($tSql,$sClassRow=null){
		return $this->findMany($tSql,$sClassRow);
    }
    public function findOne($tSql,$sClassRow=null){
		$tRs=$this->query($this->bind($tSql),$sClassRow);

		if(empty($tRs)){
			return null;
		}

		return $tRs[0];
    }
    public function findOneSimple($tSql,$sClassRow=null){
		return $this->findOne($tSql,$sClassRow=null);
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
			
			$tObj=$this->findInTableWithCritere($sClassRow,$sTable,$tCritere);

			//count
			if($bCount){
				$iCount=count($tObj);
				$o=new stdclass();
				$o->total=$iCount;
				return array($o);
			}else if(isset($tReq['order']) and $tObj!=null){
				return $this->sortResult($tObj,$tReq);
			}else{
				return $tObj;
			}
		}
		
		
		
	}
	private function explainSql($sReq){
		if(
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)WHERE(?<where>.*)ORDER BY(?<order>.*)LIMIT(?<limit>.*)/i'
			,$sReq,$tResult,PREG_SET_ORDER)
			or
                        
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)WHERE(?<where>.*)ORDER BY(?<order>.*)/i'
			,$sReq,$tResult,PREG_SET_ORDER)
			
                        or
			preg_match_all('/^SELECT(?<select>.*)FROM(?<from>.*)ORDER BY(?<order>.*)LIMIT(?<limit>.*)/i',$sReq,$tResult,PREG_SET_ORDER)
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
			$msg.="Le driver virtual gere les requetes de type : \n";
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

		$sSens=trim(strtoupper($sSens));

		if(!in_array($sChamp,$this->tColumn)){
			$this->erreur('Champ "'.$sChamp.'" inexistant dans cette table virtuelle, champs disponibles (sensible a la casse): '.implode(',',$this->tColumn).' (requete executee :"'.$this->_sReq.'"	)');
		}
		
		
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
                
		if(isset($tReq['limit'])){

			list($iOffset,$iLimit)=preg_split('/,/',trim($tReq['limit']));

			$iOffset=(int)$iOffset;
			$iLimit=(int)$iLimit;

			$tOrderedObj=array();
			$tId= array_keys($tTri);
			$i=-1;
			$j=0;
			foreach($tId as $id){
				$i++;
				if($i < $iOffset){  continue;}

				if($j>$iLimit){ break;}

				$tOrderedObj[]=$tIdObj[$id];

				$j++;
			}

		}else{

			$tOrderedObj=array();
			$tId= array_keys($tTri);
			foreach($tId as $id){
				$tOrderedObj[]=$tIdObj[$id];
			}
		}

		return $tOrderedObj;
	}
	private function findInTableWithCritere($sClassRow,$sTable,$tCritere){
		$tFile=$this->getTabFromFile($this->tLine);
			
		$tObj=array();
		foreach($tFile as $oRow){
			
			foreach($tCritere as $sCritereField => $sCritereVal){
				
				if(!isset($oRow->$sCritereField) or 
					(
						($sCritereVal[0]=='=' and (string)$sCritereVal!=(string)'='.$tRow->$sCritereField)
						
						or
						
						($sCritereVal[0]=='!' and (string)$sCritereVal==(string)'!'.$tRow->$sCritereField)
					)
				){
					continue 2;
				}
			}
			
			
			$tObj[]=$oRow;
			
		}
			
		return $tObj;
		
		
	}
        
	public function getTabFromFile($tContent){
		
		$tHeader=$this->tColumn;
		
		$tab=array();
		if($tContent){
		foreach($tContent as $i => $tLigne){
			 
			$obj=new stdclass();
			foreach($tHeader as $i => $sHeader){
				$obj->$sHeader=$tLigne[$i];
			}
			$tab[]=$obj;
			
			}
		}
		return $tab;
		
	}
   
    
    public function setColumns($tColumn){
        $this->tColumn=$tColumn;
    }
    
    public function addLine($tValue){
        
        $this->tLine[]=$tValue;
    }
	
	public function addObject($obj){
		
		foreach($this->tColumn as $i => $column){
			$tValue[$i]=null;
			if(isset($obj->$column)){
				$tValue[$i]=$obj->$column;
			}
		}
		
		$this->tLine[]=$tValue;
	}
    
    public function bind($tReq){
		$sReq='';
		
		if(is_array($tReq)){
			$sReq=$tReq[0];
			if(isset($tReq[1]) and is_array($tReq[1])){
				$tParam=$tReq[1];
			}else{
				unset($tReq[0]);
				$tParam=array_values($tReq);
			}
			
			foreach($tParam as $sVal){
				$sVal=$this->quote($sVal);
				$sReq=preg_replace('/[?]/',$sVal,$sReq,1);
			}
		}else{
			return $tReq;
		}
			
		return $sReq;
	}
	
	public function erreur($sErreur){
		throw new Exception($sErreur);
	}
    
}