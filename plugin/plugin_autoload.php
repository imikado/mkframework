<?php
class plugin_autoload{
	
	public static function autoload($sClass){
		
		$tab=preg_split('/_/',$sClass);
		if($sClass[0]=='_'){
			include _root::getConfigVar('path.lib').'class'.$sClass.'.php';
		}else if(in_array($tab[0],array('plugin','model','abstract'))){
			include _root::getConfigVar('path.'.$tab[0]).$sClass.'.php';
		}else if($tab[0]=='module'){
			if(count($tab) == 2 or _root::getConfigVar('module.folder.organized',0)==0){
				include _root::getConfigVar('path.module').substr($sClass,7).'/main.php';
			}else{
				unset($tab[0]);
				include _root::getConfigVar('path.module').implode('/',$tab).'/main.php';
			}
		}else if($tab[0]=='row'){
			include _root::getConfigVar('path.model').'model_'.substr($sClass,4).'.php';
		}else if($tab[0]=='sgbd' and in_array($tab[1],array('syntax','pdo'))){
			include _root::getConfigVar('path.lib').'sgbd/'.$tab[1].'/'.$sClass.'.php';
		}else if($tab[0]=='sgbd'){
			include _root::getConfigVar('path.lib').'sgbd/'.$sClass.'.php';
		
		//definissez ici votre regle de chargement
		//ici, si la classe debute par my_, on la cherche dans ../myClass
		}else if(substr($sClass,0,3)=='my_'){
			//on inclut la classe en tronquant my_
			//exple: my_metier => ../myClass/metier.php
			include '../myClasses/'.substr($sClass,3).'.php';
		}else{
			return false;
		}
		
	}
	
	
}
