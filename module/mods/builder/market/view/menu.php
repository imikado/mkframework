<style>
.success{
	color:darkgreen;
}
</style>
<?php $sSelected=null;?>
<ul class="projets">
	<?php foreach($this->tLink as $sLabel => $sAction):?>
	<li <?php if($sAction==_root::getParam('action')): $sSelected=tr($sLabel); ?>class="selectionne"<?php endif;?>><a href="<?php echo _root::getLink('builder::marketBuilder',array('action'=>$sAction))?>"><?php echo tr($sLabel)?></a></li>
	<?php endforeach;?>
</ul>

<?php if($sSelected):?>
<h1><?php echo $sSelected;?></h1>
<?php endif;?>