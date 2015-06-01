<h1><?php echo tr('menuProject_link_createDatabaseJsonIndex')?></h1>
<h2><?php echo tr('label_choisissezLeProfilAutiliser')?></h2>
<div class="smenu">
<ul><?php foreach($this->tConnexion as $sKey => $sgbd):?>
	<?php if(substr($sKey,-5)=='.sgbd' and $sgbd=='json') :?>
	<li><a href="<?php echo _root::getLink('builder::edit',array(
							'id' => _root::getParam('id'),
							'action'=> 'jsonindex',
							'config'=> substr($sKey,0,-5),
	))?>#createon"><?php echo substr($sKey,0,-5)?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
