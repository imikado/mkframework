<form action="" method="POST" id="formu">
<?php
$tCheck=array(
'isNotEmpty' => array('N&apos;est pas vide','Le champ ne doit pas &ecirc;tre vide'),
'isEqual' => array('Est &eacute;gal &agrave;','Le champ ne doit pas &ecirc;tre &eacute;gal &agrave;'),
'isNotEqual' => array('N&apos;est pas &eacute;gal &agrave;','Le champ ne doit pas &eacute;gal &agrave;'),
'isUpperThan' => array('Est sup&eacute;rieur &agrave;','Le champ n&apos;est pas sup&eacute;rieur &agrave;'),
'isLowerThan' => array('Est inf&eacute;rieur a','Le champ n&apos;est pas inf&eacute;rieur &agrave;'),
'isEmpty' => array('Est vide','Le champ n&apos;est pas vide'),
'isEmailValid' => array('Est un email valide','Le champ n&apos;est pas un email valide'),
'matchExpression' => array('Respecte le pattern','Le champ ne respecte pas le pattern'),
'notMatchExpression' => array('Ne respecte pas le pattern','Le champ ne doit pas respecter le pattern'),
);

?><script>
var tRuleName=new Array();
var tRuleMsg=new Array();
<?php foreach($tCheck as $sRule => $tLabel):?>
tRuleName['<?php echo $sRule?>']='<?php echo $tLabel[0]?>';
tRuleMsg['<?php echo $sRule?>']='<?php echo $tLabel[1]?>';

<?php endforeach;?>
	
var bCheckedAll=0;
function deselectAll(){
	var i=0;
	while( getById('tIdEnable['+i+']') ){
		getById('tIdEnable['+i+']').checked=bCheckedAll;i++;
	}

	if(bCheckedAll==0){ 
		bCheckedAll=1;
	}else{
		bCheckedAll=0;
	}
}
<?php foreach($this->tTableColumn as $sTable => $tColumn):?>
function resetCheck<?php echo $sTable?>(){
	
	if(!confirm('Confirmez-vous vouloir effacer les contraintes de cette classe ?')){
		return;
	}
	
	getById('<?php echo $sTable?>_check_content').innerHTML='';
}
function addCheck<?php echo $sTable?>(){
	
	var a=getById('<?php echo $sTable?>_check');
	if(a){
		a.style.display='block';
	}
	
	var sRule=null;
	var b=getById('ruleAdd');
	if(b){
		sRule=b.value;
	}
	
	var sFieldLine='';
	sFieldLine+='<table class="rule">';
		sFieldLine+='<tr>';
			sFieldLine+='<td>';
			
				sFieldLine+='Le champ ';
				
			sFieldLine+='</td>';
			sFieldLine+='<td>';
			
				sFieldLine+='<select name="tRuleColumn<?php echo $sTable?>[]">';
					sFieldLine+='<option value=""></option>';
					<?php foreach($tColumn as $sColumn):?>
					sFieldLine+='<option value="<?php echo $sColumn?>"><?php echo $sColumn?></option>';
					<?php endforeach;?>
				sFieldLine+='</select>';
			sFieldLine+='</td>';
			
			sFieldLine+='<td>';
			
				sFieldLine+='<input name="tRuleName<?php echo $sTable?>[]" type="hidden" value="'+sRule+'" />';
				sFieldLine+=tRuleName[sRule];
			
			sFieldLine+='</td>';
			
			sFieldLine+='<td>';
			if(sRule!='isNotEmpty' && sRule!='isEmpty' && sRule!='isEmailValid'){
				sFieldLine+='<input size="3" name="tRuleParam<?php echo $sTable?>[]" type="text" />';
			}else{
				sFieldLine+='<input name="tRuleParam<?php echo $sTable?>[]" type="hidden" />';
			}
			sFieldLine+='</td>';	
			
			sFieldLine+='<td>Message:</td>';
				
			sFieldLine+='<td>';
			
				sFieldLine+='<input name="tRuleMsg<?php echo $sTable?>[]" type="text" value="'+tRuleMsg[sRule]+'" />';
			
			sFieldLine+='</td>';
			
			
		sFieldLine+='<tr>';
	sFieldLine+='</table>';
	
	var tmpDiv=document.createElement('div');
	tmpDiv.innerHTML=sFieldLine;

	getById('<?php echo $sTable?>_check_content').appendChild(tmpDiv);
}
function hideCheck<?php echo $sTable?>(){
	
	var a=getById('<?php echo $sTable?>_check');
	if(a){
		a.style.display='none';
	}
	
}
function showCheck<?php echo $sTable?>(){
	
	var a=getById('<?php echo $sTable?>_check');
	if(a){
		a.style.display='block';
	}
	
}
<?php endforeach;?>
</script>
<style>
.popup{
	 display:none;
	 position:absolute;
	 border:3px solid #333;
	 background:white;
}
.popup .content{
	padding:10px;
}
.popup hr{
	border:1px solid #333;
}
.rule td{
	border:0px;
}
.effacer{
	text-align:right;
	margin:0px;
	padding:0px;
}
.effacer input{
	margin-top:10px;
	background:darkred;
	color:white;
	border:1px solid #333;
}
.fermer{
	background:#333;
	margin:0px;
	text-align:right;
}
.fermer a{
	color:white;
	text-decoration:none;
}
</style>
<?php $tEnable=_root::getParam('tEnable');
$tSelectEnable=_root::getParam('tSelectEnable');
$tSelectKey=_root::getParam('tSelectKey');
$tSelectVal=_root::getParam('tSelectVal');
?>
<div class="smenu">
<h1>Cr&eacute;er la couche mod&egrave;le</h1>

<p>S&eacute;lectionner le profil &agrave; utiliser</p>
<ul>
<?php foreach($this->tConnexion as $sKey=>$tConfig):?>
	<?php if(substr($sKey,-4)=='.dsn') :?>
		<?php $sConfig=substr($sKey,0,-4)?>
		<?php if(_root::getParam('sConfig') == $sConfig):?>
			<li class="selectionne"><?php echo $sConfig?></li>
		<?php else:?>
			<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
								'id' => _root::getParam('id'),
								'action' => _root::getParam('action'),
								'sConfig' => $sConfig
								)
			)?>#editmodel"><?php echo $sConfig?></a></li>
		<?php endif;?>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
<a id="editmodel" name="editmodel"></a>
<div class="table">
<?php $bErrorExist=0?>
<?php if($this->tTableColumn):?>


<?php if(preg_match('/mysql/',$this->tConnexion[ _root::getParam('sConfig').'.sgbd' ])):?>
<p><input type="checkbox" name="mysqlOnDuplicateKey" value="1"/> Utiliser "ON DUPLICATE KEY" (uniquement pour les bases mysql) </p>
<?php endif;?>

<table>
	<tr>
		<th></th>
		<th>Table</th>
		<th>Cl&eacute; primaire</th>

		<th style="border-top:0px;background:white";></th>

		<th style="text-align:left;">Ajouter une m&eacute;thode getSelect()*</th>
		
		<th style="border-top:0px;background:white";></th>
		
		<th>Contraintes</th>
	</tr>
<?php $i=0?>
<?php foreach($this->tTableColumn as $sTable => $tColumn):?>
	<?php $bExist=0;
	if(!is_array($tEnable) and file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.'model_'.$sTable.'.php')):
		$bExist=1;$bErrorExist=1;
	endif;?>

	<tr>
		<td><input type="checkbox" id="tIdEnable[<?php echo $i?>]" name="tEnable[<?php echo $i?>]" value="<?php echo $sTable?>" <?php if($bExist):?><?php elseif(!is_array($tEnable) ):?>checked="checked"<?php elseif(in_array($sTable,$tEnable)):?>checked="checked"<?php endif;?> /></td>
		<td><?php echo $sTable?>
			<?php if($bExist):?>
				<p class="error">La classe "model_<?php echo $sTable?>.php" existe d&eacute;j&agrave;*</p>
			<?php endif;?>
			<input type="hidden" name="tTable[<?php echo $i?>]" value="<?php echo $sTable?>"/></td>
		<td><select name="tPrimary[<?php echo $i?>]">
			<?php foreach($tColumn as $sColumn):?>
				<option value="<?php echo $sColumn?>" ><?php echo $sColumn?></option>
			<?php endforeach;?>
		</select>
		</td>

		<td></td>

		<td>
			<input type="checkbox" name="tSelectEnable[<?php echo $i?>]" value="<?php echo $sTable?>" <?php if(is_array($tSelectEnable) and in_array($sTable,$tSelectEnable)):?>checked="checked"<?php endif;?>/>
			Retourne un tableau avec 
			<ul class="getSelect">
				<li><select name="tSelectKey[<?php echo $i?>]">
				<?php foreach($tColumn as $sColumn):?>
					<option value="<?php echo $sColumn?>" <?php if(is_array($tSelectKey) and isset($tSelectKey[$i]) and $tSelectKey[$i]==$sColumn):?>selected="selected"<?php endif;?> ><?php echo $sColumn?></option>
				<?php endforeach;?>
				</select>
			comme cl&eacute;<li>
			<li> et</li>
			<li> 
			<select name="tSelectVal[<?php echo $i?>]">
				<?php foreach($tColumn as $sColumn):?>
					<option value="<?php echo $sColumn?>" <?php if(is_array($tSelectKey) and isset($tSelectVal[$i]) and $tSelectVal[$i]==$sColumn):?>selected="selected"<?php endif;?> ><?php echo $sColumn?></option>
				<?php endforeach;?>
			</select> comme valeur
			</li>
			</ul>
		</td>
		
		<td></td>
			
		<td>
			<div class="popup" id="<?php echo $sTable?>_check">
				<p class="fermer"><a href="#" onclick="hideCheck<?php echo $sTable?>();return false;">Masquer</a></p>
				<div class="content">
					<div  id="<?php echo $sTable?>_check_content"></div>
					
					<hr/>
					<input onclick="addCheck<?php echo $sTable?>()" type="button" value="Ajouter"/>
					r&egrave;gle
					<select id="ruleAdd">
					<?php foreach($tCheck as $sCheck => $tLabel):?>
						<?php $sLabel=$tLabel[0];?>
						<option value="<?php echo $sCheck?>"><?php echo $sLabel?></option>
					<?php endforeach;?>
					</select>
					
					
					<p class="effacer">
					<input onclick="resetCheck<?php echo $sTable?>()" type="button" value="Effacer"/></p>
				</div>
			</div>
			<input onclick="showCheck<?php echo $sTable?>()" type="button" value="Afficher"/>
			
		</td>
		
		
	</tr>
	<?php $i++;?>
<?php endforeach;?>
</table>
<input type="button" value="Tout (d&eacute;)s&eacute;l&eacute;ctionner" onclick="deselectAll()"/>
<input type="submit" value="G&eacute;n&eacute;rer" />
</div>
<?php endif;?>

<p> (disponible dans le fichier conf/connexion.ini.php de votre projet)</p>	
<p>* La m&eacute;thode getSelect() permet de retourner un tableau index&eacute; utilis&eacute; pour les menus d&eacute;roulant et les tableaux de liste.</p>
<?php if($bErrorExist):?>
<p class="error">* Si une classe mod&egrave;le existe d&eacute;j&agrave;, il vous faut la supprimer pour pouvoir la reg&eacute;n&eacute;rer</p>
<?php endif;?>

</form>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
