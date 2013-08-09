<form action="" method="POST">
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oArticle->$sColumn ?>" />
<?php endforeach;?>	
<table>
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( in_array($sColumn,$this->tId)) continue;?>
	<tr>
		<th><?php echo $sColumn ?></th>
		<td><input name="<?php echo $sColumn ?>" value="<?php echo $this->oArticle->$sColumn ?>" /></td>
	</tr>
<?php endforeach;?>
</table>
<input type="submit" value="Modifier" />
</form>

<?php $oAuteur=$this->oArticle->findAuteur() ?>
<?php if($oAuteur) echo $oAuteur->nom?>
