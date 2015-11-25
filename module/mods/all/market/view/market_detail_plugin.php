<?php echo $this->sHead?>

<div class="market">
	<h2><?php echo $this->title?></h2>
	<h3><?php echo tr('auteur')?>: <?php echo $this->author?></h3>
	<h3><?php echo tr('version')?>: <?php echo $this->version?></h3>
	
	<?php if($this->content and strlen($this->content)>5):?>
	<p><?php echo  module_mods_all_market::formate($this->content)?></p>
	<?php endif;?>
        
        <?php if($this->presentation and strlen($this->presentation)>5):?>
        <h3><?php echo tr('presentation')?></h3>
        <p><?php echo module_mods_all_market::formate($this->presentation)?></p>
	<?php endif;?>
        
        <?php if($this->actualites and strlen($this->actualites)>5):?>
        <h3><?php echo tr('actualites')?></h3>
        <p><?php echo  module_mods_all_market::formate($this->actualites)?></p>
        <?php endif;?>
        
	<?php if($this->utilisation and strlen($this->utilisation)>5):?>
        <h3><?php echo tr('utilisation')?></h3>
        <p><?php echo module_mods_all_market::formate($this->utilisation)?></p>
	<?php endif;?>
        
	<br/>

	<form action="<?php echo module_mods_all_market::getInstallLinkPlugin($this->id,$this->version)?>" method="POST">
	
		<a href="<?php echo _root::getLink('builder::edit',array('id'=>_root::getParam('id'),'action'=>'mods_all_market::index',  'saction'=>'install','market'=>'plugin_list_1'  ) )?>#createon"><?php echo tr('retour')?></a> <input type="submit" value="<?php echo tr('install')?>"/>
	</form>
</div>