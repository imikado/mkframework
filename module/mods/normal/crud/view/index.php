<?php $tEnable=_root::getParam('tEnable')?>
<p><?php echo tr('choisissezUneClassModele')?></p>
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
	
	<?php if(!_root::getParam('moduleToCreate') and file_exists(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$this->sModuleToCreate)):?>
		<p class="error"><?php echo sprintf(tr('leModuleExisteDeja'),$this->sModuleToCreate)?></p>
	<?php endif;?>
	
 	<table>
		<tr>
			<th><?php echo tr('nomDuModuleAcreer')?></th>
			<td><input type="text" name="moduleToCreate" value="<?php echo _root::getParam('moduleToCreate',$this->sModuleToCreate)?>"/></td>
			
			<td style="border:0px">&nbsp;</td>
			
			<th>
				<?php echo tr('actionsCrud')?>
			</th>
			
			<td>
				<input type="checkbox" name="crud[]" value="crudNew" checked="checked" /> <?php echo tr('formulaireAjout')?><br/>
				<input type="checkbox" name="crud[]" value="crudEdit" checked="checked" /> <?php echo tr('formulaireDeModification')?><br/>
				<input type="checkbox" name="crud[]" value="crudDelete" checked="checked" /> <?php echo tr('formulaireDeSuppression')?><br/>
				<input type="checkbox" name="crud[]" value="crudShow" checked="checked" /> <?php echo tr('formulaireDaffichageDetail')?><br/>
				
				
			</td>
		</tr>
		
		<tr>
			<th><?php echo tr('options')?></th>
			<td>
				<input type="checkbox" name="withPagination" value="1" <?php if(_root::getParam('withPagination')):?>checked="checked"<?php endif;?>/> <?php echo tr('avecPagination')?>
			</td>
		</tr>
		
	</table>
	
	
			
		
	<br/>

	<input type="hidden" name="sClass" value="<?php echo $this->sClass?>" />
	
	
	<?php if($this->sgbd=='mongodb'):?>
	<script>
	function addColonne(){
		
		var colonne=getById('mongoAddLineColumn').value;
		
		var a=getById('blocColumns');
		if(a){
			
			var sHtml='<tr>';
			sHtml+='<td><input type="checkbox" name="tEnable[]" checked="checked" value="'+colonne+'" /></td>';
			sHtml+='<td>'+colonne+'<input type="hidden" name="tColumn[]" value="'+colonne+'" /></td>';
			sHtml+='<td><input type="text" name="tLabel[]" value="'+colonne+'"/></td>';
			sHtml+='<td><select name="tType[]">';
			
				sHtml+='<option value="text">text</option>';
				sHtml+='<option value="textarea">textarea</option>';
				sHtml+='<option value="date">date</option>';
				sHtml+='<option value="upload">upload</option>';
				
				<?php foreach($this->tRowMethodes as $sRowMethod => $sLabel):?>
					sHtml+='<option value="select;<?php echo $sRowMethod?>"><?php echo tr('selectEnUtilisant')?> <?php echo $sLabel?></option>';
				<?php endforeach;?>
			
			sHtml+='</select></td>';
			sHtml+='</tr>';
			
			a.innerHTML+=sHtml;
		}
	}
	</script>
	
	<p>&nbsp;</p>
	<div style="border:1px dotted #444;padding:10px;margin-right:20px">
		<h3>Ajouter &agrave; votre collection Mongodb</h3>
		
		<p>Ajouter le champ <input type="text" id="mongoAddLineColumn"/><input type="button" onclick="addColonne()" value="Ajouter" /></p>
		<p style="color:red">Attention: il vous faut ajouter des colonnes (une base noSQL n'a pas de structure fixe)</p>
	</div>
	
	<p>&nbsp;</p>
	<?php endif;?>
	
	
	<table>
		<tr>
			<th></th>
			<th><?php echo tr('champ')?></th>
			<th><?php echo tr('libelle')?></th>
			<th><?php echo tr('type')?></th>
		</tr>
		<tbody id="blocColumns">
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
						<option value="select;<?php echo $sRowMethod?>"><?php echo tr('selectEnUtilisant')?> <?php echo $sLabel?></option>
					<?php endforeach;?>
				</select></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	
	
	
	
	<input type="submit" value="<?php echo tr('creer')?>" />
	
	</form>
</div>
<?php endif;?>

<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
