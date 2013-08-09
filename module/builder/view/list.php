<ul class="projets">
<?php if($this->tProjet):?>
	<?php foreach($this->tProjet as $sProjet):?>
		<li <?php if(_root::getParam('id')==$sProjet):?>class="selectionne"<?php endif;?>>
								<span><?php echo $sProjet?></span>

								<a href="<?php echo _root::getLink(
								'builder::edit',
								array('id'=>$sProjet)
								)?>#createon">Editer le projet</a>
								
								<a href="<?php echo _root::getLink(
								'code::index',
								array('project'=>$sProjet)
								)?>">Explorer le projet <sup>BETA</sup></a>
								
								
								<a target="_blank" href="<?php echo _root::getConfigVar('path.generation')?><?php echo $sProjet?>">Voir le site</a>					
								</li>
	<?php endforeach;?>
<?php endif;?>
</ul>
