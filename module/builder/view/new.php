<div class="new">
<form action="" method="POST">

&nbsp;Nom du projet &agrave; cr&eacute;er <input type="text" name="projet" />
<input type="submit" value="Creer" />

<p><input type="radio" name="opt" value="" checked="checked" /> Application vide 
<br />
<input type="radio" name="opt" value="withexamples" /> Application avec Exemples*
<br />
<input type="radio" name="opt" value="withBootstrap" /> Application compatible bootstrap**</p>

<br />
<p style="padding:6px">
* Si cette case est coch&eacute;e: 
au moment de la g&eacute;n&eacute;ration de votre nouvelle application, des modules de bases ainsi que des classes exemples seront g&eacute;n&eacute;r&eacute;es.
</p>

<p style="padding:6px">
** Si cette case est coch&eacute;e: 
au moment de la g&eacute;n&eacute;ration de votre nouvelle application, un layout spécifique bootstrap sera cr&eacute;&eacute; et le menu de builder contiendra des actions compatibles avec bootstrap.<br/>
Plus d'informations sur bootstrap: <a href="http://getbootstrap.com/" target="_blank">http://getbootstrap.com/</a>
</p>

<?php if($this->iswritable==0):?>
<p style="padding:6px;color:red">Erreur: votre r&eacute;pertoire <u><?php echo _root::getConfigVar('path.generation')?></u> doit &ecirc;tre inscriptible </p>
<?php endif;?>

</form>
</div>
