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
class model_mkfbuilderfactory extends abstract_model{
	
	protected $sClassRow='row_factory';
	
	protected $sTable='';
	protected $sConfig='';
	
	protected $tId=array();
	
	public static function getInstance(){
		return self::_getInstance(__CLASS__);
	}

	public function setConfig($sConfig){
		$this->sConfig=$sConfig;
	}
	public function setTable($sTable){
		$this->sTable=$sTable;
	}
}

?>
