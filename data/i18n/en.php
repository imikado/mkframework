<?php
_root::setConfigVar('tLangue',array(

//menu top
'menuTop_createProject'=>'Create a project',
'menuTop_editProjects'=>'Manage projects',

//menu projet
'menuProject_title_couchemodel'=>'Model part',
	'menuProject_link_createCoucheModel'=>'Create model part',
'menuProject_title_modules'=>'Modules',
	'menuProject_link_createModule'=>'Create a module',
	'menuProject_link_createModuleCRUD'=>'Create a CRUD module',
	'menuProject_link_createModuleCRUDreadonly'=>'Create a read-only module',
	'menuProject_link_createModuleAuth'=>'Create an authentification module',
	'menuProject_link_createModuleAuthWithInscription'=>'Create an authentification module with inscription',
	
	'menuProject_link_createModuleCRUDguriddo'=>'Create a CRUD Guriddo module',
	
	'menuProject_link_createAcl'=>'Add acl on your project <sup>Beta</sup>',
	
	
'menuProject_title_moduleEmbedded'=>'Embedded modules',
	'menuProject_link_createModuleMenuEmbedded'=>'Create a menu module ',
	'menuProject_link_createModuleEmbedded'=>'Create an embedded module',
	'menuProject_link_createModuleCRUDEmbedded'=>'Create an embedded CRUD module',
	'menuProject_link_createModuleCRUDreadonlyEmbedded'=>'Create an embedded read-only module',

'menuProject_title_views'=>'Views',
	'menuProject_link_addViewTablesimple'=>'Create a simple table',
	'menuProject_link_addForm'=>'Create a form',
	
'menuProject_title_databasesEmbedded'=>'Embedded databases',
	'menuProject_link_createDatabaseXml'=>'Create xml database',
	'menuProject_link_createDatabaseXmlIndex'=>'Create index on xml database',
	'menuProject_link_createDatabaseCsv'=>'Create a csv database',
	'menuProject_link_createDatabaseSqlite'=>'Create a sqlite database',
	'menuProject_link_createDatabaseJson'=>'Create a json database',
	'menuProject_link_createDatabaseJsonIndex'=>'Create index on json database',
	
'menuNavProject_link_edit'=>'Edit project',
'menuNavProject_link_explore'=>'Explore project',
'menuNavProject_link_gotoSite'=>'Watch the website',
'menuNavProject_link_export'=>'Export the project<sup>BETA</sup>',

//builder new
'builder::new_nomDuProjetAcreer'=>'Project name to create',
'builder::new_applicationVide'=>'Empty application',
'builder::new_applicationAvecExemples'=>'Application with Examples*',
'builder::new_applicationComptBootstrap'=>'Application bootstrap compatible**',
'builder::new_applicationAvecExemplesAsterisk'=>'* If this cas is checked: 
during the generation, examples modules will be added to your project.',
'builder::new_applicationComptBootstrapAsterisk'=>'** If this cas is checked: 
during the generation a new specific layout for bootstrap will be created, and the builder will generate CRUD and module bootstrap compatible.',
'builder::new_applicationComptBootstrapPlusdInfos'=>'More information about bootstrap',
'builder::new_creer'=>'Create',
'builder::new_errorVotreRepertoirePasInscriptible'=>'Error: your directory <u>%s</u> must be writable',

//builder edit
//model
'builder::edit_model_selectionnerLeProfilAutiliser'=>'Select profile to use',
'builder::edit_model_disponibleDansFichierConnexion'=>'(available in file conf/connexion.ini.php of your project)',
'builder::edit_model_laMethodeGetselectPermetDe'=>'* getSelect() method return an indexed arry used in dropdown list and list array.',
'builder::edit_model_siUneClasseModeleExiste'=>'* If a model class still exist, you need to delete it to generate',
'builder::edit_model_table'=>'Table',
'builder::edit_model_clePrimaire'=>'Primary key',
'builder::edit_model_ajouterUneMethodeGetselect'=>'Add a getSelect() method*',
'builder::edit_model_contraintes'=>'Constraints',
'builder::edit_model_laClasseModelExisteDeja'=>'The "model_%s.php" class still exist*',
'builder::edit_model_retourneUnTableauAvec'=>'Return an array with',
'builder::edit_model_commeCle'=>'as key',
'builder::edit_model_commeValeur'=>'as value',
'builder::edit_model_regle'=>'rule',
'builder::edit_model_et'=>'and',
'builder::edit_model_afficher'=>'Display',
'builder::edit_model_effacer'=>'Clear',

//module
'builder::edit_module_module'=>'Module',
'builder::edit_module_actions'=>'Actions',
'builder::edit_module_entrezLesActionsSuivi'=>'Fill actions with carriage return',
'builder::edit_module_generer'=>'Generate',

//CRUD
'builder::edit_crud_choisissezUneClassModele'=>'Choose a model class',
'builder::edit_crud_leModuleExisteDeja'=>'Module module/%s still exist, you should fill a different name',
'builder::edit_crud_nomDuModuleAcreer'=>'Module name to create',

'builder::edit_crud_actionsCrud'=>'CRUD actions',
'builder::edit_crud_formulaireAjout'=>'Add form',
'builder::edit_crud_formulaireDeModification'=>'Update form',
'builder::edit_crud_formulaireDeSuppression'=>'Delete form',
'builder::edit_crud_formulaireDaffichageDetail'=>'Detail display',

'builder::edit_crud_options'=>'Options',
'builder::edit_crud_avecPagination'=>'with pagination',

'builder::edit_crud_champ'=>'Field',
'builder::edit_crud_libelle'=>'Label',
'builder::edit_crud_type'=>'Type',
'builder::edit_crud_tripardefaut'=>'Default sort',

'builder::edit_crud_limit'=>'Limit',

'builder::edit_crud_dimensions'=>'Dimensions',
'builder::edit_crud_width'=>'Width',
'builder::edit_crud_height'=>'Height',
	
'builder::edit_crud_selectEnUtilisant'=>'dropdown list with',

'builder::edit_crud_creer'=>'create',

//CRUD read-only
'builder::edit_crudreadonly_choisissezUneClasseModele'=>'Choose a class model',
'builder::edit_crudreadonly_nomDuModuleAcreer'=>'Module name to create',
'builder::edit_crudreadonly_leModuleExisteDeja'=>'Module module/%s still exists, you should indicate different name',
'builder::edit_crudreadonly_champ'=>'Champ',
'builder::edit_crudreadonly_type'=>'Type',

//auth
'Builder::edit_authmodule_choisissezLaClasseAutiliser'=>'Choose users model class to use',
'Builder::edit_authmodule_champUtilisateur'=>'User field',
'Builder::edit_authmodule_champMdp'=>'Password field',
'Builder::edit_authmodule_ilVousFautModifierLaClasse'=>'You have to update file class : "model/%s"',
'Builder::edit_authmodule_ilVousFautAjouterCesMethodes'=>'You have to add methods on your model class',
'Builder::edit_authmodule_deVosComptesDeConnexion'=>' of your connexion accounts',
'Builder::edit_authmodule_uneMethode'=>'A method',
'Builder::edit_authmodule_quiRetourneraUntableauIndexe'=>'Which return indexed array of your connexion accounts',
'Builder::edit_authmodule_etUneMethode'=>'And a method',
'Builder::edit_authmodule_quiRetourneraLeHashageDuMdp'=>'Wich return an hash of the password (don\'t store password in clear)',
'Builder::edit_authmodule_pensezAmodifierLeSel'=>'Think to update password salt',
'Builder::edit_authmodule_pourRendreEmpreintSecurise'=>'to increase security',
'Builder::edit_authmodule_ajoutezCesMethodesDansLaClasse'=>'Add this methods in the model class, then',
'Builder::edit_authmodule_reactualisezLaPage'=>'reload the page',

//acl
'Builder::edit_addrightsmanager_presentation'=>'Presentation',
'Builder::edit_addrightsmanager_pourGererLesDroitsNousAllons'=>'To generate rights of your applicatoin, you should first create database',
'Builder::edit_addrightsmanager_miseEnPlace'=>'Implementation',
'Builder::edit_addrightsmanager_vousNetesPasObligerDutiliserLesmemes'=>'You have not to use same fields name or table name, you will choose for each table name of your model class, then select fields',
'Builder::edit_addrightsmanager_utilisateur'=>'Users',
'Builder::edit_addrightsmanager_groupes'=>'Groups',
'Builder::edit_addrightsmanager_permissions'=>'Permissions',
'Builder::edit_addrightsmanager_elements'=>'Elements',
'Builder::edit_addrightsmanager_actions'=>'Actions',
'Builder::edit_addrightsmanager_nomDuModuleAgenerer'=>'Module name to generate',
'Builder::edit_addrightsmanager_nomDeLaClasseAgenerer'=>'model class name to generate',
'Builder::edit_addrightsmanager_generer'=>'Generate',
'Builder::edit_addrightsmanager_ajouterLeChargementDesDroits'=>'Add rights load on authentification module',
'Builder::edit_addrightsmanager_editezVotreFichier'=>'Edit your file module/%s/main.php and edit your authentification method',

//menu embedded
'Builder::edit_addmodulemenu_pourCreerLeMenu'=>'To generate the menu, fill actions/pages of the module to create and fill label of links',
'Builder::edit_addmodulemenu_nomDuModule'=>'Module name',
'Builder::edit_addmodulemenu_leRepertoireModuleExisteDeja'=>'Module directory still exists',
'Builder::edit_addmodulemenu_methodeAppelee'=>'Called method',
'Builder::edit_addmodulemenu_libelleDuLien'=>'Link label',
'Builder::edit_addmodulemenu_classe'=>'class',
'Builder::edit_addmodulemenu_genererLeMenu'=>'Generate menu',

//module embedded
'Builder::edit_addmodulemenu_module'=>'Module',
'Builder::edit_addmodulemenu_actions'=>'Actions',
'Builder::edit_addmodulemenu_entrezLesActions'=>'Fill actions ended by cariage return',
'Builder::edit_addmodulemenu_generer'=>'Generate',

//crud embedded
//same crud

//simple table
'builder::edit_addviewtablemoduletablesimple_choisissezUneClasseModele'=>'Choose a model class',
'builder::edit_addviewtablemoduletablesimple_choisissezLaMethodeQuiRemplira'=>'Choose the method wich will fill the table',
'builder::edit_addviewtablemoduletablesimple_creerLaVue'=>'Create the view',
'builder::edit_addviewtablemoduletablesimple_activerLalternance'=>'Enable alternance',
'builder::edit_addviewtablemoduletablesimple_classesAalterner'=>'Class to switch',
'builder::edit_addviewtablemoduletablesimple_classeDuTableau'=>'Class of the table',
'builder::edit_addviewtablemoduletablesimple_champ'=>'Field',
'builder::edit_addviewtablemoduletablesimple_libelle'=>'Label',
'builder::edit_addviewtablemoduletablesimple_type'=>'Type',

'builder::edit_addviewtablemoduletablesimple_creer'=>'create',

//index xml
'builder::edit_xmlindex_choisissezLeProfilAutiliser'=>'Choose xml profile to use',
'builder::edit_xmlindex_choisirLaTable'=>'Choose table where create index',

//databases embedded
'label_Champs'=>'Fields',
'label_EntrezLesActions'=>'Fill actions ended by cariage return',
'label_Generer'=>'Generate',
'label_SelectionnnezUneConfig'=>'Choose a config',
'label_NomDeLaTable'=>'Table name',
'label_ClePrimaire'=>'Primary key',
'label_Champ'=>'Field',
'label_Type'=>'Type',
'label_Longueur'=>'Length',
'label_AjouterUnChamp'=>'Add a field',
'label_choisissezLeProfilAutiliser'=>'Choose profile to use',



));?>
