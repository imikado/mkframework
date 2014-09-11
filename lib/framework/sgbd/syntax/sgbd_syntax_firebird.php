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
class sgbd_syntax_firebird{
	
	public static function getListColumn($sTable){
		return 'select  f.rdb$field_name from rdb$relation_fields f
		join rdb$relations r on f.rdb$relation_name = r.rdb$relation_name
		and r.rdb$view_blr is null 
		and (r.rdb$system_flag is null or r.rdb$system_flag = 0)

		WHERE f.rdb$relation_name=\''.$sTable.'\' ';
	}
	public static function getStructure($sTable){
		return 'select  f.rdb$field_name from rdb$relation_fields f
		join rdb$relations r on f.rdb$relation_name = r.rdb$relation_name
		and r.rdb$view_blr is null 
		and (r.rdb$system_flag is null or r.rdb$system_flag = 0)

		WHERE f.rdb$relation_name=\''.$sTable.'\' ';
	}
	public static function getListTable(){
		return 'select rdb$relation_name from rdb$relations where rdb$view_blr is null and (rdb$system_flag is null or rdb$system_flag = 0);';
	}
	public static function getLimit($sRequete,$iOffset,$iLimit){
		return $sRequete.' LIMIT '.$iOffset.','.$iLimit;
	}
	public static function getLastInsertId(){
		return 'SELECT LAST_INSERT_ID()';
	}
}
