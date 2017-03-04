<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>examplesite</title>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script src="js/main.js" type="text/javascript"></script>
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
