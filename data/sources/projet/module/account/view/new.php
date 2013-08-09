<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST">

<table>
	
	<tr>
		<th>login</th>
		<td><input name="login" /><?php if($this->tMessage and isset($this->tMessage['login'])): echo implode(',',$this->tMessage['login']); endif;?></td>
	</tr>

	<tr>
		<th>pass</th>
		<td><input name="pass" /><?php if($this->tMessage and isset($this->tMessage['pass'])): echo implode(',',$this->tMessage['pass']); endif;?></td>
	</tr>

	<tr>
		<th>nom</th>
		<td><input name="nom" /><?php if($this->tMessage and isset($this->tMessage['nom'])): echo implode(',',$this->tMessage['nom']); endif;?></td>
	</tr>

	<tr>
		<th>prenom</th>
		<td><input name="prenom" /><?php if($this->tMessage and isset($this->tMessage['prenom'])): echo implode(',',$this->tMessage['prenom']); endif;?></td>
	</tr>

	<tr>
		<th>groupe</th>
		<td><?php echo $oPluginHtml->getSelect('groupe',model_group::getInstance()->getListSelect() )?><?php if($this->tMessage and isset($this->tMessage['groupe'])): echo implode(',',$this->tMessage['groupe']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Ajouter" />
</form>

