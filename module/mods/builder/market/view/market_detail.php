<?php echo $this->sHead?>

<div class="market">
	<h2><?php echo $this->title?></h2>
	<h3><?php echo tr('auteur')?>: <?php echo $this->author?></h3>
	<h3><?php echo tr('version')?>: <?php echo $this->version?></h3>
	<p><?php echo $this->content?></p>
	<br/>

	<form action="<?php echo module_mods_builder_market::getInstallLink($this->id,$this->version)?>" method="POST">
	
		<input type="submit" value="<?php echo tr('install')?>"/>
	</form>
</div>