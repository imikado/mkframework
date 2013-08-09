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
class sgbd_syntax_oracle{
	
	public static function getListColumn($sTable){
		return 'SELECT  COLUMN_NAME 
		FROM user_tab_columns
		WHERE table_name = \''.$sTable .'\'';
	}
	public static function getStructure($sTable){
		return 'SELECT  COLUMN_NAME 
		FROM user_tab_columns
		WHERE table_name = \''.$sTable . '\'';
	}
	public static function getListTable(){
		return 'SELECT table_name FROM user_tables';
	}
	public static function getLimit($sRequete,$iOffset,$iLimit){
		return $sRequete.' LIMIT '.$iOffset.','.$iLimit;
	}
	
}

