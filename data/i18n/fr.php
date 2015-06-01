<?php
_root::setConfigVar('tLangue',array(

//menu top
'menuTop_createProject'=>'Cr&eacute;er un projet',
'menuTop_editProjects'=>'Administrer les projets',

//menu projet
'menuProject_title_couchemodel'=>'Couche modele',
	'menuProject_link_createCoucheModel'=>'Cr&eacute;er la couche mod&egrave;le',
'menuProject_title_modules'=>'Modules',
	'menuProject_link_createModule'=>'Cr&eacute;er un module',
	'menuProject_link_createModuleCRUD'=>'Cr&eacute;er un module CRUD',
	'menuProject_link_createModuleCRUDreadonly'=>'Cr&eacute;er un module Lecture seule',
	'menuProject_link_createModuleAuth'=>'Cr&eacute;er un module d\'authentification',
	'menuProject_link_createModuleAuthWithInscription'=>'Cr&eacute;er un module d\'authentification avec inscription',
	
	'menuProject_link_createAcl'=>'Ajouter une gestion de droits &agrave; votre application <sup>Beta</sup>',
	
	
'menuProject_title_moduleEmbedded'=>'Modules int&eacute;grable',
	'menuProject_link_createModuleMenuEmbedded'=>'Cr&eacute;er un module menu ',
	'menuProject_link_createModuleEmbedded'=>'Cr&eacute;er un module int&eacute;grable',
	'menuProject_link_createModuleCRUDEmbedded'=>'Cr&eacute;er un module CRUD int&eacute;grable',
	'menuProject_link_createModuleCRUDreadonlyEmbedded'=>'Cr&eacute;er un module lecture seule int&eacute;grable',

'menuProject_title_views'=>'Vues',
	'menuProject_link_addViewTablesimple'=>'Cr&eacute;er un tableau simple (avec le module table)',
	'menuProject_link_addForm'=>'C&eacuteer un formulaire',
	
'menuProject_title_databasesEmbedded'=>'Base de donn&eacute;es embarqu&eacute;es',
	'menuProject_link_createDatabaseXml'=>'Cr&eacute;er une base xml',
	'menuProject_link_createDatabaseXmlIndex'=>'Cr&eacute;er un index sur une base xml',
	'menuProject_link_createDatabaseCsv'=>'Cr&eacute;er une base csv',
	'menuProject_link_createDatabaseSqlite'=>'Cr&eacute;er une base sqlite',
	'menuProject_link_createDatabaseJson'=>'Cr&eacute;er une base json',
	'menuProject_link_createDatabaseJsonIndex'=>'Cr&eacute;er un index sur une base json',

'menuNavProject_link_edit'=>'Editer le projet',
'menuNavProject_link_explore'=>'Explorer le projet',
'menuNavProject_link_gotoSite'=>'Voir le site',
'menuNavProject_link_export'=>'Exporter le projet<sup>BETA</sup>',

//builder new
'builder::new_nomDuProjetAcreer'=>'Nom du projet &agrave; cr&eacute;er',
'builder::new_applicationVide'=>'Application vide',
'builder::new_applicationAvecExemples'=>'Application avec Exemples*',
'builder::new_applicationComptBootstrap'=>'Application compatible bootstrap**',
'builder::new_applicationAvecExemplesAsterisk'=>'* Si cette case est coch&eacute;e: 
au moment de la g&eacute;n&eacute;ration de votre nouvelle application, des modules de bases ainsi que des classes exemples seront g&eacute;n&eacute;r&eacute;es.',
'builder::new_applicationComptBootstrapAsterisk'=>'** Si cette case est coch&eacute;e: 
au moment de la g&eacute;n&eacute;ration de votre nouvelle application, un layout spécifique bootstrap sera cr&eacute;&eacute; et le menu de builder contiendra des actions compatibles avec bootstrap.',
'builder::new_applicationComptBootstrapPlusdInfos'=>'Plus d\'informations sur bootstrap',
'builder::new_creer'=>'Cr&eacute;er',
'builder::new_errorVotreRepertoirePasInscriptible'=>'Erreur: votre r&eacute;pertoire <u>%s</u> doit &ecirc;tre inscriptible',

//builder edit
//model
'builder::edit_model_selectionnerLeProfilAutiliser'=>'S&eacute;lectionner le profil &agrave; utiliser',
'builder::edit_model_disponibleDansFichierConnexion'=>'(disponible dans le fichier conf/connexion.ini.php de votre projet)',
'builder::edit_model_laMethodeGetselectPermetDe'=>'* La m&eacute;thode getSelect() permet de retourner un tableau index&eacute; utilis&eacute; pour les menus d&eacute;roulant et les tableaux de liste.',
'builder::edit_model_siUneClasseModeleExiste'=>'* Si une classe mod&egrave;le existe d&eacute;j&agrave;, il vous faut la supprimer pour pouvoir la reg&eacute;n&eacute;rer',
'builder::edit_model_table'=>'Table',
'builder::edit_model_clePrimaire'=>'Cl&eacute; primaire',
'builder::edit_model_ajouterUneMethodeGetselect'=>'Ajouter une m&eacute;thode getSelect()*',
'builder::edit_model_contraintes'=>'Contraintes',
'builder::edit_model_laClasseModelExisteDeja'=>'La classe "model_%s.php" existe d&eacute;j&agrave;*',
'builder::edit_model_retourneUnTableauAvec'=>'Retourne un tableau avec',
'builder::edit_model_commeCle'=>'comme cl&eacute;',
'builder::edit_model_commeValeur'=>'comme valeur',
'builder::edit_model_regle'=>'r&egrave;gle',
'builder::edit_model_et'=>'et',
'builder::edit_model_afficher'=>'Afficher',
'builder::edit_model_effacer'=>'Effacer',

//module
'builder::edit_module_module'=>'Module',
'builder::edit_module_actions'=>'Actions',
'builder::edit_module_entrezLesActionsSuivi'=>'Entrez les actions suivi d\'un retour chariot',
'builder::edit_module_generer'=>'G&eacute;n&eacute;rer',

//CRUD
'builder::edit_crud_choisissezUneClassModele'=>'Choisissez une classe mod&egrave;le',
'builder::edit_crud_leModuleExisteDeja'=>'Le module module/%s existe d&eacute;j&agrave;, veuillez indiquer un autre nom ',
'builder::edit_crud_nomDuModuleAcreer'=>'Nom du module &agrave cr&eacute;er',

'builder::edit_crud_actionsCrud'=>'Actions CRUD',
'builder::edit_crud_formulaireAjout'=>'Formulaire d\'ajout',
'builder::edit_crud_formulaireDeModification'=>'Formulaire de modification',
'builder::edit_crud_formulaireDeSuppression'=>'Formulaire de suppression',
'builder::edit_crud_formulaireDaffichageDetail'=>'Page d\'affichage du d&eacute;tail',

'builder::edit_crud_options'=>'Options',
'builder::edit_crud_avecPagination'=>'avec pagination',

'builder::edit_crud_champ'=>'Champ',
'builder::edit_crud_libelle'=>'Libell&eacute;',
'builder::edit_crud_type'=>'Type',

'builder::edit_crud_selectEnUtilisant'=>'Select en utilisant',

'builder::edit_crud_creer'=>'cr&eacute;er',

//CRUD read-only
'builder::edit_crudreadonly_choisissezUneClasseModele'=>'Choisissez une classe mod&egrave;le',
'builder::edit_crudreadonly_nomDuModuleAcreer'=>'Nom du module &agrave cr&eacute;er',
'builder::edit_crudreadonly_leModuleExisteDeja'=>'Le module module/%s existe d&eacute;j&agrave;, veuillez indiquer un autre nom',
'builder::edit_crudreadonly_champ'=>'Champ',
'builder::edit_crudreadonly_type'=>'Type',

//auth
'Builder::edit_authmodule_choisissezLaClasseAutiliser'=>'Choisissez la classe mod&egrave;le des utilisateurs &agrave; utiliser',
'Builder::edit_authmodule_champUtilisateur'=>'Champ nom d\'utilisateur',
'Builder::edit_authmodule_champMdp'=>'Champ mot de passe',
'Builder::edit_authmodule_ilVousFautModifierLaClasse'=>'Il vous faut modifier la classe : "model/%s"',
'Builder::edit_authmodule_ilVousFautAjouterCesMethodes'=>'Il vous faut ajoutez ces deux m&eacute;thodes à votre classe mod&egrave;le',
'Builder::edit_authmodule_deVosComptesDeConnexion'=>'de vos comptes de connexion',
'Builder::edit_authmodule_uneMethode'=>'Une m&eacute;thode',
'Builder::edit_authmodule_quiRetourneraUntableauIndexe'=>'Qui retournera un tableau index&eacute; de vos comptes de connexion',
'Builder::edit_authmodule_etUneMethode'=>'Et une m&eacute;thode',
'Builder::edit_authmodule_quiRetourneraLeHashageDuMdp'=>'Qui retournera le hashage(empreinte) du mot de passe (ne pas stoquer les mots de passe en clair)',
'Builder::edit_authmodule_pensezAmodifierLeSel'=>'Pensez &agrave; modifier le sel',
'Builder::edit_authmodule_pourRendreEmpreintSecurise'=>'pour rendre l\'empreinte tr&egrave;s s&eacute;curis&eacute;',
'Builder::edit_authmodule_ajoutezCesMethodesDansLaClasse'=>'Ajoutez ces deux m&eacute;thodes dans la classe mod&egrave;le concern&eacute;e puis',
'Builder::edit_authmodule_reactualisezLaPage'=>'r&eacute;actualiser la page',

//acl
'Builder::edit_addrightsmanager_presentation'=>'Pr&eacute;sentation',
'Builder::edit_addrightsmanager_pourGererLesDroitsNousAllons'=>'Pour g&eacute;rer les droits de votre application, nous allons cr&eacute;er d\'abord notre base de donn&eacute;es',
'Builder::edit_addrightsmanager_miseEnPlace'=>'Mise en place',
'Builder::edit_addrightsmanager_vousNetesPasObligerDutiliserLesmemes'=>'Vous n\'&ecirc;tes pas oblig&eacute; d\'utiliser les m&ecirc;me noms de champs et de tables, vous allez ci-dessous indiquer pour chaque table le nom de votre classe mod&egrave;le puis s&eacute;lectionnez la correspondance des champs',
'Builder::edit_addrightsmanager_utilisateur'=>'Utilisateur',
'Builder::edit_addrightsmanager_groupes'=>'Groupes',
'Builder::edit_addrightsmanager_permissions'=>'Permissions',
'Builder::edit_addrightsmanager_elements'=>'Elements',
'Builder::edit_addrightsmanager_actions'=>'Actions',
'Builder::edit_addrightsmanager_nomDuModuleAgenerer'=>'Nom du module &agrave; g&eacute;n&eacute;rer',
'Builder::edit_addrightsmanager_nomDeLaClasseAgenerer'=>'nom de la classe model &agrave; g&eacute;n&eacute;rer',
'Builder::edit_addrightsmanager_generer'=>'G&eacute;nerer',
'Builder::edit_addrightsmanager_ajouterLeChargementDesDroits'=>'Ajouter le chargement des droits sur votre module d\'authentification',
'Builder::edit_addrightsmanager_editezVotreFichier'=>'Editez votre fichier module/%s/main.php et editer la m&eacute;thode d\'authentification',

//menu embedded
'Builder::edit_addmodulemenu_pourCreerLeMenu'=>'Pour c&eacute;er le menu: cochez les actions/pages des modules &agrave; cr&eacute;er et indiquez en face le libell&eacute; du lien',
'Builder::edit_addmodulemenu_nomDuModule'=>'Nom du module',
'Builder::edit_addmodulemenu_leRepertoireModuleExisteDeja'=>'Le r&eacute;pertoire module/menu existe d&eacute;j&agrave;',
'Builder::edit_addmodulemenu_methodeAppelee'=>'M&eacute;thode appel&eacute;e',
'Builder::edit_addmodulemenu_libelleDuLien'=>'Libell&eacute; du lien',
'Builder::edit_addmodulemenu_classe'=>'classe',
'Builder::edit_addmodulemenu_genererLeMenu'=>'G&eacute;n&eacute;rer le menu',

//module embedded
'Builder::edit_addmodulemenu_module'=>'Module',
'Builder::edit_addmodulemenu_actions'=>'Actions',
'Builder::edit_addmodulemenu_entrezLesActions'=>'Entrez les actions suivi d\'un retour chariot',
'Builder::edit_addmodulemenu_generer'=>'G&eacute;n&eacute;rer',

//crud embedded
//same than crud

//simple table
'builder::edit_addviewtablemoduletablesimple_choisissezUneClasseModele'=>'Choisissez une classe mod&egrave;le',
'builder::edit_addviewtablemoduletablesimple_choisissezLaMethodeQuiRemplira'=>'Choisissez la m&eacute;thode &agrave; appeler qui remplira le tableau',
'builder::edit_addviewtablemoduletablesimple_creerLaVue'=>'Cr&eacute;er la vue',
'builder::edit_addviewtablemoduletablesimple_activerLalternance'=>'Activer l\'alternance',
'builder::edit_addviewtablemoduletablesimple_classesAalterner'=>'Classes a alterner',
'builder::edit_addviewtablemoduletablesimple_classeDuTableau'=>'Classe du tableau',
'builder::edit_addviewtablemoduletablesimple_champ'=>'Champ',
'builder::edit_addviewtablemoduletablesimple_libelle'=>'Libell&eacute;',
'builder::edit_addviewtablemoduletablesimple_type'=>'Type',

'builder::edit_addviewtablemoduletablesimple_creer'=>'cr&eacute;er',

//index xml
'builder::edit_xmlindex_choisissezLeProfilAutiliser'=>'Choisissez le profil xml &agrave; utiliser',
'builder::edit_xmlindex_choisirLaTable'=>'Choisir la table o&ugrave; cr&eacute;er l\'index',

//databases embedded
'label_Champs'=>'Champs',
'label_EntrezLesActions'=>'Entrez les actions suivi d\'un retour chariot',
'label_Generer'=>'G&eacute;nerer',
'label_SelectionnnezUneConfig'=>'S&eacute;lectionnez une config sqlite',
'label_NomDeLaTable'=>'Nom de la table',
'label_ClePrimaire'=>'Cl&eacute; primaire',
'label_Champ'=>'Champ',
'label_Type'=>'Type',
'label_Longueur'=>'Longueur',
'label_AjouterUnChamp'=>'Ajouter un champ',
'label_choisissezLeProfilAutiliser'=>'Choisissez le profil &agrave; utiliser',



));?>
