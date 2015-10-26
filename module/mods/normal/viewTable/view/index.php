<?php $tEnable=_root::getParam('tEnable')?>
<p><?php echo tr('choisissezUneClasseModele')?></p>
<div class="smenu">
<ul>
<?php if($this->tFile)?>
<?php foreach($this->tFile as $sFile):?>
	<?php if(_root::getParam('model')==$sFile):?>
		<li class="selectionne"><?php echo $sFile?></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
									'id' => _root::getParam('id'),
									'action' => _root::getParam('action'),
									'model'=> $sFile
							))?>#editcrud"><?php echo $sFile?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
<?php if(_root::getParam('model') !=''):?>
<a id="editcrud" name="editcrud"></a>
<p><?php echo tr('choisissezLaMethodeQuiRemplira')?></p>
<div class="smenu">
<ul>
<?php if($this->tMethod)?>
<?php foreach($this->tMethod as $sMethod):?>
	<?php if(_root::getParam('method')==$sMethod):?>
		<li class="selectionne"><?php echo $sMethod?></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink(_root::getRequest()->getParamNav(),array(
									'id' => _root::getParam('id'),
									'action' => _root::getParam('action'),
									'model'=> _root::getParam('model'),
									'method'=> $sMethod
							))?>#editcrud"><?php echo $sMethod?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>


<?php if(_root::getParam('model') !='' and _root::getParam('method')!=''):?>
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
			<td><input type="text" name="view" value="<?php echo _root::getParam('moduleToCreate','listViaModule')?>"/>.php</td>
			
			 
		</tr>
		
	 
		
	</table>
	
	<br/>
	
	<table>
		<tr>
			<th><?php echo tr('activerLalternance')?></th>
			<td><input type="checkbox" name="enableAlt"/></td>
		 
		
			<td style="border:0px">&nbsp;</td>
		
	 
			<th><?php echo tr('classesAalterner')?></th>
			<td>
				<input style="width:50px" type="text" name="arrayAlt[]" value=""/> ,
				<input style="width:50px" type="text" name="arrayAlt[]" value="alt"/>
			</td>
		 
		</tr>
	</table>
	
	
			
		
	<br/>

	<table>
		<tr>
			<th><?php echo tr('classeDuTableau')?></th>
			<td><input type="text" name="tableClass" value="tb_list"/></td>
		</tr>
	</table>
	<br />
	
	<table>
		<tr>
			<th></th>
			<th><?php echo tr('champ')?></th>
			<th><?php echo tr('libelle')?></th>
		</tr>
	<?php foreach($this->tColumn as $sColumn):?>
		<tr>
			<td><input type="checkbox" name="tEnable[]" value="<?php echo $sColumn?>" <?php if(!is_array($tEnable)):?>checked="checked"<?php elseif(in_array($sColumn,$tEnable)):?>checked="checked"<?php endif;?> /></td>
			<td><?php echo $sColumn?><input type="hidden" name="tColumn[]" value="<?php echo $sColumn?>" /></td>
			<td><input type="text" name="tLabel[]" value="<?php echo $sColumn?>"/></td>
		
		</tr>
	<?php endforeach;?>
	</table>
	
	<input type="submit" value="<?php echo tr('creer')?>" />
	
	</form>
</div>
<?php endif;?>
<?php if($this->error!=''):?>
	<p class="error"><?php echo $this->error?></p>
<?php endif;?>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
