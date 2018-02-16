;<?php die();?>
[cache]
enabled=0
lifetime= (0*3600)

[encodage]
;indique l'encodage des fichiers de code, utilise pour la protection des tableaux de contexte ($_GET,$_POST)
charset=ISO-8859-1
;indique si le framework encode en html le code deja en html
double_encode=0

[db]

[debug]
class=Plugin\DebugError

[module]
folder.organized=1

[auth]
;note : >= php5.2 dans le php.ini 
;session.cookie_httponly=1
;session.use_cookies = 1
;session.use_only_cookies = 1
enabled=0
class=Plugin\Auth
module=auth::login
session.timeout.enabled=1
session.timeout.lifetime=(60*1)

[navigation]
scriptname=index.php
var=:nav
module.default=builder
action.default=index
layout.erreur=site/layout/erreurprod.php

[urlrewriting]
enabled=0
class=Plugin\Routing
conf=conf/routing.php
use4O4=0

[security]
;XSRF ou CSRF,bSecuriteXSRF utilisation de jeton dans le CRUD, plus d'infos: http://fr.wikipedia.org/wiki/Cross-Site_Request_Forgeries
;XSS, bSecuriteXSS protection des variables GET,POST... via getParam( , plus d'infos http://fr.wikipedia.org/wiki/XSS
xsrf.enabled=1
xsrf.timeout.lifetime=(60*3)
xss.enabled=1
xsrf.session.enabled=0

[log]
class=Plugin\Log
application=0
warning=0
error=0
information=0
file.enabled=1
apache.enabled=1

[check]
class=Plugin\Check

[site]
;Redirection
;header : header('location:$url ') 
;http: <html><head><META http-equiv="refresh" content="0; URL=$url ></head></html>
redirection.default=header
timezone=Europe/Paris

[path]
lib=lib/framework/

log=data/log/
view=view/
generation=data/genere/
data=data/
conf=conf/
module=module/
plugin=plugin/
model=model/
img=data/img/
i18n=data/i18n/
cache=data/cache/
layout=site/layout/
