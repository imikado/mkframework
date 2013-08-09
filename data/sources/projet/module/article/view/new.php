<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST">

<table>
	
	<tr>
		<th>titre</th>
		<td><input name="titre" /><?php if($this->tMessage and isset($this->tMessage['titre'])): echo implode(',',$this->tMessage['titre']); endif;?></td>
	</tr>

	<tr>
		<th>auteur_id</th>
		<td><?php echo $oPluginHtml->getSelect('auteur_id',$this->tJoinAuteur,$this->oArticle->auteur_id)?><?php if($this->tMessage and isset($this->tMessage['auteur_id'])): echo implode(',',$this->tMessage['auteur_id']); endif;?></td>
	</tr>

	<tr>
		<th>priority</th>
		<td><input name="priority" /><?php if($this->tMessage and isset($this->tMessage['priority'])): echo implode(',',$this->tMessage['priority']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Ajouter" />
</form>

