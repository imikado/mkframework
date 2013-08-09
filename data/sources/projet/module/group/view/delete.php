<table>
	
	<tr>
		<th>name</th>
		<td><?php echo $this->oGroup->name ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" />
</form>

