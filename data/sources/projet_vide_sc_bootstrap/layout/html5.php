<!DOCTYPE html>
<html lang="fr">
<head>
<title>chart</title>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script src="js/main.js" type="text/javascript"></script>
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo _root::getLink('article::newsrss') ?>"/>
<meta charset="utf-8">
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
