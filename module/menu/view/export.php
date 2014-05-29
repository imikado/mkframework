<h1><?php echo _root::getParam('id')?> 

							<a style="margin-left:20px;color:white" class="buttons" href="<?php echo _root::getLink(
								'builder::edit',
								array('id'=>_root::getParam('id'))
								)?>#createon">Editer le projet</a>

							<a style="margin-left:10px;color:white" class="buttons" href="<?php echo _root::getLink(
								'code::index',
								array('project'=>_root::getParam('id'))
								)?>">Explorer le projet </a>
								
								
								<a style="margin-left:30px;color:white;" class="buttons" target="_blank" href="<?php echo _root::getConfigVar('path.generation')?><?php echo _root::getParam('id')?>">Voir le site</a>


								<a style="margin-left:30px;color:#444;background:#eee" class="buttons" href="<?php echo _root::getLink('builder::export')?>">Exporter le projet<sup>BETA</sup></a>
									</h1>
<a id="createon" name="createon"></a>
