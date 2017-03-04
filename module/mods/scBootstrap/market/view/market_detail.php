<?php echo $this->sHead ?>

<div class="market">
	<h2><?php echo $this->title ?></h2>
	<h3><?php echo tr('auteur') ?>: <?php echo $this->author ?></h3>
	<h3><?php echo tr('version') ?>: <?php echo $this->version ?></h3>
	<p><?php echo $this->content ?></p>


        <h3><?php echo tr('presentation') ?></h3>
        <p><?php echo $this->presentation ?></p>

	<?php if ($this->actualites and strlen($this->actualites) > 5): ?>
		<h3><?php echo tr('actualites') ?></h3>
		<p><?php echo $this->actualites ?></p>
	<?php endif; ?>

        <h3><?php echo tr('utilisation') ?></h3>
        <p><?php echo $this->utilisation ?></p>

	<br/>

	<form action="<?php echo module_mods_scBootstrap_market::getInstallLink($this->id, $this->version) ?>" method="POST">

		<a href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'install', 'market' => 'normal_list_1')) ?>#createon"><?php echo tr('retour') ?></a> <input type="submit" value="<?php echo tr('install') ?>"/>
	</form>
</div>