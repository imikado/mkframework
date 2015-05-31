<div class="new">
<form action="" method="POST">

<?php echo tr('builder::new_nomDuProjetAcreer')?> <input type="text" name="projet" />
<input type="submit" value="<?php echo tr('builder::new_creer')?>" />

<p><input type="radio" name="opt" value="" checked="checked" /> <?php echo tr('builder::new_applicationVide')?> 
<br />
<input type="radio" name="opt" value="withexamples" /> <?php echo tr('builder::new_applicationAvecExemples')?>
<br />
<input type="radio" name="opt" value="withBootstrap" /> <?php echo tr('builder::new_applicationComptBootstrap')?></p>

<br />
<p style="padding:6px">
	<?php echo tr('builder::new_applicationAvecExemplesAsterisk')?>
</p>

<p style="padding:6px">
	<?php echo tr('builder::new_applicationComptBootstrapAsterisk')?>
<br/>
<?php echo tr('builder::new_applicationComptBootstrapPlusdInfos')?>: <a href="http://getbootstrap.com/" target="_blank">http://getbootstrap.com/</a>
</p>

<?php if($this->iswritable==0):?>
<p style="padding:6px;color:red"><?php echo sprintf(tr('builder::new_errorVotreRepertoirePasInscriptible'),_root::getConfigVar('path.generation'))?> </p>
<?php endif;?>

</form>
</div>
