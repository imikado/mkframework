<table>
	
	<tr>
		<th>login</th>
		<td><?php echo $this->oAccount->login ?></td>
	</tr>

	<tr>
		<th>pass</th>
		<td><?php echo $this->oAccount->pass ?></td>
	</tr>

	<tr>
		<th>nom</th>
		<td><?php echo $this->oAccount->nom ?></td>
	</tr>

	<tr>
		<th>prenom</th>
		<td><?php echo $this->oAccount->prenom ?></td>
	</tr>

	<tr>
		<th>groupe</th>
		<td><?php echo $this->oAccount->groupe ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" />
</form>

