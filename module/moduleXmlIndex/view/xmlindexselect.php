<h2>Choisissez le profil xml &agrave; utiliser</h2>
<div class="smenu">
<ul><?php foreach($this->tConnexion as $sKey => $sgbd):?>
	<?php if(substr($sKey,-5)=='.sgbd' and $sgbd=='xml') :?>
	<li><a href="<?php echo _root::getLink('builder::edit',array(
							'id' => _root::getParam('id'),
							'action'=> 'xmlindex',
							'config'=> substr($sKey,0,-5),
	))?>#createon"><?php echo substr($sKey,0,-5)?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
</div>
