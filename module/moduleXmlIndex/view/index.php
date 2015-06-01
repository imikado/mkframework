<h1><?php echo tr('menuProject_link_createDatabaseXmlIndex')?></h1>
<h2>Configuration: <?php echo _root::getParam('config')?></h2>
<?php $sTableSelected=_root::getParam('sTable')?>
<p><?php echo tr('builder::edit_xmlindex_choisirLaTable')?></p>
<div class="smenu">
<ul>
<?php foreach($this->tTables as $sTable):?>
	<?php if($sTable == $sTableSelected):?>
		<li><strong><?php echo $sTable?></strong></li>
	<?php else:?>
		<li><a href="<?php echo _root::getLink('builder::edit',array(
								'id' => _root::getParam('id'),
								'action' => _root::getParam('action'),
								'sTable' => $sTable,
								'config'=>_root::getParam('config')
								)
			)?>#createon"><?php echo $sTable?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>

<?php if($sTableSelected!=''):?>
<h2><?php echo $sTableSelected?></h2>

S&eacute;lectionner les champs de l'index &agrave; cr&eacute;er<br />
<form action="" method="POST">
	<?php foreach($this->tTableColumn[$sTableSelected] as $sField):?>
	<input type="checkbox" name="tField[]" value="<?php echo $sField?>" />
	<?php echo $sField?><br />
	<?php endforeach;?>

<input type="submit" value="Ajouter l'index"/>
</form>

<p style="background:#ddd"><?php echo $this->detail?></p>

<br />
Ou reg&eacute;n&eacute;rer un des index suivants:
<ul>
<?php foreach($this->tFileIndex as $sIndex):?>
<li><a href="<?php echo _root::getLink('builder::edit',array(
								'id' => _root::getParam('id'),
								'action' => _root::getParam('action'),
								'sTable' => $sTableSelected,
								'regenerateIndexXml'=>$sIndex,
								'config'=>_root::getParam('config')
								)
			)?>"><?php echo $sIndex?></a></li>
<?php endforeach;?>
</ul>



<?php endif;?> 
