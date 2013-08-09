;<?php die();?>
[cache]
enabled=0
lifetime= (0*3600)

[encodage]
;indique l'encodage des fichiers de code, utilise par defaut pour plugin_html::encode($texte) 
charset=ISO-8859-1

[language]
;fr / en... //sLangue
default=fr
allow=fr,en

[auth]
;note : >= php5.2 dans le php.ini 
;session.cookie_httponly=1
;session.use_cookies = 1
;session.use_only_cookies = 1
enabled=0
class=plugin_auth
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
class=plugin_routing
conf=conf/routing.php

[security]
;XSRF ou CSRF,bSecuriteXSRF utilisation de jeton dans le CRUD, plus d'infos: http://fr.wikipedia.org/wiki/Cross-Site_Request_Forgeries
;XSS, bSecuriteXSS protection des variables GET,POST... via getParam( , plus d'infos http://fr.wikipedia.org/wiki/XSS
xsrf.enabled=1
xsrf.timeout.lifetime=(60*3)
xss.enabled=1
xsrf.session.enabled=0

[log]
class=plugin_log
application=0
warning=0
error=0
information=0

[check]
class=plugin_check

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
