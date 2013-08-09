<div class="new">
<form action="" method="POST">

&nbsp;Nom du projet &agrave; cr&eacute;er <input type="text" name="projet" />
<input type="submit" value="Creer" />
<br />
<input type="checkbox" name="withexamples" value="1" /> G&eacute;n&eacute;rer avec Exemples*

<br />
<p style="padding:6px">
* Si cette case est coch&eacute;e: 
au moment de la g&eacute;n&eacute;ration de votre nouvelle application, des modules de bases ainsi que des classes exemples seront g&eacute;n&eacute;r&eacute;es.
</p>

<?php if($this->iswritable==0):?>
<p style="padding:6px;color:red">Erreur: votre r&eacute;pertoire <u><?php echo _root::getConfigVar('path.generation')?></u> doit &ecirc;tre inscriptible </p>
<?php endif;?>

</form>
</div>
