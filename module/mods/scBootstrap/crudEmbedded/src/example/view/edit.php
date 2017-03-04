<?php
$oForm = new plugin_sc_form($this->VARoTableENDVAR);
$oForm->setMessage($this->tMessage);
?>
<form class="form-horizontal" action="" method="POST" VARenctypeENDVAR>

	VARfieldsENDVAR

	<?php echo $oForm->getToken('token', $this->token) ?>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-success" value="<?php echo tr('Modifier') ?>" /> <a class="btn btn-link" href="<?php echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('list') ?>"><?php echo tr('annuler') ?></a>
		</div>
	</div>
</form>
