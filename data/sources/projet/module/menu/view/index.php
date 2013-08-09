<ul>
<?php foreach($this->tLink as $sLibelle => $sLink): ?>
	<?php if(_root::getParamNav()==$sLink):?>
		<li class="selectionne"><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
	<?php else:?>
		<li><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
	<?php endif;?>
	
<?php endforeach;?>

<?php if( _root::getAuth()->isConnected() ):?>
	<li><a href="<?php echo _root::getLink('auth::logout')?>">Se deconnecter</a></li>
<?php endif;?>

</ul>

