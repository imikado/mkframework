<?php
$oForm = new plugin_sc_form($this->VARoTableENDVAR);
$oForm->setMessage($this->tMessage);
?>
<form class="form-horizontal" action="" method="POST">

	VARfieldsENDVAR

	<?php echo $oForm->getToken('token', $this->token) ?>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input class="btn btn-danger" type="submit" value="<?php echo tr('ConfirmerLaSuppression') ?>" /> <a class="btn btn-link" href="<?php echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('list') ?>"><?php echo tr('Annuler') ?></a>
		</div>
	</div>

</form>
