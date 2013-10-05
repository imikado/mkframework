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
* _cache classe pour gerer le cache
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _cache{
	
	/** 
	* retourne vrai ou faux selon que le cache est rescent
	* @access public
	* @param string $sId
	* @param int $iMinute
	* @return bool
	*/
	public function isCached($sId,$iMinute=null){
		$oFile=new _file(_root::getConfigVar('path.cache').$sId.'.cache');
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
	* retourne l'objet _view $sId en cache
	* @access public
	* @param string $sId
	* @return object _view
	*/
	public function getCached($sId){
		$oView=new _view(_root::getConfigVar('path.cache').$sId.'.cache');
		return $oView;
	}
	/** 
	* met l'objet _view $sId en cache
	* @access public
	* @param string $sId
	* @param object $oTpl
	*/
	public function setCache($sId,$oView){
		$oFile=new _file(_root::getConfigVar('path.cache').$sId.'.cache');
		$oFile->setContent($oView->show() );
		$oFile->save();
	}
	/** 
	* supprime l'objet _view $sId en cache
	* @access public
	* @param string $sId
	*/
	public function clearCache($sId){
		$oFile=new _file(_root::getConfigVar('path.cache').$sId.'.cache');
		$oFile->delete();
	}
	


}
