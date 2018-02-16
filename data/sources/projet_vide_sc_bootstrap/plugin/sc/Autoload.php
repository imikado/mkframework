<?php

namespace Plugin\sc;

class Autoload
{

    public static function autoload($sClass)
    {
        $tab = preg_split('/_/', $sClass);
        if ($sClass[0] == '_') {
            include \_root::getConfigVar('path.lib') . 'class' . $sClass . '.php';
        } else if (in_array($tab[0], array('abstract'))) {
            include \_root::getConfigVar('path.' . $tab[0]) . $sClass . '.php';
        } else if ($tab[0] == 'sgbd' && in_array($tab[1], array('syntax', 'pdo'))) {
            include __DIR__ . '/' . \_root::getConfigVar('path.lib') . 'sgbd/' . $tab[1] . '/' . $sClass . '.php';
        } else if ($tab[0] == 'sgbd') {
            include __DIR__ . '/' . \_root::getConfigVar('path.lib') . 'sgbd/' . $sClass . '.php';
        }
    }
}

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new \Exception('You must install vendors with composer before using this version of mk_framework');
}
include_once(__DIR__ . '/../vendor/autoload.php');
