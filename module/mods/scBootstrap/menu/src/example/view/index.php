<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">examplesite</a>
		</div>
		<div class="collapse navbar-collapse">

			<ul class="nav navbar-nav">
			<?php foreach($this->tLink as $sLibelle => $sLink): ?>
				<?php if(_root::getParamNav()==$sLink):?>
					<li class="active"><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
				<?php else:?>
					<li><a href="<?php echo $this->getLink($sLink) ?>"><?php echo $sLibelle ?></a></li>
				<?php endif;?>
			<?php endforeach;?>
			</ul>
				
		</div><!--/.nav-collapse -->
	</div>
</div>
