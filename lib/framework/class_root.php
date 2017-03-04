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
/**
* _root classe moteur du framework (charge les fichiers de config / parametres)
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class _root{

	static protected $_oRequest;
	static protected $_oCache;
	static protected $_oCacheVar;
	static protected $_oAuth;
	static protected $_oUrlrewriting;
	static protected $_oLog;
	static protected $_oI18n;

	static protected $_tConfigFilename;
	static protected $_tRequestTab;
	static public $tAutoload;

	static public $tConfigVar;
	static protected $_tGlobalVar;
	static protected $_tObject;

	static protected $_oACL;

	static protected $_bSessionStarted=0;

	/**
	* constructeur
	* @access public
	*/
	public function __construct(){

		self::$tConfigVar=array();

		self::$tConfigVar['path']=array(

				'lib' => 'lib/framework/',

				'conf' => 'conf/',
				'module' => 'module/',
				'plugin'=> 'plugin/',
				'model'=> 'model/',
				'img'=> 'data/img/',
				'i18n'=>'data/i18n/',
				'cache'=>'data/cache/',
				'layout'=>'site/layout/',

		);

		self::$_tConfigFilename=array();

	}

	/**
	* ajoute un fichier de config ini
	* @access public static
	* @param string $sConfig adresse du fichier de config a charger
	* @param string $sCat nom de la section a charger (si on ne veut charger qu'une section du fichier ini)
	*/
	public static function addConf($sConfig,$sCat=null){
		self::$_tConfigFilename[]=array($sConfig,$sCat);
	}
	/**
	* charge la configuration de l'application
	* @access public static
	*/
	public static function loadConf(){
		try{
			$bConfCacheEnabled=(int)self::getConfigVar('cache.conf.enabled');
			$sCacheFilename=self::getConfigVar('path.cache').'conf.php';
			if($bConfCacheEnabled==1 and file_exists($sCacheFilename) ){
				include $sCacheFilename;
				return;
			}

			$tConfigVar=self::$tConfigVar;

			foreach(self::$_tConfigFilename as $tConfig){
				$sConfig=$tConfig[0];
				$sCatFilter=$tConfig[1];

				$tIni=array();
				$tIniBrut=parse_ini_file($sConfig,true);

				if($sCatFilter!=null){
					$tIni[$sCatFilter]=$tIniBrut[$sCatFilter];
				}else{
					$tIni=$tIniBrut;
				}
				$tConfigVar=self::arrayMergeRecursive($tConfigVar,$tIni);
			}

			self::$tConfigVar=$tConfigVar;


			if($bConfCacheEnabled==1){
				$sCodeCache='<?php _root::$tConfigVar='.var_export(self::$tConfigVar,true).';';
				file_put_contents($sCacheFilename,$sCodeCache);
			}

		}catch(Exception $e){
		      self::erreurLog($e->getMessage()."\n".$e->getTraceAsString());
		}
	}

	public static function arrayMergeRecursive($tArray,$tNewArray){

		foreach($tNewArray as $sKey => $tValue){
			foreach($tValue as $sChildKey => $sChildValue){
				$tArray[$sKey][$sChildKey]=$sChildValue;
			}
		}

		return $tArray;

	}

	/**
	* charge le fichier de langue
	* @access public static
	*/
	public static function loadLangueDate(){
		include_once self::getConfigVar('path.i18n').'date_'.self::getConfigVar('language.default').'.php';

	}

	public static function nullbyteprotect($string){

		$string=trim($string);

		return preg_replace('/\\x00/','', preg_replace('/\\\0/','',$string));
	}

	public static function startSession(){
		if(self::$_bSessionStarted){
			return null;
		}else if( (int)self::getConfigVar('auth.session.use_cookies') == 1 ){
			$bHttpOnly=null;
			if((int)self::getConfigVar('auth.session.cookie_httponly')==1){
				$bHttpOnly=true;
			}
			$bSecure=null;
			if((int)self::getConfigVar('auth.session.cookie_secure')==1 and isset($_SERVER['HTTPS']) ){
				$bSecure=true;
			}
			session_set_cookie_params(
				(int)self::getConfigVar('auth.session.cookie_lifetime',0),
				self::getConfigVar('auth.session.cookie_path',null),
				self::getConfigVar('auth.session.cookie_domain',null),
				$bSecure,$bHttpOnly
			);
		}
		session_start();

		self::$_bSessionStarted=1;
	}


	/**
	* lance le framework (dispatch...)
	* @access public
	*/
	public function run(){

		try{

			self::loadConf();
			self::loadAutoload();
			self::loadRequest();

			//parametrage du niveau d'erreur
			if(self::getConfigVar('site.mode')=='dev'){
				error_reporting(E_ALL);
			}else{
				error_reporting(0);
			}

			self::getLog()->setInformation((int)self::getConfigVar('log.information'));
			self::getLog()->setWarning((int)self::getConfigVar('log.warning'));
			self::getLog()->setError((int)self::getConfigVar('log.error'));
			self::getLog()->setApplication((int)self::getConfigVar('log.application'));



			date_default_timezone_set(self::getConfigVar('site.timezone'));
			//auth
			if( (int)self::getConfigVar('auth.enabled') == 1 ){
				self::getAuth()->enable();
			}

			//desactivation des magic quotes
			if (get_magic_quotes_gpc()) {
				$_POST = array_map('stripslashes_deep', $_POST);
				$_GET = array_map('stripslashes_deep', $_GET);
				$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
				$this->getRequest()->magic_quote();
			}

			if( (int)self::getConfigVar('urlrewriting.enabled') == 1 ){
				self::getUrlRewriting()->parseUrl($_SERVER['REQUEST_URI']);
			}

			$sModuleToLoad=self::getRequest()->getModule();
			$sModuleActionToLoad=self::getRequest()->getAction();

			/*LOG*/self::getLog()->info('module a appeler ['.$sModuleToLoad.'::'.$sModuleActionToLoad.']');

			//chargement module/action
			$sClassModule='module_'.$sModuleToLoad;

			$oModule=new $sClassModule;

			if( method_exists($oModule,'_'.$sModuleActionToLoad) ){

				/*LOG*/self::getLog()->info('appel module ['.$sModuleToLoad.'::before]');
				$oModule->before();

				//pre action
				if( method_exists($oModule,'before_'.$sModuleActionToLoad) ){
					/*LOG*/self::getLog()->info('appel module ['.$sModuleToLoad.'::before_'.$sModuleActionToLoad.']');
					$sActionBefore='before_'.$sModuleActionToLoad;
					$oModule->$sActionBefore();
				}

				//debut cache
				if( (int)self::getConfigVar('cache.enabled') == 1 ){

					$sNomPageCache='cache_'.str_replace('::','_',implode('_',self::getRequest()->getParams())).'.html';
					$oFichierCache=new _file(self::getConfigVar('path.cache').$sNomPageCache);

					if(
						( $oFichierCache->exist() and (int)self::getConfigVar('cache.lifetime') == 0 )
						or
						( $oFichierCache->exist() and time()-$oFichierCache->filemtime() < (int)self::getConfigVar('cache.lifetime') )
					){
						/*LOG*/self::getLog()->info('utilisation page en cache ['.$sNomPageCache.']');
						echo $oFichierCache->getContent();
						return;
					}

					ob_start();
				}
				$sAction='_'.$sModuleActionToLoad;

				/*LOG*/self::getLog()->info('appel module ['.$sModuleToLoad.'::'.$sAction.']');
				$oModule->$sAction();

				//post action
				if( method_exists($oModule,'after_'.$sModuleActionToLoad) ){
					/*LOG*/self::getLog()->info('appel module ['.$sModuleToLoad.'::after_'.$sModuleActionToLoad.']');

					$sActionAfter=	'after_'.$sModuleActionToLoad;
					$oModule->$sActionAfter();
				}

				//post module
				/*LOG*/self::getLog()->info('appel module ['.$sModuleToLoad.'::after]');
				$oModule->after();

				//fin cache
				if( (int)self::getConfigVar('cache.enabled')== 1 ){

					$sSortie=ob_get_contents();
					ob_end_clean();

					$oFichierCache->write($sSortie."\n<!--cache -->");

					echo $sSortie;
				}
			}
			else{
				$tErreur=array(
					'Erreur dans module/'.$sModuleToLoad.'/main.php',
					'Pas de m&eacute;thode _'.$sModuleActionToLoad.'() dans le module "'.$sModuleToLoad.'" &agrave; charger',
					'Note: vous pouvez modifier le couple module/action par defaut ',
					'en modifiant la section [navigation] dans le fichier conf/site.ini.php',
				);
				throw new Exception(implode("\n",$tErreur));
			}

		}catch(Exception $e){
			self::erreurLog($e->getMessage()."\n".self::showException($e),$e);
		}

	}

	public static function showException(Exception $e) {
		$tTrace = $e->getTrace();
		$result=$e->getTraceAsString();


		$result.="\n\nDetail:\n";

		foreach($tTrace as $i=> $trace){
			$result.='#'.$i.' ';
			if(isset($trace['file'])){$result.=$trace['file'];}
			if(isset($trace['line'])){$result.=' ('.$trace['line'].') '."\n";}
			$result.=' ';

			if(isset($trace['class'])){
				$result.=$trace['class'].' '.$trace['type'].' '.$trace['function'].'( ';
			}else{
				$result.=$trace['function'].'( ';

			}

			if(isset($trace['args']) and is_array($trace['args'])){

				foreach($trace['args'] as $j => $arg){

					if($j>0){ $result.=' , ';}

					if(is_array($arg)){
						$result.=preg_replace('/\n|\r/',' ',
							print_r($arg,1)
						);
					}else{
						if(is_null($arg)){ $result.='NULL';}

						if(is_string($arg)){
							$result.="'$arg'";
						}
					}

				}

			}
			$result.=' ) '."\n";

		}
		$result.='#'.($i+1).' {main}';


		return $result;
	}

	private function loadAutoload(){
		if((int)self::getConfigVar('cache.autoload.enabled')==1){
			$sCacheFilename=self::getConfigVar('path.cache').'autoload.php';
			if(file_exists($sCacheFilename)){
				include $sCacheFilename;
			}else{
				//on creer un tableau associatif de tous les path des classes
				$tDir=array(
					'lib' => self::getConfigVar('path.lib'),
					'abstract' => self::getConfigVar('path.lib').'abstract/',
					'sgbd' => self::getConfigVar('path.lib').'sgbd/',
					'sgbd_pdo' => self::getConfigVar('path.lib').'sgbd/pdo/',
					'sgbd_syntax' => self::getConfigVar('path.lib').'sgbd/syntax/',
					'plugin' => self::getConfigVar('path.plugin'),
					'model' => self::getConfigVar('path.model'),
					'module' => self::getConfigVar('path.module'),
				);

				$tAutoload=array();

				foreach($tDir as $sType => $sDir){

					if(in_array($sType,array(
									'lib',
									'abstract',
									'sgbd',
									'sgbd_pdo',
									'sgbd_syntax',
									'plugin',
									'model',
					))){

						$oDir=new _dir($sDir);

						$tFile=$oDir->getListFile();
						foreach($tFile as $oFile){
							$sFilename=$oFile->getName();
							$tFilename=preg_split('/_/',$sFilename);
							if($sType=='lib'){
								$tAutoload[ '_'.substr($tFilename[1],0,-4) ]=$sDir.$sFilename;
							}else{
								$tAutoload[ substr($sFilename,0,-4) ]=$sDir.$sFilename;
							}

						}
					}else if($sType=='module'){
						$oDir=new _dir($sDir);

						$tModuleDir=$oDir->getListDir();
						foreach($tModuleDir as $oModuleDir){
							$sModuleDirname=$oModuleDir->getName();
							$tAutoload['module_'.$sModuleDirname]=$sDir.$sModuleDirname.'/main.php';
						}
					}
				}

				$sCodeCache='<?php _root::$tAutoload='.var_export($tAutoload,true).';';
				file_put_contents($sCacheFilename,$sCodeCache);
				self::$tAutoload=$tAutoload;
			}
		}
	}

	/**
	* appele par le autoload quand il trouve pas une classe (chargement dynamique)
	* @access public static
	* @param string $sClass nom de la classe appellee
	*/
	public static function autoload($sClass){

			$tab=preg_split('/_/',$sClass);
			if(isset(self::$tAutoload[$sClass])){
					include self::$tAutoload[$sClass];
			}else if($sClass[0]=='_'){
				include self::getConfigVar('path.lib').'class'.$sClass.'.php';
			}else if(in_array($tab[0],array('plugin','model','abstract'))){
				include self::getConfigVar('path.'.$tab[0]).$sClass.'.php';
			}else if($tab[0]=='module'){
				include self::getConfigVar('path.module').substr($sClass,7).'/main.php';
			}else if($tab[0]=='row'){
				include self::getConfigVar('path.model').'model_'.substr($sClass,4).'.php';
			}else if($tab[0]=='sgbd' and in_array($tab[1],array('syntax','pdo'))){
				include self::getConfigVar('path.lib').'sgbd/'.$tab[1].'/'.$sClass.'.php';
			}else if($tab[0]=='sgbd'){
				include self::getConfigVar('path.lib').'sgbd/'.$sClass.'.php';
			}else{
				return false;
			}


	}

	/**
	* force une variable de requete GET,POST
	* @acces public
	* @param string $sEnv
	* @param string $sVar
	* @param undefined $uValue
	*/
	public static function setParam($sVar,$uValue){
		self::getRequest()->setParam($sVar,$uValue);
	}

	/**
	* retourne une variable de requete GET,POST
	* @acces public
	* @return undefined
	* @param string $sEnv
	* @param string $sVar
	* @param undefined $uElse
	*/
	public static function getParam($sVar,$uElse=null){
		return self::getRequest()->getParam($sVar,$uElse);
	}


	/**
	* force une variable de navigation
	* @access public
	* @param string $sNav
	*/
	public static function setParamNav($sNav){
		self::getRequest()->setParamNav($sNav);
	}

	/**
	* retourne la valeur de navigation
	* @access public
	* @return string navigation (module::action)
	*/
	public static function getParamNav(){
		return self::getRequest()->getParamNav();
	}

	/**
	* retourne le module
	* @access public
	* @return string module
	*/
	public static function getModule(){
		return self::getRequest()->getModule();
	}

	/**
	* retourne l'action
	* @access public
	* @return string action
	*/
	public static function getAction(){
		return self::getRequest()->getAction();
	}


	/**
	* defini le tableau d'environnement a utiliser comme _request
	* @access public
	* @param array $tRequest
	*/
	public static function addRequest($tRequest){
		self::$_tRequestTab[]=$tRequest;
	}

	public static function loadRequest(){
		if(self::$_oRequest==null){
			self::$_oRequest=new _request();
		}
		foreach(self::$_tRequestTab as $tRequest){
			foreach($tRequest as $sVar => $sVal){
				self::getRequest()->setParam($sVar,$sVal);
			}
		}
	}

	/**
	* efface l'objet _request
	* @access public
	*/
	public static function resetRequest(){
		self::$_oRequest=null;
		self::$_tRequestTab=array();
	}

	/**
	* retourne l'objet _request
	* @access public
	* @return _request
	*/
	public static function getRequest(){
		return self::$_oRequest;
	}

	/**
	* retourne l'objet _cache
	* @access public
	* @return _cache
	*/
	public static function getCache(){
		if(self::$_oCache==null){ self::$_oCache=new _cache(); }
		return self::$_oCache;
	}
	/**
	* retourne l'objet _cacheVar
	* @access public
	* @return _cacheVar
	*/
	public static function getCacheVar(){
		if(self::$_oCacheVar==null){ self::$_oCacheVar=new _cacheVar(); }
		return self::$_oCacheVar;
	}
	/**
	* retourne l'objet d'authentification auth.class
	* @access public
	* @return d'authentification auth.class
	*/
	public static function getAuth(){
		if(self::$_oAuth==null){
			$sClassAuth=self::getConfigVar('auth.class');
			self::$_oAuth=new $sClassAuth;
		}
		return self::$_oAuth;
	}

	/**
	* retourne l'objet de gestion de droits auth.acl
	* @access public
	* @return objet de gestion de droits auth.acl
	*/
	public static function getACL(){
		if(self::$_oACL==null){
			$sClassACL=self::getConfigVar('acl.class');
			self::$_oACL=new $sClassACL;
		}
		return self::$_oACL;
	}


	/**
	* retourne l'objet d'urlrewriting urlrewriting.class
	* @access public
	* @return d'urlrewriting urlrewriting.class
	*/
	public static function getUrlRewriting(){
		if(self::$_oUrlrewriting==null){
			$sClassUrlrewriting=self::getConfigVar('urlrewriting.class');
			self::$_oUrlrewriting=new $sClassUrlrewriting;
		}
		return self::$_oUrlrewriting;

	}


	/**
	* retourne l'objet log.class
	* @access public
	* @return $_oLog
	*/
	public static function getLog(){
		if(self::$_oLog==null){
			$sClassLog=self::getConfigVar('log.class');
			if($sClassLog==''){
				$tErreur=array(
					'Il vous manque un bloc dans votre fichier conf/site.ini',
					'[log]',
					'class=plugin_log',
					'application=1',
					'warning=1',
					'error=1',
					'information=1',
				);

				self::erreurLog(implode("\n",$tErreur));
			}
			self::$_oLog=new $sClassLog;
		}
		return self::$_oLog;
	}

	/**
	* retourne l'objet i18n.class
	* @access public
	* @return $_oI18n
	*/
	public static function getI18n(){
		if(self::$_oI18n==null){
			$sClassI18n=self::getConfigVar('language.class');
			if($sClassI18n==''){
				$tErreur=array(
					'Il vous manque un bloc dans votre fichier conf/site.ini',
					'[language]',
					'class=plugin_sc_i18n',

				);

				self::erreurLog(implode("\n",$tErreur));
			}
			self::$_oI18n=new $sClassI18n;
		}
		return self::$_oI18n;
	}


	/**
	* defini une variable "global a l'application"
	* @access public static
	* @param string $sVar variable a definir
	* @param string $sValue valeur
	*/
	public static function setConfigVar($sCatAndVar,$uValue){
	      if(preg_match('/\./',$sCatAndVar)){
			list($sCategory,$sVar)=preg_split('/\./',$sCatAndVar,2);
			self::$tConfigVar[$sCategory][$sVar]=$uValue;
	      }else{
			self::$tConfigVar[$sCatAndVar]=$uValue;
	      }
	}


	/**
	* retourne la variable $sVar "global a l'application" si elle existe, sinon $defaut
	* @access public static
	* @return undefined
	* @param string $sVar variable a definir
	* @param undefined $uDefaut retour en cas d'echec
	*/
	public static function getConfigVar($sCatAndVar,$uDefaut=null){

		if(preg_match('/\./',$sCatAndVar)){
			list($sCategory,$sVar)=preg_split('/\./',$sCatAndVar,2);

			if(in_array($sVar,array('sgbd','abstract','sgbd_pdo','sgbd_syntax'))){
				if(preg_match('/_/',$sVar)){
					$sVar=preg_replace('/_/','/',$sVar);
				}
				return self::$tConfigVar['path']['lib'].$sVar.'/';
			}
			else if(isset(self::$tConfigVar[$sCategory][$sVar])){
				return self::$tConfigVar[$sCategory][$sVar];
			}

		}else if(isset(self::$tConfigVar[$sCatAndVar])){
			return self::$tConfigVar[$sCatAndVar];
		}
		return $uDefaut;
	}

	/**
	* defini une variable "global a l'application"
	* @access public static
	* @param string $sVar variable a definir
	* @param string $sValue valeur
	*/
	public static function setGlobalVar($sVar,$sValue){
		self::$_tGlobalVar[$sVar]=$sValue;
	}
	/**
	* retourne la variable $sVar "global a l'application" si elle existe, sinon $defaut
	* @access public static
	* @return undefined
	* @param string $sVar variable a definir
	* @param string $defaut retour en cas d'echec
	*/
	public static function getGlobalVar($sVar,$defaut=null){
		if(isset(self::$_tGlobalVar[$sVar])){
			return self::$_tGlobalVar[$sVar];
		}
		return $defaut;
	}

	/**
	* defini un objet "global a l'application"
	* @access public static
	* @param string $sVar variable a definir
	* @param string $sValue valeur
	*/
	public static function setInstanceOf($sObj,$oObj){
		self::$_tObject[$sObj]=$oObj;
	}
	/**
	* retourne un objet "global a l'application" si elle existe, sinon $defaut
	* @access public static
	* @return undefined
	* @param string $sVar variable a definir
	* @param string $defaut retour en cas d'echec
	*/
	public static function getObject($sObj,$defaut=null){
		if(isset(self::$_tObject[$sObj])){
			return self::$_tObject[$sObj];
		}
		return $defaut;
	}

	/**
	* redirige vers le couple module::action $sNav
	* @access public static
	* @param string ou array $uNav (dans le cas de l'array, l'indice 0 c'est le couple controller/action)
	* @param array $tParam tableau de parametres de l'url
	*/
	public static function redirect($uNav,$tParam=null){
		/*LOG*/self::getLog()->info('redirection ['.self::getLink($uNav,$tParam,false).']');
		header('Location:'.self::getLink($uNav,$tParam,false));
		exit(0);
	}

	/**
	* retourne le lien framework en reprenant les parametres actuels
	* @access public static
	* @param array $tParam les parametres supplementaires de l'url
	* @param bool $bAmp utilise ou non &amp; dans les liens (passer a false dans le cas d'un header location)
	*/
	public static function getLinkWithCurrent($tParam=null,$bAmp=true){
		$tOriginParam=self::getRequest()->getParamsGET();
		unset($tOriginParam[ _root::getConfigVar('navigation.var') ] );

		$tNewParam=array_merge($tOriginParam,$tParam);
		$sNav=self::getParamNav();

		return self::getLink($sNav,$tNewParam,$bAmp);
	}

	/**
	* retourne le lien framework
	* @access public static
	* @param string ou array $uNav (dans le cas de l'array, l'indice 0 c'est le couple controller/action)
	* @param array $tParam les parametres de l'url
	* @param bool $bAmp utilise ou non &amp; dans les liens (passer a false dans le cas d'un header location)
	*/
	public static function getLink($uNav,$tParam=null,$bAmp=true){

		if(is_array($uNav)){
			$sNav=$uNav[0];
			unset($uNav[0]);
			$tParam=$uNav;
		}else{
			$sNav=$uNav;
		}

		if( (int)self::getConfigVar('urlrewriting.enabled') ==1 ){
			return self::getUrlRewriting()->getLink($sNav,$tParam);
		}else{
			return self::getLinkString($sNav,$tParam,$bAmp);
		}
	}
	/**
	* retourne le lien framework
	* @access public static
	* @param string $sLink
	* @param bool $bAmp utilise ou non &amp; dans les liens (passer a false dans le cas d'un header location)
	*/
	public static function getLinkString($sNav,$tParam=null,$bAmp=true){

		$sLink='';
		if(is_array($tParam)){
			foreach($tParam as $sKey => $sVal){
				if($sKey=='#'){
					continue;
				}else if($bAmp){
					$sLink.='&amp;';
				}else{
					$sLink.='&';
				}
				$sLink.=$sKey.'='.$sVal;
			}
			if(isset($tParam['#'])){
				$sLink.='#'.$tParam['#'];
			}
		}

		return self::getConfigVar('navigation.scriptname').'?'.self::getConfigVar('navigation.var').'='.$sNav.$sLink;

	}

	public static function erreurLog($sText,$e=null){
		if(self::getConfigVar('site.mode')=='dev'){

			if(self::getConfigVar('debug.class')){
				$sClass=self::getConfigVar('debug.class');
				$oDebug=new $sClass;
				$oDebug->show($sText,$e);
			}else{
				$sText=nl2br($sText);
				include self::getConfigVar('path.layout').'erreur.php';
			}

		}else{
			include self::getConfigVar('navigation.layout.erreur','../layout/erreurprod.php');
			try{
				if(self::getConfigVar('log.apache.enabled',1)==1){
					error_log('[quiet error]:'.$sText);
				}
				if(self::getConfigVar('log.file.enabled',1)==1){
					$open=fopen(self::getConfigVar('path.log','data/log').date('Y-m-d').'.txt','a+');
					fputs($open,$sText);
					fclose($open);
				}

			}catch(Exception $e){
				//en mode production, on est muet
			}
		}
	}



}

function stripslashes_deep($value){
	if(is_array($value)){
		return array_map('stripslashes_deep', $value);
	}else{
		return stripslashes($value);
	}
}
function customHtmlentities($string){
	if(is_array($string)){ return array_map('customHtmlentities',$string) ;}
	return _root::nullbyteprotect(htmlentities(
									$string,
									ENT_QUOTES,
									_root::getConfigVar('encodage.charset'),
									_root::getConfigVar('encodage.double_encode',1)));
}
