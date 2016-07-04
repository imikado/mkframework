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
* plugin_gestionuser classe de gestion user
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_debug{
	
	private $iStartMicrotime;
	private $sHtml;
	
	private static $tSpy;
	private static $tTime;
	private static $tTimeById;

	public static $enabled=0;
	
	public function __construct($sMicrotime){

		if(!self::$enabled){ return; }
		
		$this->iStartMicrotime=self::microtime($sMicrotime);
		
		$iEndTime=self::microtime();
		self::$tTime[]=array('End',$iEndTime);
		
		$iDiff=($iEndTime-$this->iStartMicrotime);
		
		$this->add('Time',sprintf('%0.3f',$iDiff).'s');
		
		$this->addComplexTimes('times',self::$tTime);
		
		$this->addComplex('$_GET',print_r($_GET,1));
		
		if(isset($_POST)){
			$this->addComplex('$_POST',print_r($_POST,1));
		}
		
		if(isset($_SESSION)){
			$this->addComplex('$_SESSION',print_r($_SESSION,1));
		}
		
		if(isset($_SERVER)){
			$this->addComplex('$_SERVER',print_r($_SERVER,1));
		}
		
		$oRequest=_root::getRequest();
		
		$this->add('Module',$oRequest->getModule());
		$this->add('Action',$oRequest->getAction());
		
		$oFileLog=new _file(_root::getConfigVar('path.log','data/log/').date('Y-m-d').'_log.csv');
		if($oFileLog->exist()){ 
			$oFileLog->load();
			$sContentLog=$oFileLog->getContent();
			$this->addFileLog('File log',$sContentLog);
		}
		
		$sVarIniConfig=_root::getConfigVar('model.ini.var','db');
		$tClassSgbd=_root::getConfigVar($sVarIniConfig);
		$this->addComplexIni('Connexions',array($sVarIniConfig=>$tClassSgbd));
		
		$tConfigSection=array(
			'path' ,
			'cache' ,
			'language',
			'auth',
			'acl',
			'navigation',
			'urlrewriting',
			'security',
			'log',
			'check',
			'path',
			'model',
		);
		$tConfig=array();
		foreach($tConfigSection as $sSection){
			$tConfig[$sSection]=_root::getConfigVar($sSection);
		}
		
		$this->addComplexIni('Config',$tConfig);
		
		if(self::$tSpy){
			$this->addComplexSpy('Spy variables',self::$tSpy);
		}

		$tSessionSpy=self::getListSessionSpy();
		if($tSessionSpy){
			$this->addComplexSpy('Spy Session variables',$tSessionSpy);
		}
		
		$this->addAcl();
	}
	
	/** ajoute dans la barre de debug l'affichage d'une variable (tableau,objet..)
	* @access public
	* @return void
	* @param string $uLabel nom de la variable a afficher
	* @param mixte $uVar la variable a afficher dans la barre	
	*/
	public static function addSpy($uLabel,$uVar){
		if(!self::$enabled){ return; }
		self::$tSpy[][$uLabel]=$uVar;
	}

	public static function getIpHash(){
		return sha1($_SERVER['REMOTE_ADDR']);
	}
	public static function getSessionSpyVarFilename(){
		$sIP=self::getIpHash();
		return _root::getConfigVar('path.log','data/log/').'spyVar'.$sIP;
	}

	public static function addSessionSpy($uLabel,$uVar){
		if(!self::$enabled){ return; }
		
		$sFilename=self::getSessionSpyVarFilename();

		$tSpy=array();
		if(file_exists($sFilename)){
			$tSpy=unserialize(file_get_contents( $sFilename ));
		}

		$tSpy[][$uLabel]=$uVar;

		file_put_contents($sFilename, serialize($tSpy));
	}
	public static function getListSessionSpy(){
		$sFilename=self::getSessionSpyVarFilename();
		$tSpy=array();
		if(file_exists($sFilename)){
			$tSpy=unserialize(file_get_contents( $sFilename ));
		}

		file_put_contents($sFilename, null);

		return $tSpy;
	}
	
	public static function getListSpy(){
		return self::$tSpy;
	}
	
	/** ajoute un chrono
	* @access public
	* @return void
	* @param string $uLabel nom du chrono
	*/
	public static function addChrono($uLabel){
		if(!self::$enabled){ return; }
		$iTime=self::microtime();
		self::$tTime[]=array($uLabel,$iTime);
	}
	/** demarre un chrono (pour chronometre le temps d'un point A a un point B)
	* @access public
	* @return void
	* @param string $uLabel nom du chrono
	*/
	public static function startChrono($uLabel){
		if(!self::$enabled){ return; }
		$iTime=self::microtime();
		self::$tTimeById[$uLabel]['start']=$iTime;
	}
	/** arrete le chrono de l'id passe (pour chronometre le temps d'un point A a un point B)
	* @access public
	* @return void
	* @param string $uLabel nom du chrono (qui doit etre le meme que le chrono demarre)
	*/
	public static function stopChrono($uLabel){
		if(!self::$enabled){ return; }
		$iTime=self::microtime();
		self::$tTimeById[$uLabel]['end']=$iTime;
	}
	
	
	
	
	public function display(){
		
		echo '<script>
		var activePopup=\'\';
		function openPopupDebug(id){
			if(id!=activePopup){
				closePopup();
			}

			var a=getById(id);
			if(!a){
			}else if(a.style.display=="none"){
				a.style.display="block";
				activePopup=id;
			}else if(a.style.display=="block"){
				a.style.display="none";
				activePopup=id;
			}
		}
		function closePopup(){
			if(activePopup){
				var b=getById(activePopup);
				if(b){
					b.style.display="none";
				}
			}
		}
		function showHideDebugBar(){
			var a=getById(\'debugBar\');
			if(a){
				btnName="Masquer";

				if(a.style.display==\'none\'){
					a.style.display=\'block\';
					var b=getById(\'debugBtn\');
					if(b){
						b.style.width=\'100%\';
					}
				}else{
					a.style.display=\'none\';
					var b=getById(\'debugBtn\');
					if(b){
						b.style.width=\'auto\';
					}
					btnName=">>";
				}
				
				var c=getById(\'btnHidebar\');
				if(c){
					c.value=btnName;
				}
			}
		}
		</script>';
		echo '<div id="debugBtn" ';
		echo 'style="position:fixed;border:2px solid #444;background:#ddd;bottom:0px;left:0px;width:100%">';
		echo '<div  style="float:left"><input id="btnHidebar" type="button" value="Masquer" onclick="showHideDebugBar()"/></div>';
		echo '<div id="debugBar" style="width:100%">';
		echo $this->sHtml;
		echo '</div>';
		echo '</div>';
	}
	
	private function addComplex($key,$value){
		$this->addHtml('<input type="button" value="'.$key.'" onclick="openPopupDebug(\'popupDebug'.$key.'\')" />');
		$this->addSep();
		
		$this->addPopupPrintr($key,$value);
	}
	private function addComplexTimes($key,$value){
		$this->addHtml('<input type="button" value="'.$key.'" onclick="openPopupDebug(\'popupDebug'.$key.'\')" />');
		$this->addSep();
		
		$value=$this->parseTime($value);
		
		$this->addPopup($key,$value);
	}
	private function addComplexIni($key,$value){
		$this->addHtml('<input type="button" value="'.$key.'" onclick="openPopupDebug(\'popupDebug'.$key.'\')" />');
		$this->addSep();
		
		$value=$this->parseIni($value);
		
		$this->addPopup($key,$value);
	}
	private function addComplexSpy($key,$value){
		$this->addHtml('<input type="button" value="'.$key.'" onclick="openPopupDebug(\'popupDebug'.$key.'\')" />');
		$this->addSep();
		
		$sValue=$this->parseSpy($value);
		
		$this->addPopup($key,$sValue);
	}
	
	private function addFileLog($key,$value){
		$this->addHtml('<input type="button" value="'.$key.'" onclick="openPopupDebug(\'popupDebug'.$key.'\')" />');
		$this->addSep();
		
		$value=$this->parseLog($value);
		
		$this->addPopup($key,$value);
	}
	
	private function add($key,$value){
		$this->addHtml('<strong>'.$key.'</strong>:<span style="padding:2px 4px;background:#fff">'.$value.'</span>');
		$this->addSep();
	}
	
	private function addPopupPrintr($key,$value){
		$this->addHtml(
		'<div id="popupDebug'.$key.'" 
			style="display:none;position:absolute;left:0px;bottom:20px;border:2px solid gray;background:white">
			<p style="text-align:right;background:#ccc;margin:0px;"><a href="#" onclick="closePopup()">Fermer</a></p>
			<div style="height:350px;width:400px;overflow:auto;padding:10px;">
				<pre>'.customHtmlentities(print_r($value,1)).'</pre>
			</div>
		</div>');
	}
	
	private function addPopup($key,$value,$width=800){
		$this->addHtml(
		'<div id="popupDebug'.$key.'" 
			style="display:none;position:absolute;left:0px;bottom:20px;border:2px solid gray;background:white">
			<p style="text-align:right;background:#ccc;margin:0px;"><a href="#" onclick="closePopup()">Fermer</a></p>
			<div style="height:350px;width:'.$width.'px;overflow:auto;padding:10px;">
			'.$value.'
			</div>
		</div>');
	}
	
	private function addAcl(){
		$this->addHtml('<input type="button" value="Permissions" onclick="openPopupDebug(\'popupDebugACL\')" />');
		$this->addSep();
		
		$sHtml='<div style="position:absolute">';
		$sHtml.='<table style="background:white;border-collapse:collapse">
		<tr>
			<th style="border:1px solid gray;">Action</th>
			<th style="border:1px solid gray;">Component</th>
		</tr>';
		$tab=_root::getConfigVar('tAskCan');
		if($tab and is_array($tab)):
		foreach($tab as $tVal):
		$sHtml.='<tr>
			<td style="border:1px solid gray;';
			if($tVal[2]){
				$sHtml.='color:green;'; 
			}else{
				$sHtml.='color:red'; 
			} 
			$sHtml.='">'.$tVal[0].'</td>
			<td style="border:1px solid gray;';
			if($tVal[2]){
				$sHtml.='color:green;'; 
			}else{
				$sHtml.='color:red'; 
			} 
			$sHtml.='">'.$tVal[1].'</td>
			 
		</tr>';
		endforeach;
		endif;
		$sHtml.='</table>'; 
		
		$this->addPopup('ACL', $sHtml,300);
	}
	
	private function addSep(){
		$this->addHtml('&nbsp;&nbsp;&nbsp;&nbsp;');
	}
	
	private function addHtml($sHtml){
		$this->sHtml.=$sHtml;
	}
	
	private function parseLog($value){
		$sep=' | ';
		
		$tLine=explode("\n",$value);
		$sHtml=null;
		 
		$iMax=count($tLine)-1;
		for($i=$iMax;$i>0;$i--){
			$sLine=$tLine[$i];
				
			$tCase=explode(';',$sLine,4);
			$sDate=null;
			if(isset($tCase[0])){ 
				$sDate=$tCase[0]; 
			}
			$sTime=null;
			if(isset($tCase[1])){ 
				$sTime=$tCase[1]; 
			}
			$sType=null;
			if(isset($tCase[2])){ 
				$sType=$tCase[2]; 
			}
			$sLog=null;
			if(isset($tCase[3])){ 
				$sLog=$tCase[3]; 
			}
			
			if($sDate==null){ 
				continue;
			}
			
			$sHtml.='<p style="margin:0px">';
		
				$sHtml.='<span >'.$sDate.'</span> ';
				$sHtml.='<span style="font-weight:bold">'.$sTime.'</span>';
				
				$sHtml.=$sep;
				
				$sHtml.='<span style="color:';
				if($sType=='info'){ $sHtml.='gray';}
				elseif($sType=='log'){ $sHtml.='darkblue';}
				$sHtml.='">'.$sType.'</span>';
				
				$sHtml.=$sep;

				if(substr($sLog,0,3)=='sql'){
					$sHtml.='<span style="color:darkblue"><strong>SQL</strong> '.substr($sLog,3).'</span>';
				}else{
					$sHtml.=$sLog;	
				}
				
				
			
			$sHtml.='</p>';
			
			if(preg_match('/module a appeler/',$sLog)){ 
				$sHtml.='<p>&nbsp;</p>';
			}
			
		}
		return $sHtml;
	}
	
	private function parseSpy($tValue){
		$sHtml=null;
		foreach($tValue as $tDetail){
			foreach($tDetail as $ref => $value){
				$sHtml.='<h2 style="border-bottom:1px solid black">'.$ref.'</h2>';
				$sHtml.='<p><pre>'.customHtmlentities(print_r($value,1)).'</pre></p>';
			}
		}
		
		return $sHtml;
	}
	
	private function parseIni($tValue){
		$sHtml=null;
		foreach($tValue as $sSection => $tDetail){
			$sHtml.='<h2 style="border-bottom:1px solid black">'.$sSection.'</h2>';
			foreach($tDetail as $sKey => $sValue){
				$sHtml.='<p style="margin:0px;margin-left:10px;">';
				$sHtml.='<strong>'.$sKey.'</strong> = <span style="color:darkgreen"> '.$sValue.'</span>';
				$sHtml.='</p>';
			}
		}
		
		return $sHtml;
	}
	private function parseTime($tValue){
		$sHtml=null;
		$iPreviousTime=$this->iStartMicrotime;
		$sPreviousStep='Start';
		foreach($tValue as $tDetail){
			list($sLabel,$iTime)=$tDetail;
			$iDelta=($iTime-$iPreviousTime);
			$sHtml.='<p><strong>'.$sPreviousStep.' &gt;&gt; '.$sLabel.'</strong> : '.sprintf('%0.3f',$iDelta).'s</p>';
			
			$iPreviousTime=$iTime;
			$sPreviousStep=$sLabel;
		}
		
		$sHtml.='<p style="border-top:1px solid gray">';
		$sHtml.='<strong>Total</strong> '.sprintf('%0.3f',($iTime-$this->iStartMicrotime)).'s';
		$sHtml.='</p>';
		
		if(self::$tTimeById){
			
			$sHtml.='<p>&nbsp;</p>';
			
			foreach(self::$tTimeById as $sLabel => $tValue){
				
				if(isset($tValue['end']) and isset($tValue['start'])){
					$iDelta=($tValue['end']-$tValue['start']);
					
					$sHtml.='<p><strong>'.$sLabel.' </strong> : '.sprintf('%0.3f',$iDelta).'s</p>';
				}else{
					$sHtml.='<p><strong>'.$sLabel.' </strong> : <span style="color:red">';
					$sHtml.=' Erreur il manque startChrono ou stopChrono</span></p>';
				}
				
			}
			
		}
		
		return $sHtml;
	}
	
	public static function microtime($sMicrotime=null){		
		return microtime(true);
	}
	
}
if(_root::getConfigVar('site.mode')=='dev'){
	plugin_debug::$enabled=1;
}