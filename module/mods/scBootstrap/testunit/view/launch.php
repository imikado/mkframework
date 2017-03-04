<style>
.ok{
color:green;
font-weight:bold;
}
.ko{
color:darkred;
font-weight:bold;
}

.expected{
color:darkblue;
font-weight:bold;
}
.actual{
color:darkred;
font-weight:bold;
}
.desc{
	border:1px dotted gray;
}
</style>
<div style="padding:10px">
<form action="" method="POST">
	<?php echo tr('cheminPhpUnit')?> 
	<select name="directory">
		<option value="local"><?php echo tr('local').module_builder::getTools()->getRootWebsite()?></option>
		<option value="absolu"><?php echo tr('absolu')?></option>
	</select>
	<input type="text" name="launcher" value="<?php echo $this->phpunit?>" /><input type="submit" value="<?php echo tr('lancer')?>"/></form>

<p><?php echo tr('phpUnitWebsite')?>: <a href="https://phpunit.de/">https://phpunit.de/</a></p>

<p class="desc"><?php echo trR('expleInstall',array('#chemin#'=>module_builder::getTools()->getRootWebsite()))?></p>
<div style="padding:16px">

<h1>Retour</h1>
<div style="font-size:12px;padding:4px">
<?php echo $this->detail?>
</div>

</div>
</div>
 
