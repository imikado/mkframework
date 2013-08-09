<table>
	
	<tr>
		<th>group</th>
		<td><?php echo $this->oPermission->group ?></td>
	</tr>

	<tr>
		<th>action</th>
		<td><?php echo $this->oPermission->action ?></td>
	</tr>

	<tr>
		<th>element</th>
		<td><?php echo $this->oPermission->element ?></td>
	</tr>

	<tr>
		<th>allowdeny</th>
		<td><?php echo $this->oPermission->allowdeny ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" />
</form>

