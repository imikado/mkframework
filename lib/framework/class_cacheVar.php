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
* _cachevar classe pour gerer le cache de variable (par exemple recordset de requete ORM)
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _cacheVar{
	
	protected $_oFile=null;
	
	/** 
	* retourne vrai ou faux selon que le cache est rescent
	* @access public
	* @param string $sId
	* @param int $iMinute
	* @return bool
	*/
	public function isCached($sId,$iMinute=null){
		$oFile=new _file(_root::getConfigVar('path.cache').$sId.'.cachevar');
		$this->_oFile=$oFile;
		if($oFile->exist()){
			if($iMinute==null){
				return true;
			}else if( (time()-$oFile->filemtime()) < ($iMinute*60)){
				return true;
			}
			return false;
		}
		return false;
	}
	/** 
	* retourne l'objet php $sId en cache
	* @access public
	* @param string $sId
	* @return mixed uData
	*/
	public function getCached($sId){
		$uData=unserialize($this->_oFile->getContent());
		return $uData;
	}
	/** 
	* met l'objet _view $sId en cache
	* @access public
	* @param mixed $uData
	*/
	public function setCache($sId,$uData){
		$sData=serialize($uData);
		
		$this->_oFile->setContent($sData );
		$this->_oFile->save();
	}


}
