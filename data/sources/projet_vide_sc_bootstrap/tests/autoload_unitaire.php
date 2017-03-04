<?php

date_default_timezone_set('Europe/Paris');

chdir(dirname(__FILE__));
$tIni = parse_ini_file('../conf/path.ini.php', true);


if (file_exists($tIni['path']['lib'])) {

} else if (file_exists('/var/www/common/mkframework')) {
	$tIni['path']['lib'] = '/var/www/common/mkframework/lib/framework/';
} else {
	$tIni['path']['lib'] = 'D:/code/www/mkframework/lib/framework/';
}


$sLib = $tIni['path']['lib'] . '../../module/mods/all/testunit/lib';


$tUnitTestClass = array(
    'class_layout.php',
    'class_view.php',
    'class_unitTest.php',
    'abstract/abstract_module.php',
    'abstract/abstract_model.php',
    'abstract/abstract_row.php'
);

foreach ($tUnitTestClass as $sClass) {
	include $sLib . '/' . $sClass;
}

include ($tIni['path']['lib'] . '/class_root.php');

//enregistrement de l'autoloader
include($tIni['path']['plugin'] . '/sc/plugin_sc_autoload.php');
spl_autoload_register(array('plugin_sc_autoload', 'autoload'));

$oRoot = new _root();
$oRoot->setConfigVar('path', $tIni['path']);
$oRoot->addConf('../conf/mode.ini.php');
$oRoot->addConf('../conf/connexion.ini.php');
$oRoot->addConf('../conf/site.ini.php');
$oRoot->loadConf();
