<?php
/*-----------------------------------------------------------------------------------
Auteur: Mika http://mkdevs.com
Page: tout passe par la; c'est le moteur du site, il gere les appels aux modules/action...

Description:
C'est ici que vous pouvez installer ci-besoin un compteur de visite
-----------------------------------------------------------------------------------*/

/* decommenter pour utiliser zendframework a partir de la 1.8
set_include_path(get_include_path() . PATH_SEPARATOR .'../../lib/');

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(false);
*/

$iMicrotime=microtime();

//on parse le fichier ini pour trouver l'adresse de la librairie
$tIni=parse_ini_file('../conf/path.ini.php',true);
//enregistrement de l'auto loader du framework
include($tIni['path']['lib'].'/class_root.php');

//enregistrement de l'autoloader
include($tIni['path']['plugin'].'/plugin_autoload.php');
spl_autoload_register(array('plugin_autoload','autoload'));


//pour gerer toutes les erreurs en exception
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

$oRoot=new _root();
$oRoot->setConfigVar('path',$tIni['path']);

//decommenter pour activer le cache de fichier de configuration
//$oRoot->setConfigVar('cache.conf.enabled',1);

$oRoot->addConf('../conf/mode.ini.php');
$oRoot->addConf('../conf/connexion.ini.php');
$oRoot->addConf('../conf/site.ini.php');
$oRoot->addRequest($_GET);
$oRoot->addRequest($_POST);
$oRoot->run();

if(_root::getConfigVar('site.mode')=='dev'){
	$oDebug=new plugin_debug($iMicrotime);
	echo $oDebug->display();
}
if(_root::getConfigVar('log.performance')==1){
        $sUser=null;
        $oAccount=_root::getAuth();
        if($oAccount and $oAccount->getAccount()){
                $sUser=$oAccount->getAccount()->ACC_Login;
        }
        $iDelta=sprintf('%0.3f',plugin_debug::microtime()-plugin_debug::microtime($iMicrotime));
        $sLog=date('Y-m-d').';'.date('H:i:s').';'.$sUser.';'.$_SERVER['REQUEST_URI'].';'.$iDelta.'s'."\n";
        file_put_contents(_root::getConfigVar('path.log','data/log/').date('Y-m-d').'_performance.csv', $sLog, FILE_APPEND);
}
