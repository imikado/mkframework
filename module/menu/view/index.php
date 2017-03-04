<ul>
	<?php foreach ($this->tLink as $sLibelle => $sLink): ?>
		<?php if (_root::getParamNav() == 'builder::edit' and $sLink == 'builder::list'): ?>
			<li class="selectionne"><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
		<?php elseif (preg_match('/' . $sLink . '/', $_SERVER['QUERY_STRING'])): ?>
			<li class="selectionne"><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
			<?php else: ?>
			<li><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>

	<li><a target="_blank" href="http://mkframework.com">MkFramework.com</a></li>
</ul>
