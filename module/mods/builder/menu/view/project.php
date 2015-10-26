<h1><?php echo _root::getParam('id')?> 

							<a style="margin-left:20px;color:#444;background:#eee" class="buttons" href="<?php echo _root::getLink(
								'builder::edit',
								array('id'=>_root::getParam('id'))
								)?>#createon"><?php echo tr('menuNavProject_link_edit')?></a>

							<a style="margin-left:10px;color:white" class="buttons" href="<?php echo _root::getLink(
								'code::index',
								array('project'=>_root::getParam('id'))
								)?>"><?php echo tr('menuNavProject_link_explore')?></a>
								
								
								<a style="margin-left:30px;color:white;" class="buttons" target="_blank" href="<?php echo _root::getConfigVar('path.generation')?><?php echo _root::getParam('id')?>"><?php echo tr('menuNavProject_link_gotoSite')?></a>


								<a style="margin-left:30px;color:white;" class="buttons" href="<?php echo _root::getLink('builder::export',array('id'=>_root::getParam('id')))?>"><?php echo tr('menuNavProject_link_export')?></a>
									</h1>
<ul>
<?php foreach($this->tLink as $sLibelle => $sLink): ?>

	<?php if($sLink == 'title'):?>
		<h2 style="background:white"><?php echo $sLibelle?></h2>
	<?php elseif( _root::getParam('action') == $sLink):?>
		<?php _root::setConfigVar('builder.selected.label',$sLibelle);?>
		<li class="selectionne"><a href="<?php echo $this->getLink(
											'builder::edit',
											array(
												'id'=>_root::getParam('id'),
												'action'=>$sLink
											)
										) ?>#createon"><?php echo $sLibelle ?></a></li>
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
