<?php $tEnable=_root::getParam('tEnable')?>
<h1><?php echo tr('menuProject_link_createModuleCRUDreadonly')?></h1>
<p><?php echo tr('builder::edit_crudreadonly_choisissezUneClasseModele')?></p>
<div class="smenu">
<ul>
<?php if($this->tFile)?>
<?php foreach($this->tFile as $sFile):?>
	<?php if(_root::getParam('class')==$sFile):?>
		<li class="selectionne"><?php echo $sFile?></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
									'id' => _root::getParam('id'),
									'action' => 'crudreadonly',
									'class'=> $sFile
							))?>#editcrud"><?php echo $sFile?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
<br />

<?php if(_root::getParam('class') !=''):?>
<a id="editcrud" name="editcrud"></a>
<div class="table">
	<?php if($this->tColumn)?>
	<form action="" method="POST">
	<p><?php echo tr('builder::edit_crudreadonly_nomDuModuleAcreer')?> <input type="text" name="moduleToCreate" value="<?php echo _root::getParam('moduleToCreate',$this->sModuleToCreate)?>"/></>
	<?php if(!_root::getParam('moduleToCreate') and file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$this->sModuleToCreate)):?>
		<p class="error"><?php echo sprintf(tr('builder::edit_crudreadonly_leModuleExisteDeja'),$this->sModuleToCreate)?> </p>
	<?php endif;?>

	<input type="hidden" name="sClass" value="<?php echo $this->sClass?>" />
	<table>
		<tr>
			<th></th>
			<th><?php echo tr('builder::edit_crudreadonly_champ')?></th>
			<th><?php echo tr('builder::edit_crudreadonly_type')?></th>
		</tr>
	<?php foreach($this->tColumn as $sColumn):?>
		<tr>
			<td><input type="checkbox" name="tEnable[]" value="<?php echo $sColumn?>" <?php if(!is_array($tEnable)):?>checked="checked"<?php elseif(in_array($sColumn,$tEnable)):?>checked="checked"<?php endif;?> /></td>
			<td><?php echo $sColumn?><input type="hidden" name="tColumn[]" value="<?php echo $sColumn?>" /></td>
			<td><select name="tType[]">
				<option value="text">text</option>
				
				<?php foreach($this->tRowMethodes as $sRowMethod => $sLabel):?>
					<option value="select;<?php echo $sRowMethod?>"><?php echo tr('builder::edit_crud_selectEnUtilisant')?> <?php echo $sLabel?></option>
				<?php endforeach;?>
			</select></td>
		</tr>
	<?php endforeach;?>
	</table>
	
	<input type="submit" value="<?php echo tr('builder::edit_crud_creer')?>" />
	
	</form>
</div>
<?php endif;?>

<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
