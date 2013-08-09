<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST">
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oPermission->$sColumn ?>" />
	<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
<?php endforeach;?>	
<table>
	
	<tr>
		<th>group</th>
		<td><?php echo $oPluginHtml->getSelect('group',model_group::getInstance()->getListSelect(),$this->oPermission->group )?>
		<?php if($this->tMessage and isset($this->tMessage['group'])): echo implode(',',$this->tMessage['group']); endif;?></td>
	</tr>

	<tr>
		<th>action</th>
		<td><input name="action" value="<?php echo $this->oPermission->action ?>" /><?php if($this->tMessage and isset($this->tMessage['action'])): echo implode(',',$this->tMessage['action']); endif;?></td>
	</tr>

	<tr>
		<th>element</th>
		<td><input name="element" value="<?php echo $this->oPermission->element ?>" /><?php if($this->tMessage and isset($this->tMessage['element'])): echo implode(',',$this->tMessage['element']); endif;?></td>
	</tr>

	<tr>
		<th>allowdeny</th>
		<td><?php echo $oPluginHtml->getSelect('allowdeny',array('ALLOW'=>'Allow','DENY'=>'Deny'),$this->oPermission->allowdeny )?><?php if($this->tMessage and isset($this->tMessage['allowdeny'])): echo implode(',',$this->tMessage['allowdeny']); endif;?></td>
	</tr>

</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" />
</form>

