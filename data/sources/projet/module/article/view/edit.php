<?php 
$oForm=new plugin_form($this->oArticle);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST">
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oArticle->$sColumn ?>" />
	<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
<?php endforeach;?>	
<table>
	
	<tr>
		<th>titre</th>
		<td><?php echo $oForm->getInputText('titre')?></td>
	</tr>

	<tr>
		<th>auteur_id</th>
		<td><?php echo $oForm->getSelect('auteur_id',$this->tJoinAuteur)?></td>
	</tr>

	<tr>
		<th>priority</th>
		<td><?php echo $oForm->getInputText('priority')?></td>
	</tr>

</table>

<?php echo $oForm->getToken('token',$this->token)?>

<input type="submit" value="Modifier" />
</form>

