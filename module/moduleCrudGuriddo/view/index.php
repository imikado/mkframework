<script>
function refresh(){
	document.location.reload();
}
</script>
<style>
	.code{
		border:1px solid #ccc;
		background:#eee;
		padding:4px;
	}
	.genere{
		color:#444;
	}
</style>
<?php $tEnable=_root::getParam('tEnable'); $r="\n";$t="\t";?>
<h1><?php echo tr('menuProject_link_createModuleCRUDguriddo')?></h1>

<?php if(!$this->bGuriddo):?>
<p style="color:red">Le module Guriddo est absent, veuillez le t&eacute;l&eacute;charger</p>
<ul>
	<li>Rendez-vous &agrave; &agrave; cette  <a href="http://mkframework.com/telechargerModule_guriddo.html" target="_blank">adresse</a></li> 
	<li>t&eacute;l&eacute;chargez le module,</li>
	<li>d&eacute;sarchivez-le dans le repertoire "<?php echo $this->pathModule?>"</li> 
	<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
</ul>
<?php else:?>
<p>Module Guriddo 1/2: <span style="color:green">ok</span></p>


	<?php if(!$this->bGuriddoPublicExist):?>
	<p style="color:red">Le module Guriddo est presque install&eacute;, </p>
	<ul>
		<li>copiez le repertoire "<span class="genere"><?php echo $this->pathGenerated?></span>/module/Guriddo/public/<strong>guriddo</strong>"</li>
		<li>dans "<?php echo $this->pathPublic?>"</li>
		<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
	</ul>   
	<?php else:?>
	<p>Module Guriddo 2/2: <span style="color:green">ok</span></p>
	
	
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
										'action' => 'crudguriddo',
										'class'=> $sFile
								))?>#editcrud"><?php echo $sFile?></a></li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
	</div>
	
	<?php endif;?> 

<?php endif;?>


<a id="editcrud" name="editcrud"></a>
<?php if(_root::getParam('class') !=''):?>

	<?php if(!$this->bModelCountExist):?>
		<p class="error">Il nous faut une methode findTotal() sur votre classe "<?php echo $this->class?>" dans votre fichier <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
		<ul>
			<li>Ajoutez une methode findTotal()</li>
			<li><div class="code">
				<?php highlight_string('<?php '.$r.
				$t.'//methode listant le nombre total d\'enregistrements'.$r.
				$t.'public function findTotal(){'.$r.
					$t.$t.'$oRow=$this->findOneSimple(\'SELECT count(*) as total FROM \'.$this->sTable);'.$r.
					$t.$t.'return $oRow->total;'.$r.
				$t.'}');?>
				</div>
			</li>
			<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
		</ul>
	<?php else:?>
		<p>Model <?php echo $this->class?> 1/4: <span style="color:green">ok</span></p>
	
		<?php if(!$this->bModelPaginationExist):?>
			<p class="error">Il nous faut une methode findListLimitOrderBy() sur votre classe "<?php echo $this->class?>" dans votre fichier <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a> </p>
			<ul>
				<li>Ajoutez une methode findListLimitOrderBy()</li>
				<li>Exemple de pagination (pour mysql), adaptez selon votre SGBD<div class="code">
					<?php highlight_string('<?php '.$r.
					$t.'//methode retournant les enregistrements trie et pagine'.$r.
					$t.'public function findListLimitOrderBy($start,$limit,$sField,$side){'.$r.
						$t.$t.'return $this->findManySimple(\'SELECT * FROM \'.$this->sTable.\' ORDER BY \'.$sField.\' \'.$side.\' LIMIT \'.$start.\',\'.$limit);'.$r.
					$t.'}');?>
					</div>
				</li>
				<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
			</ul>
		<?php else:?>
			<p>Model <?php echo $this->class?> 2/4: <span style="color:green">ok</span></p>

			
			<?php if(!$this->bModelFilterCountExist):?>
				<p class="error">Il nous faut une methode findTotalFiltered() sur votre classe "<?php echo $this->class?>" dans votre fichier <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
				<ul>
					<li>Ajoutez une methode findTotalFiltered()</li>
					<li><div class="code">
						<?php highlight_string('<?php '.$r.
						$t.'//methode listant le nombre total d\'enregistrements'.$r.
						$t.'public function findTotalFiltered($tFilter){'.$r.
							$t.$t.'foreach($tFilter as $sField => $sValue){'.$r.
								$t.$t.$t.'$tWhere[]=$sField.\'=?\';'.$r.
								$t.$t.$t.'$tValue[]=$sValue;'.$r.
							$t.$t.'}'.$r.
							$t.$t.'$oRow=$this->findOneSimple(\'SELECT count(*) as total FROM \'.$this->sTable.\' WHERE  \'.implode(\'AND\',$tWhere).\'\',$tValue);'.$r.
							$t.$t.'return $oRow->total;'.$r.
						$t.'}');?>
						</div>
					</li>
					<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
				</ul>
			<?php else:?>
				<p>Model <?php echo $this->class?> 3/4: <span style="color:green">ok</span></p>
				
				<?php if(!$this->bModelFilterPaginationExist):?>
					<p class="error">Il nous faut une methode findListFilteredAndLimitOrderBy() sur votre classe "<?php echo $this->class?>" dans votre fichier <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
					<ul>
						<li>Ajoutez une methode findListFilteredAndLimitOrderBy()</li>
						<li>Exemple de pagination (pour mysql), adaptez selon votre SGBD<div class="code">
							<?php highlight_string('<?php '.$r.
							$t.'//methode listant le nombre total d\'enregistrements'.$r.
							$t.'public function findListFilteredAndLimitOrderBy($tFilter,$start,$limit,$sField,$side){'.$r.
								$t.$t.'foreach($tFilter as $sField => $sValue){'.$r.
									$t.$t.$t.'$tWhere[]=$sField.\'=?\';'.$r.
									$t.$t.$t.'$tValue[]=$sValue;'.$r.
								$t.$t.'}'.$r.
								$t.$t.'return $this->findManySimple(\'SELECT * FROM \'.$this->sTable.\' WHERE  \'.implode(\'AND\',$tWhere).\' ORDER BY \'.$sField.\' \'.$side.\' LIMIT \'.$start.\',\'.$limit,$tValue);'.$r.
							$t.'}');?>
							</div>
						</li>
						<li><input type="button" value="reactualiser" onclick="refresh()"  /></li>
					</ul>
				<?php else:?>
					<p>Model <?php echo $this->class?> 4/4: <span style="color:green">ok</span></p>
					

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
								<th><?php echo tr('builder::edit_crud_tripardefaut')?></th>
								<td>
									
									<select name="defaultSort">
									<?php foreach($this->tSortColumn as $sColumn):?>
										<option value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
									<?php endforeach;?>
									</select>
								</td>
							</tr>
							
							<tr>
								<th><?php echo tr('builder::edit_crud_dimensions')?></th>
								<td style="text-align:right">
									
									
									<?php echo tr('builder::edit_crud_limit')?>:<input type="text" name="tableLimit" value="5" size="3"/>lignes<br/><br/>
									
									<?php echo tr('builder::edit_crud_width')?>:<input type="text" name="tableWidth" value="600"/>px<br/>
									<?php echo tr('builder::edit_crud_height')?>:<input type="text" name="tableHeight" value="120"/>px
								
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

					
					
				<?php endif;?>
				
			<?php endif;?>
		<?php endif;?>
		
	<?php endif;?>
