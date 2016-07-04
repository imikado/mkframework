<?php $tEnable=_root::getParam('tEnable')?>
<p><?php echo tr('choisissezUneClasseModele')?></p>
<div class="smenu">
<ul>
<?php if($this->tFile)?>
<?php foreach($this->tFile as $sFile):?>
	<?php if(_root::getParam('class')==$sFile):?>
		<li class="selectionne"><?php echo $sFile?></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
									'id' => _root::getParam('id'),
									'action' => _root::getParam('action'),
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
	
	
 	<table>
		<tr>
			<th><?php echo tr('creerLaVue')?></th>
			<td>module/</td>
			<td>
				<select name="module">
					<?php foreach($this->tModule as $sModule):?>
					<option value="<?php echo $sModule->getName()?>"><?php echo $sModule->getName()?></option>
					<?php endforeach;?>
				
				</select>
			
			</td>
			
			<td>/view/</td>
			<td><input type="text" name="view" value="<?php echo _root::getParam('moduleToCreate','edit')?>"/>.php</td>
			
			 
		</tr>
		
	 
		
	</table>
		
	<br/>

	<input type="hidden" name="sClass" value="<?php echo $this->sClass?>" />
	<table>
		<tr>
			<th></th>
			<th><?php echo tr('champ')?></th>
			<th><?php echo tr('libelle')?></th>
			<th><?php echo tr('type')?></th>
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
	
	<input type="submit" value="<?php echo tr('creer')?>" />
	
	</form>
</div>
<?php endif;?>

<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
