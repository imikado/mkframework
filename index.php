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

/*-----------------------------------------------------------------------------------
Auteur: Mika http://mkdevs.com
Page: tout passe par la; c'est le moteur du site, il gere les appels aux modules/action...

Description:
C'est ici que vous pouvez installer ci-besoin un compteur de visite
-----------------------------------------------------------------------------------*/

/* decommenter pour utiliser zendframework a partir de la 1.8
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(false);
*/

//on parse le fichier ini pour trouver l'adresse de la librairie
$tIni=parse_ini_file('conf/site.ini.php',true);
//enregistrement de l'auto loader du framework
include($tIni['path']['lib'].'/class_root.php');
//spl_autoload_register(array('_root','autoload'));

include($tIni['path']['plugin'].'/plugin_autoload.php');
spl_autoload_register(array('plugin_autoload','autoload'));

//pour gerer toutes les erreurs en exception
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

$oRoot=new _root();
$oRoot->addConf('conf/mode.ini.php');
$oRoot->addConf('conf/site.ini.php');
$oRoot->addConf('conf/language.ini.php');
$oRoot->addConf('conf/market.ini.php');
$oRoot->addRequest($_GET);
$oRoot->addRequest($_POST);
$oRoot->run();

?>
