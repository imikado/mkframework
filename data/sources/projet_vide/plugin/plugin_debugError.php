<?php
class plugin_debugError{
	
	
	public function show($sText,$e=null){
		
		if(ob_get_length()){
			ob_end_clean();
		}
		
		$sText=nl2br($sText);
		
		if($e){
			
			
			$sCode=highlight_string( file_get_contents($e->getFile()),true);
			$tFile=explode('<br />',$sCode);

				
			$iLine=$e->getLine()-1;
			
			$iLineCurrent=$iLine-1;
			
			$sCode=null;
			
			$sCode.='<p><strong>Fichier :</strong> '.$e->getFile().' <strong>ligne</strong> '.$e->getLine().'</p>';
			$sCode.='<p><strong>Message : </strong> '.$e->getMessage().'</p>';
			
			$sCode.='<style>
			.code{ 
			background:#eee;border-collapse:collapse;border:2px solid #777;
			} 
			.code td.line{ 
			text-align:right;color:#777; 
			} 
			.code .selected, .code .selected span{
			background:#fdbfbf;
			}
			 
			</style>';
			
			$sCode.='<table class="code">';
			
				for($i=-18;$i<18;$i++){
					
					$iLineCurrent=$iLine+$i;
					if(!isset($tFile[$iLineCurrent])){
						continue;
					}
					
					$class=null;
					if($i==0){
						$class='selected';
					}
					
					$sCodeLine=$tFile[$iLineCurrent];
					
					$sCodeLine=preg_replace('/\t/','&nbsp;&nbsp;&nbsp;&nbsp;',$sCodeLine);
			
					$sCode.='<tr>';
						$sCode.='<td class="line '.$class.'" >'.($iLineCurrent+1).'</td>';
						$sCode.='<td class="'.$class.'">'.$sCodeLine.'</td>';
					$sCode.='</tr>';
					
				} 
				
			$sCode.='</table>';
			
			$sText.='<hr/>'.$sCode;
			
			$sText.='<hr/>';
			$sText.='<h2>Navigation</h2>';
			$oRequest=_root::getRequest();
			$sText.='<strong>Module:</strong>'.$oRequest->getModule();
			$sText.=' ';
			$sText.='<strong>Action:</strong>'.$oRequest->getAction();
			
			
			$sText.='<h2>$_GET</h2>';
			$sText.=nl2br(print_r($_GET,true));
			$sText.='<h2>$_POST</h2>';
			$sText.=nl2br(print_r($_POST,true));
			if(isset($_SESSION)){
				$sText.='<h2>$_SESSION</h2>';
				$sText.=nl2br(print_r($_SESSION,true));
			}
			if(isset($_SERVER)){
				$sText.='<h2>$_SERVER</h2>';
				$sText.=nl2br(print_r($_SERVER,true));
			}
			
			$tSpy=plugin_debug::getListSpy();
			if($tSpy){
				$sText.='<h2>Spy variables</h2>';
				foreach($tSpy as $tDetail){
					foreach($tDetail as $ref => $value){
						$sText.='<h3 style="margin-left:20px;border-bottom:1px solid black">'.$ref.'</h3>';
						$sText.='<div style="padding-left:20px"><pre>'.customHtmlentities(print_r($value,1)).'</pre></div>';
					}
				}
				 
			}
			
		}
		
		echo '<html><head><style>*{ font-family:arial;font-size:12px;}</style></head></html>';
		echo $sText;
		
		
		exit;
		
	}
	 
	
}
