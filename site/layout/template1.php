<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Builder</title>
<link rel="stylesheet" type="text/css" href="site/css/main.css" media="screen" />
<script src="site/js/main.js" type="text/javascript"></script>

</head>
<body>

<div class="main">

	<div class="menu">
		<p class="top">
		<a <?php if(_root::getConfigVar('language.default')=='fr'):?>class="default"<?php endif;?> href="<?php echo _root::getLink('builder::lang',array('switch'=>'fr'))?>">FR</a> | 
		<a <?php if(_root::getConfigVar('language.default')=='en'):?>class="default"<?php endif;?> href="<?php echo _root::getLink('builder::lang',array('switch'=>'en'))?>">EN</a>
		</p>
		<?php echo $this->load('menu') ?>
	</div>
	<div class="content">
		<?php echo $this->load('main') ?>
	</div>
</div>

</body>
</html>