<table>
	
	<tr>
		<th>titre</th>
		<td><?php echo $this->oArticle->titre ?></td>
	</tr>

	<tr>
		<th>Auteur</th>
		<td><?php  if(isset($this->tJoinAuteur[ $this->oArticle->auteur_id])){ echo $this->tJoinAuteur[$this->oArticle->auteur_id]; } ?></td>
	</tr>

	<tr>
		<th>priority</th>
		<td><?php echo $this->oArticle->priority ?></td>
	</tr>

</table>

