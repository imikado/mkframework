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
* classe abstract_model
* @author Mika
* @link http://mkf.mkdevs.com/
*/
abstract class abstract_model{
	
	private $_sClassSgbd=null;
	private $_oSgbd=null;
	private static $_tInstance=array();
	
	protected $_tAssoc=array();
	
	public function getConfig(){
		return $this->sConfig;
	}
	public function getTable(){
		return $this->sTable;
	}
	
	/**
	* @access public static
	* @return object sgbd
	*/
	public function getSgbd(){
		$bFirst=false;
		if($this->_sClassSgbd==null){
			$sVarIniConfig=_root::getConfigVar('model.ini.var','db');
			$tClassSgbd=_root::getConfigVar($sVarIniConfig);
			if(!$tClassSgbd){
				$sMsg='Il vous manque un fichier de configuration';
				$sMsg.=' ou le bloc de configuration ['.$sVarIniConfig.'] concernant la connexion'."\n";
				$sMsg.='
				Exemple:
				['.$sVarIniConfig.']
				mysql.dsn="mysql:dbname=blog;host=localhost"
				mysql.sgbd=pdo_mysql
				mysql.hostname=localhost
				mysql.database=blog
				mysql.username=root
				mysql.password=pass
				';
				throw new Exception($sMsg);
			}
			$this->_sClassSgbd='sgbd_'.$tClassSgbd[$this->sConfig.'.sgbd'];
			$bFirst=true;
			if(substr($this->_sClassSgbd,0,8)=='sgbd_pdo'){
				$sClassPath=_root::getConfigVar('path.lib').'sgbd/pdo/'.$this->_sClassSgbd.'.php';
			}elseif(substr($this->_sClassSgbd,0,5)=='sgbd_'){
				$sClassPath=_root::getConfigVar('path.lib').'sgbd/'.$this->_sClassSgbd.'.php';
			}
			if(!file_exists($sClassPath)){
				$oDirPdo=new _dir( _root::getConfigVar('path.lib').'sgbd/pdo/');
				$tListPdo=$oDirPdo->getListFile();
				$tPlus=array('Liste driver pdo:');
				foreach($tListPdo as $oFile){
					$tPlus[]='-'.$oFile->getName();
				}
				
				$sListePdo=implode("\n",$tPlus);
				
				$oDir=new _dir( _root::getConfigVar('path.lib').'sgbd/');
				$tList=$oDir->getListFile();
				$tPlus=array('Liste driver autre:');
				foreach($tList as $oFile){
					$tPlus[]='-'.$oFile->getName();
				}
				$sListeAutre=implode("\n",$tPlus);
				
				throw new Exception('Pas de driver '.$this->_sClassSgbd.' ('.$sClassPath.')'."\n".$sListePdo."\n".$sListeAutre);
			}
		}
		
		$this->_oSgbd=call_user_func( array($this->_sClassSgbd,'getInstance'),$this->sConfig);

		if($bFirst){
			$this->_oSgbd->setConfig($tClassSgbd);
		}
		
		return $this->_oSgbd;
	}
	/** 
	* retourne un tableau d'enregistrement d'objet simple (plus rapide)
	* @access public
	* @param string $sRequete
	* @param undefined $uParam
	* @return un object
	*/
	public function findOneSimple(){
		$tSql=func_get_args();
		$oObj= $this->getSgbd()->findOneSimple($tSql,$this->sClassRow);
		/*LOG*/_root::getLog()->info('sql select:'.$this->getSgbd()->getRequete());
		return $oObj;
	}
	/** 
	* retourne un tableau d'enregistrement
	* @access public
	* @param string $sRequete
	* @param undefined $uParam
	* @return un object
	*/
	public function findOne(){
		$tSql=func_get_args();
		$oObj= $this->getSgbd()->findOne($tSql,$this->sClassRow);
		/*LOG*/_root::getLog()->info('sql select:'.$this->getSgbd()->getRequete());
		return $oObj;
	}
	/** 
	* retourne un tableau d'enregistrement
	* @access public
	* @param string $sRequete
	* @param undefined $uParam
	* @return un tableau d'object
	*/
	public function findMany(){
		$tSql=func_get_args();
		$tObj= $this->getSgbd()->findMany($tSql,$this->sClassRow);
		/*LOG*/_root::getLog()->info('sql select:'.$this->getSgbd()->getRequete());
		return $tObj;
	}
	/** 
	* retourne un tableau d'enregistrement d'objet simple (plus rapide)
	* @access public
	* @param string $sRequete
	* @param undefined $uParam
	* @return un tableau d'object
	*/
	public function findManySimple(){
		$tSql=func_get_args();
		$tObj= $this->getSgbd()->findManySimple($tSql,$this->sClassRow);
		/*LOG*/_root::getLog()->info('sql select:'.$this->getSgbd()->getRequete());
		return $tObj;
	}
	/** 
	* execute une requete 
	* @access public
	* @param string $sRequete
	* @param undefined $uParam
	*/
	public function execute(){
		$tSql=func_get_args();
		$handle=$this->getSgbd()->execute($tSql);
		/*LOG*/_root::getLog()->info('sql execute:'.$this->getSgbd()->getRequete());
		return $handle;
	}
	/** 
	* met a jour un enregistrement
	* @access public
	* @param object $oRow
	*/
	public function update($oRow){
		$this->getSgbd()->update($this->sTable,$oRow->getToUpdate(),$oRow->getWhere());
		/*LOG*/_root::getLog()->info('sql update:'.$this->getSgbd()->getRequete());
	}
	/** 
	* ajoute un enregistrement
	* @access public
	* @param object $oRow
	*/
	public function insert($oRow){
		$oInsert= $this->getSgbd()->insert($this->sTable,$oRow->getToUpdate());
		/*LOG*/_root::getLog()->info('sql insert:'.$this->getSgbd()->getRequete());
		return $oInsert;
	}
	/** 
	* supprime un enregistrement
	* @access public
	* @param object $oRow
	*/
	public function delete($oRow){
		$this->getSgbd()->delete($this->sTable,$oRow->getWhere());
		/*LOG*/_root::getLog()->info('sql delete:'.$this->getSgbd()->getRequete());
	}
	/** 
	* retourne la requete
	* @access public
	* @return string requete
	*/
	public function getRequete(){
		return $this->getSgbd()->getRequete();
	}
	public function getIdTab(){
		return $this->tId;
	}
	/** 
	* retourne un tableau contenant les colonnes d'une table
	* @access public
	* @return array
	*/
	public function getListColumn(){
		return $this->getSgbd()->getListColumn($this->sTable);
	}
	/** 
	* retourne un tableau contenant les tables
	* @access public
	* @return array
	*/
	public function getListTable(){
		return $this->getSgbd()->getListTable();
	}
	
	public function getWhereFromTab($tWhere=null){

		if($tWhere==null){
			return null;
		}

		$sWhere='';

		if(is_array($tWhere)){
			foreach($tWhere as $sVar => $sVal){
				$sWhere.=$sVar.'='.$this->quote($sVal).' AND';
			}
			return substr($sWhere,0,-3);
		}else{
			return $this->tId[0].'='.$tWhere;
		}
	}
	
	protected static function _getInstance($class) {
		if (array_key_exists($class, self::$_tInstance) === false){
			self::$_tInstance[$class] = new $class();
		}
		return self::$_tInstance[$class];
	}
	
}
