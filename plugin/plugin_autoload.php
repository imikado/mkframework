<?php
class plugin_autoload{
	
	public static function autoload($sClass){
		
		//definissez ici votre regle de chargement
		//ici, si la classe debute par my_, on la cherche dans ../myClass
		if(substr($sClass,0,3)=='my_'){
			//on inclut la classe en tronquant my_
			//exple: my_metier => ../myClass/metier.php
			include '../myClasses/'.substr($sClass,3).'.php';
		}
		
	}
	
	
}
