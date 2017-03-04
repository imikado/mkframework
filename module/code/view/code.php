<?php
$sFileType=null;
$sModule=null;
if(_root::getParam('type')){
		$sFileType=_root::getParam('type');
		if(preg_match('/module::/',$sFileType)){
			list($sFileType,$sFileModule)=preg_split('/::/',$sFileType);
		}
}


$tDoc=array(
'abstract_model'	=>'classabstract__model',
'abstract_module'	=>'classabstract__module',

'_root'	=>'class__root',
'_cache'	=>'class__cache',
'_request'	=>'class__request',
'_layout'	=>'class__layout',
'_view'		=>'class__view',
'_file'		=>'class__file',
'_dir'		=>'class__dir',

'plugin_auth'	=> 'classplugin__auth',
'plugin_check'	=>'classplugin__check',
'plugin_date'	=>'classplugin__date',
'plugin_datetime'	=>'classplugin__datetime',
'plugin_gestionuser'	=>'classplugin__gestionuser',
'plugin_html'	=>'classplugin__html',
'plugin_i18n'	=>'classplugin__i18n',
'plugin_jquery'	=>'classplugin__jquery',
'plugin_log'	=>'classplugin__log',
'plugin_mail'	=>'classplugin__mail',
'plugin_rss'	=>'classplugin__rss',
'plugin_upload'	=>'classplugin__upload',
'plugin_valid'	=>'classplugin__valid',
'plugin_xsrf'	=>'classplugin__xsd',

);

$tLine=$this->oFile->getTab();

$sGoTo=null;
$tColor=array('#fff','#eee');

$tFunction=null;

$sTypeFile=null;

if(preg_match('/conf\//',_root::getParam('file')) and preg_match('/\.ini/',_root::getParam('file'))){

	//$sCode=$this->oFile->getContent();
	$tCode=$tLine;

	$sTypeFile=module_code::$INI;

}else{

	$sCode=highlight_string($this->oFile->getContent(),true);
	$tCode=explode('<br />',$sCode);

	foreach($tLine as $i => $line):
		$iLine=sprintf('%06d',($i+1));
		if(preg_match('/function/',$line) ):
			list($sType,$sMethod)=preg_split('/function/',$line);
			$sMethod=preg_replace('/{/','',$sMethod);
			$tFunction[$sMethod]=array(
							'method' => $sMethod,
							'type' => $sType,
							'line' => $iLine,
						);
		endif;
	endforeach;
}
?>

<script>
window.parent.setTitle('<?php if(_root::getParam('type')):?>[<?php echo _root::getParam('type')?>][<?php echo $this->oFile->getName()?>] <?php endif;?>','<?php echo $this->oFile->getAdresse()?>');
</script>

<?php


if($sTypeFile!=module_code::$INI and $tFunction):?>
<div class="fonctions">
	<?php
	ksort($tFunction);
	foreach($tFunction as $tMethod){

		$iLine=$tMethod['line'];
		$sType=$tMethod['type'];
		$sMethod=$tMethod['method'];
		 ?>
		<a href="#num<?php echo $iLine?>"><i>function</i><?php echo $sMethod?><sup style="color:gray;font-size:9px"><?php echo $sType?></sup></a><br />
		<?php
		if( _root::getParam('method') and preg_match('/'._root::getParam('method').'\(/',$sMethod)){
			$gotoLine=$iLine;
			$gotoLine-=4;
			$gotoLine=sprintf('%06d',$gotoLine);
			if($gotoLine < 0){ $gotoLine=0; }
			$sGoTo='document.location.href="#num'.$gotoLine.'";colorLine(\''.$iLine.'\');';
		}

	}?>
</div>
<?php endif;?>

<p style="text-align:center"><a class="btn" href="<?php echo _root::getLink('code::editfullcode',array('project'=>_root::getParam('project'),'file'=>_root::getParam('file')))?>">EDITER EN ENTIER</a></p>

<?php foreach($tCode as $i=>$sCode):?>

	<?php if($sTypeFile==module_code::$INI and $i==0){ continue; };?>

	<?php if(!isset($tLine[$i])){ break; }?>
	<?php $sCodeOriginal=$tLine[$i];?>
	<?php $iLine=sprintf('%06d',($i+1));

	if($i%2){ $j=1; }else{$j=0;}

	if($sTypeFile==module_code::$INI){
		$sCode=htmlentities($sCode);
	}

	foreach($tDoc as $sDoc => $sClassDoc){
		if(preg_match('/'.$sDoc.'/',$sCode)){
				$sCode=preg_replace('/'.$sDoc.'/','<a class="helpDoc" href="#" onclick="help(\''.$sClassDoc.'\');return false;">'.$sDoc.'</a>',$sCode);

				if($sDoc=='_view'){
					preg_match('/'.$sDoc.'\(([\w:\'"]*)/',$sCodeOriginal,$tFound);
					if($tFound and isset($tFound[1])){
						$sViewToReplace=$tFound[1];
						$sViewToReplace=preg_replace('/\'/','',$sViewToReplace);
						if(preg_match('/::/',$sViewToReplace)){
							list($sModule,$sView)=preg_split('/::/',$sViewToReplace);
							$sCode=preg_replace('/'.$sViewToReplace.'/','<a class="goto" href="#" onclick="gotofileandtype(\'module/'.$sModule.'/view/'.$sView.'.php\',\'view\');return false;">'.$sViewToReplace.'</a>',$sCode);
						}
					}
				}
		}
	}

	//module
	foreach($this->tModule as $sModule){
		if(!preg_match('/module\/'.$sModule.'\/main/',_root::getParam('file')) and preg_match('/module_'.$sModule.'/',$sCode)){
			$sCode=preg_replace('/module_'.$sModule.'/','<a class="goto" href="#" onclick="gotofileandtype(\'module/'.$sModule.'/main.php\',\'module\');return false;">module_'.$sModule.'</a>',$sCode);
		}
	}

	//model
	foreach($this->tModel as $sModel){
		if(!preg_match('/model\/'.$sModel.'/',_root::getParam('file')) and preg_match('/'.$sModel.'[^\w]/',$sCodeOriginal)){

			$sCode=preg_replace('/'.$sModel.'/','<a class="goto" href="#" onclick="gotofileandtype(\'model/'.$sModel.'.php\',\'model\');return false;">'.$sModel.'</a>',$sCode);

			$sPattern='/'.$sModel.'::getInstance\(\)->([\w\-]*)\(/';
			preg_match($sPattern,$sCodeOriginal,$tFind);
			if(isset($tFind[1])){
				$sMethod=$tFind[1];
				$sCode=preg_replace('/'.$sMethod.'/','<a class="goto" href="#" onclick="gotofileandmethod(\'model/'.$sModel.'.php\',\'model\',\''.$sMethod.'\');return false;">'.$sMethod.'</a>',$sCode);
			}
		}
	}


	if($sTypeFile==module_code::$INI ){
		if(preg_match('/\[/',$sCode) and preg_match('/\]/',$sCode)){
			$sCode='<strong>'.$sCode.'</strong>';

		}elseif($sCode[0]==';'){
			$sCode='<span style="color:orange">'.$sCode.'</span>';
		}else if(preg_match('/=/',$sCode)){
			$tCode=preg_split('/=/',$sCode,2);
			if($tCode[1]!=''){
				$sCode='<span style="color:darkblue">'.$tCode[0].'</span> <span style="color:red">=</span> '.$tCode[1].'';
			}
		}
	}

	?>


	<?php if($sTypeFile==module_code::$INI):?>
		<?php if(preg_match('/=/',$tLine[(int)$iLine-1])):?>
			<a id="num<?php echo $iLine?>" name="num<?php echo $iLine?>"  class="btn" href="#" onclick="editLine('<?php echo $iLine?>');return false;">[EDITER]</a>
		<?php else:?>
			<a id="num<?php echo $iLine?>" href="#" class="btn"  style="color:#ddd">[EDITER]</a>
		<?php endif;?>
	<?php else:?>
		<a id="num<?php echo $iLine?>" name="num<?php echo $iLine?>"  class="btn" href="#" onclick="editLine('<?php echo $iLine?>');return false;">[EDITER]</a>
	<?php endif;?>


	&nbsp;<span style="background:#fff;color:#444"><?php echo $iLine?>&nbsp;&nbsp;</span><?php echo $sCode?><br/>
	<form style="display:none;" id="line<?php echo $iLine?>" method="POST" action="#num<?php echo sprintf('%06d',($iLine-10))?>"><a class="btn" style="color:red" href="#" onclick="closeEditLine()">[FERMER]</a>&nbsp;<span style="background:#fff;color:#eee"><?php echo $iLine?>&nbsp;&nbsp;</span><input type="hidden" name="iLine" value="<?php echo $iLine?>"/>

	<?php if($sTypeFile==module_code::$INI):?>

		<?php if(preg_match('/=/',$tLine[(int)$iLine-1])):?>
			<?php list($var,$val)=preg_split('/=/', $tLine[(int)$iLine-1],2 );?>

			<?php echo $var ?> <input type="hidden" name="content_begin" value="<?php echo $var?>"/> = <textarea rows="1" style="width:280px" name="content_end" ><?php echo $val?></textarea>
		<?php endif;?>

	<?php else:?>
		<textarea style="border:0px;background:#ddd;width:600px;height:30px" name="content" id="content<?php echo $iLine?>" ><?php echo $tLine[(int)$iLine-1]?></textarea>
	<?php endif;?>

	<input type="submit" value="Enregistrer"/>
	<?php if($sFileType=='module'):?>
		<p style="text-align:center">
			<a href="#" onclick="addContent('<?php echo $iLine?>','addAction')">Ajouter une action</a>
			|
			<a href="#" onclick="addContent('<?php echo $iLine?>','addView',Array('<?php echo $sFileModule?>','mavue'))">Appel d'une vue</a>
		</p>
	<?php else:?>

	<?php endif;?>
	</form>
	<hr id="hr<?php echo $iLine?>"/>

<?php endforeach;?>
<?php if($sGoTo!=''):?>
<script><?php echo $sGoTo?></script>
<?php endif;?>
