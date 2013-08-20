<h1><?php echo _root::getParam('id')?> 

							<a style="margin-left:20px;color:#444;background:#eee" class="buttons" href="<?php echo _root::getLink(
								'builder::edit',
								array('id'=>_root::getParam('id'))
								)?>#createon">Editer le projet</a>

							<a style="margin-left:10px;color:white" class="buttons" href="<?php echo _root::getLink(
								'code::index',
								array('project'=>_root::getParam('id'))
								)?>">Explorer le projet <sup>BETA</sup></a>
								
								
								<a style="margin-left:10px;color:white;" class="buttons" target="_blank" href="<?php echo _root::getConfigVar('path.generation')?><?php echo _root::getParam('id')?>">Voir le site</a>	</h1>
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
