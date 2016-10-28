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

<?php if(!$this->bGuriddo):?>
<?php $tParam=array('id'=>_root::getParam('id'),'action'=>'mods_all_market::index','saction'=>'install','market'=>'detail_bootstrap.guriddo');?>
<p style="color:red"><?php echo tr('leModuleGuriddoEstAbsent')?></p>
<ul>
	<li><?php echo trR('rendezVousAcetteAdresse',array('#link#'=>_root::getLink('builder::edit',$tParam).'#createon' ))?></li>
	<li><?php echo tr('telechargezLeModule') ?>,</li>
	<li><?php echo trR('desachivezLeDansLeRepertoire',array('#pathModule#'=>$this->pathModule))?></li>
	<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
</ul>
<?php else:?>
<p><?php echo tr('moduleGuriddo')?> 1/2: <span style="color:green">ok</span></p>


	<?php if(!$this->bGuriddoPublicExist):?>
	<p style="color:red"><?php echo tr('leModuleGuriddoEstPresqueInstalle')?>, </p>
	<ul>
		<li><?php echo tr('copiezLeRepertoire')?> "<span class="genere"><?php echo $this->pathGenerated?></span>/module/Guriddo/public/<strong>guriddo</strong>"</li>
		<li><?php echo tr('dans')?> "<?php echo $this->pathPublic?>"</li>
		<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
	</ul>
	<?php else:?>
	<p><?php echo tr('moduleGuriddo')?> 2/2: <span style="color:green">ok</span></p>


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

	<?php endif;?>

<?php endif;?>

<a id="editcrud" name="editcrud"></a>
<?php if(_root::getParam('class') !=''):?>

	<?php if(!$this->bModelCountExist):?>
		<p class="error"><?php echo trR('ilNousFautUneMethode',array('#method#'=>'findTotal()','#class#'=>$this->class))?> <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
		<ul>
			<li><?php echo tr('ajoutezUneMethode')?> findTotal()</li>
			<li><div class="code">
				<?php highlight_string('<?php '.$r.
				$t.'//'.tr('methodeRetournantLeNombreTotalDenregistrement').$r.
				$t.'public function findTotal(){'.$r.
					$t.$t.'$oRow=$this->findOneSimple(\'SELECT count(*) as total FROM \'.$this->sTable);'.$r.
					$t.$t.'return $oRow->total;'.$r.
				$t.'}');?>
				</div>
			</li>
			<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
		</ul>
	<?php else:?>
		<p>Model <?php echo $this->class?> 1/4: <span style="color:green">ok</span></p>

		<?php if(!$this->bModelPaginationExist):?>
			<p class="error"><?php echo trR('ilNousFautUneMethode',array('#method#'=>'findListLimitOrderBy()','#class#'=>$this->class))?> <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a> </p>
			<ul>
				<li><?php echo tr('ajoutezUneMethode')?> findListLimitOrderBy()</li>
				<li><?php echo tr('exempleDePaginationAdaptezSelonVotreSgbd')?><div class="code">
					<?php highlight_string('<?php '.$r.
					$t.'//'.tr('methodeRetournantListeEnregistrementsTrieEtPagine').$r.
					$t.'public function findListLimitOrderBy($start,$limit,$sField,$side){'.$r.
						$t.$t.'return $this->findManySimple(\'SELECT * FROM \'.$this->sTable.\' ORDER BY \'.$sField.\' \'.$side.\' LIMIT \'.$start.\',\'.$limit);'.$r.
					$t.'}');?>
					</div>
				</li>
				<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
			</ul>
		<?php else:?>
			<p>Model <?php echo $this->class?> 2/4: <span style="color:green">ok</span></p>


			<?php if(!$this->bModelFilterCountExist):?>
				<p class="error"><?php echo trR('ilNousFautUneMethode',array('#method#'=>'findTotalFiltered()','#class#'=>$this->class))?> <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
				<ul>
					<li><?php echo tr('ajoutezUneMethode')?> findTotalFiltered()</li>
					<li><div class="code">
						<?php highlight_string('<?php '.$r.
						$t.'//'.tr('methodeRetournantLeNombreTotalDenregistrementFiltre').$r.
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
					<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
				</ul>
			<?php else:?>
				<p>Model <?php echo $this->class?> 3/4: <span style="color:green">ok</span></p>

				<?php if(!$this->bModelFilterPaginationExist):?>
					<p class="error"><?php echo trR('ilNousFautUneMethode',array('#method#'=>'findListFilteredAndLimitOrderBy()','#class#'=>$this->class))?> <a target="_blank" href="<?php echo _root::getLink('code::index',array('project'=>_root::getParam('id'),'file'=>'model/'.$this->class.'.php'))?>">model/<?php echo $this->class?>.php</a></p>
					<ul>
						<li><?php echo tr('ajoutezUneMethode')?> findListFilteredAndLimitOrderBy()</li>
						<li><?php echo tr('exempleDePaginationAdaptezSelonVotreSgbd')?><div class="code">
							<?php highlight_string('<?php '.$r.
							$t.'//'.tr('methodeRetournantListeEnregistrementsTrieEtPagineEtFiltre').$r.
							$t.'public function findListFilteredAndLimitOrderBy($tFilter,$start,$limit,$sField,$side){'.$r.
								$t.$t.'foreach($tFilter as $sField => $sValue){'.$r.
									$t.$t.$t.'$tWhere[]=$sField.\'=?\';'.$r.
									$t.$t.$t.'$tValue[]=$sValue;'.$r.
								$t.$t.'}'.$r.
								$t.$t.'return $this->findManySimple(\'SELECT * FROM \'.$this->sTable.\' WHERE  \'.implode(\'AND\',$tWhere).\' ORDER BY \'.$sField.\' \'.$side.\' LIMIT \'.$start.\',\'.$limit,$tValue);'.$r.
							$t.'}');?>
							</div>
						</li>
						<li><input type="button" value="<?php echo tr('reactualiser')?>" onclick="refresh()"  /></li>
					</ul>
				<?php else:?>
					<p>Model <?php echo $this->class?> 4/4: <span style="color:green">ok</span></p>


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
								<th><?php echo tr('tripardefaut')?></th>
								<td>

									<select name="defaultSort">
									<?php foreach($this->tSortColumn as $sColumn):?>
										<option value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
									<?php endforeach;?>
									</select>
								</td>
							</tr>

							<tr>
								<th><?php echo tr('dimensions')?></th>
								<td style="text-align:right">


									<?php echo tr('limit')?>:<input type="text" name="tableLimit" value="5" size="3"/>lignes<br/><br/>

									<?php echo tr('width')?>:<input type="text" name="tableWidth" value="600"/>px<br/>
									<?php echo tr('height')?>:<input type="text" name="tableHeight" value="120"/>px

								</td>
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
										<option value="select;<?php echo $sRowMethod?>"><?php echo tr('selectEnUtilisant')?> <?php echo $sLabel?></option>
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



				<?php endif;?>

			<?php endif;?>
		<?php endif;?>

	<?php endif;?>
