<?php $oPluginHtml=new plugin_html?>
<form action="<?php echo $this->getLink('auteur::save',array(
															'id' => $this->oAuteur->getId()
														) 
											)?>" method="POST">
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oAuteur->$sColumn ?>" />
<?php endforeach;?>	
<table>
	
	<tr>
		<th>nom</th>
		<td><input name="nom" value="<?php echo $this->oAuteur->nom ?>" /></td>
	</tr>

	<tr>
		<th>prenom</th>
		<td><input name="prenom" value="<?php echo $this->oAuteur->prenom ?>" /></td>
	</tr>

</table>
<input type="submit" value="Modifier" />
</form>
