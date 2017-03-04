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
* plugin_datetime classe pour gerer la date
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_sc_datetime{

	protected $_oDatetime;

	/**
	* @param string $sDatetime_ datetime 2016-12-25 00:00:00
	* @param string $sTimezone_ locale
	*/
	public function __construct($sDatetime_='now',$sTimezone_=null){
		if($sTimezone_){
			$uTimezone=new DateTimeZone($sTimezone_);
		}else{
			$uTimezone=null;
		}
		$this->_oDatetime=new DateTime($sDatetime_,$uTimezone);
	}

	public function addDay($nbDay_){
		
	}



	/**
	* @param string $sFormat_ format voulu en retour (appel la fonction date() )
	* @return string la date au format $sFormat cf format connu de la fonction php date()
	*/
	public function toString($sFormat_='Y-m-d H:i:s'){
		return $this->_oDatetime->format($sFormat_);
	}

}
