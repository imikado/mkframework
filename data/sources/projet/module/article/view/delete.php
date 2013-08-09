<table>
	
	<tr>
		<th>titre</th>
		<td><?php echo $this->oArticle->titre ?></td>
	</tr>

	<tr>
		<th>auteur_id</th>
		<td><?php echo $this->oArticle->auteur_id ?></td>
	</tr>

	<tr>
		<th>priority</th>
		<td><?php echo $this->oArticle->priority ?></td>
	</tr>

</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" />
</form>

