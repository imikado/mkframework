<h1><?php echo _root::getParam('id')?></h1>
<ul>
<?php foreach($this->tLink as $sLibelle => $sLink): ?>
	<?php if( _root::getParam('action') == $sLink):?>
		<li class="selectionne"><a href="<?php echo $this->getLink(
											'builder::edit',
											array(
												'id'=>_root::getParam('id'),
												'action'=>$sLink
											)
										) ?>"><?php echo $sLibelle ?></a></li>
	<?php else:?>
		<li><a href="<?php echo $this->getLink(
											'builder::edit',
											array(
												'id'=>_root::getParam('id'),
												'action'=>$sLink
											)
					) ?>#createon"><?php echo $sLibelle ?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
<a id="createon" name="createon"></a>
