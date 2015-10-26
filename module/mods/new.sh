#!/bin/bash
mkdir $1/$2
mkdir $1/$2/view
mkdir $1/$2/src
mkdir $1/$2/i18n
cp all/example/main.php $1/$2/
cp all/example/info.ini $1/$2/
cp all/example/view/index.php $1/$2/view/
cp all/example/i18n/fr.php $1/$2/i18n/
cp all/example/i18n/en.php $1/$2/i18n/
cp all/example/src/example.php $1/$2/src/
cp all/example/src/example.php.xml $1/$2/src/
