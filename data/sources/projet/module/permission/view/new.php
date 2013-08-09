<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST">

<table>
	
	<tr>
		<th>group</th>
		<td><?php echo $oPluginHtml->getSelect('group',model_group::getInstance()->getListSelect() )?><?php if($this->tMessage and isset($this->tMessage['group'])): echo implode(',',$this->tMessage['group']); endif;?></td>
	</tr>

	<tr>
		<th>action</th>
		<td><input name="action" /><?php if($this->tMessage and isset($this->tMessage['action'])): echo implode(',',$this->tMessage['action']); endif;?></td>
	</tr>

	<tr>
		<th>element</th>
		<td><input name="element" /><?php if($this->tMessage and isset($this->tMessage['element'])): echo implode(',',$this->tMessage['element']); endif;?></td>
	</tr>

	<tr>
		<th>allowdeny</th>
		<td><?php echo $oPluginHtml->getSelect('allowdeny',array('ALLOW'=>'Allow','DENY'=>'Deny'))?><?php if($this->tMessage and isset($this->tMessage['allowdeny'])): echo implode(',',$this->tMessage['allowdeny']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Ajouter" />
</form>

