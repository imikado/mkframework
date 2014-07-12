<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Site</title>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script src="js/main.js" type="text/javascript"></script>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo _root::getLink('article::newsrss') ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

<div class="main">
	<div class="menu"><?php echo $this->load('menu') ?></div>
	<div class="content">
		<?php echo $this->load('main') ?>
	</div>
</div>

</body>
</html>
