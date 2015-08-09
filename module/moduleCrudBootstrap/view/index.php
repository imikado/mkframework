<?php $tEnable=_root::getParam('tEnable')?>
<h1><?php echo tr('menuProject_link_createModuleCRUD')?></h1>
<p><?php echo tr('builder::edit_crud_choisissezUneClassModele')?></p>
<div class="smenu">
<ul>
<?php if($this->tFile)?>
<?php foreach($this->tFile as $sFile):?>
	<?php if(_root::getParam('class')==$sFile):?>
		<li class="selectionne"><?php echo $sFile?></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
									'id' => _root::getParam('id'),
									'action' => 'crudWithBootstrap',
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
	
	<?php if(!_root::getParam('moduleToCreate') and file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$this->sModuleToCreate)):?>
		<p class="error"><?php echo sprintf(tr('builder::edit_crud_leModuleExisteDeja'),$this->sModuleToCreate)?></p>
	<?php endif;?>
	
 	<table>
		<tr>
			<th><?php echo tr('builder::edit_crud_nomDuModuleAcreer')?></th>
			<td><input type="text" name="moduleToCreate" value="<?php echo _root::getParam('moduleToCreate',$this->sModuleToCreate)?>"/></td>
			
			<td style="border:0px">&nbsp;</td>
			
			<th>
				<?php echo tr('builder::edit_crud_actionsCrud')?>
			</th>
			
			<td>
				<input type="checkbox" name="crud[]" value="crudNew" checked="checked" /> <?php echo tr('builder::edit_crud_formulaireAjout')?><br/>
				<input type="checkbox" name="crud[]" value="crudEdit" checked="checked" /> <?php echo tr('builder::edit_crud_formulaireDeModification')?><br/>
				<input type="checkbox" name="crud[]" value="crudDelete" checked="checked" /> <?php echo tr('builder::edit_crud_formulaireDeSuppression')?><br/>
				<input type="checkbox" name="crud[]" value="crudShow" checked="checked" /> <?php echo tr('builder::edit_crud_formulaireDaffichageDetail')?><br/>
				
				
			</td>
		</tr>
		
		<tr>
			<th><?php echo tr('builder::edit_crud_options')?></th>
			<td>
				<input type="checkbox" name="withPagination" value="1" <?php if(_root::getParam('withPagination')):?>checked="checked"<?php endif;?>/> <?php echo tr('builder::edit_crud_avecPagination')?>
			</td>
		</tr>
		
	</table>
	
	
			
		
	<br/>

	<input type="hidden" name="sClass" value="<?php echo $this->sClass?>" />
	<table>
		<tr>
			<th></th>
			<th><?php echo tr('builder::edit_crud_champ')?></th>
			<th><?php echo tr('builder::edit_crud_libelle')?></th>
			<th><?php echo tr('builder::edit_crud_type')?></th>
		</tr>
	<?php foreach($this->tColumn as $sColumn):?>
		<tr>
			<td><input type="checkbox" name="tEnable[]" value="<?php echo $sColumn?>" <?php if(!is_array($tEnable)):?>checked="checked"<?php elseif(in_array($sColumn,$tEnable)):?>checked="checked"<?php endif;?> /></td>
			<td><?php echo $sColumn?><input type="hidden" name="tColumn[]" value="<?php echo $sColumn?>" /></td>
			<td><input type="text" name="tLabel[]" value="<?php echo $sColumn?>"/></td>
			<td><select name="tType[]">
				<option value="text">text</option>
				<option value="textarea">textarea</option>
				<option value="date">date</option>
				<option value="upload">upload</option>
				
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
