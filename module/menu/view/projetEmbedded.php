<ul>
<?php foreach($this->tLink as $sLibelle => $sLink): ?>
	<?php if($sLink == 'title'):?>
		<h2 style="background:white"><?php echo $sLibelle?></h2>
	<?php elseif( _root::getParam('action') == $sLink):?>
		<li class="selectionne"><a href="<?php echo $this->getLink(
											'builder::editembedded',
											array(
												'id'=>_root::getParam('id'),
												'action'=>$sLink
											)
										) ?>"><?php echo $sLibelle ?></a></li>
	<?php else:?>
		<li><a href="<?php echo $this->getLink(
											'builder::editembedded',
											array(
												'id'=>_root::getParam('id'),
												'action'=>$sLink
											)
					) ?>#createon"><?php echo $sLibelle ?></a></li>
	<?php endif;?>
<?php endforeach;?>
</ul>
<a id="createon" name="createon"></a>
