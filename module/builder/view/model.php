<script>
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
</script>
<?php $tEnable=_root::getParam('tEnable');
$tSelectEnable=_root::getParam('tSelectEnable');
$tSelectKey=_root::getParam('tSelectKey');
$tSelectVal=_root::getParam('tSelectVal');
?>
<div class="smenu">
<h1>Cr&eacute;er la couche mod&egrave;le</h1>
<form action="" method="POST" id="formu">
<p>S&eacute;lectionner le profil &agrave; utiliser</p>
<ul>
<?php foreach($this->tConnexion as $sKey=>$tConfig):?>
	<?php if(substr($sKey,-4)=='.dsn') :?>
		<?php $sConfig=substr($sKey,0,-4)?>
		<?php if(_root::getParam('sConfig') == $sConfig):?>
			<li class="selectionne"><?php echo $sConfig?></li>
		<?php else:?>
			<li><a href="<?php echo _root::getLink('builder::edit',array(
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
<table>
	<tr>
		<th></th>
		<th>Table</th>
		<th>Cl&eacute; primaire</th>

		<th style="border-top:0px;background:white";></th>

		<th style="text-align:left;">Ajouter une m&eacute;thode getSelect()*</th>
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
