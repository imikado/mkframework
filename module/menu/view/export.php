<h1><?php echo _root::getParam('id')?> 

							<a style="margin-left:20px;color:white" class="buttons" href="<?php echo _root::getLink(
								'builder::edit',
								array('id'=>_root::getParam('id'))
								)?>#createon"><?php echo tr('menuNavProject_link_edit')?></a>

							<a style="margin-left:10px;color:white" class="buttons" href="<?php echo _root::getLink(
								'code::index',
								array('project'=>_root::getParam('id'))
								)?>"><?php echo tr('menuNavProject_link_explore')?></a>
								
								
								<a style="margin-left:30px;color:white;" class="buttons" target="_blank" href="<?php echo _root::getConfigVar('path.generation')?><?php echo _root::getParam('id')?>"><?php echo tr('menuNavProject_link_gotoSite')?></a>


								<a style="margin-left:30px;color:#444;background:#eee" class="buttons" href="<?php echo _root::getLink('builder::export')?>"><?php echo tr('menuNavProject_link_export')?></a>
									</h1>
<a id="createon" name="createon"></a>
