<h2><?php echo tr('pageDinscription') ?></h2>
<?php
$oForm = new plugin_sc_form($this->oUser);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" class="form-signin" role="form">

	<p>
		<label><?php echo tr('login') ?></label>
		<?php echo $oForm->getInputText('VARloginFieldENDVAR', array('class' => 'form-control')) ?>
	</p>
	<p>
		<label><?php echo tr('motDePasse') ?></label>
		<?php echo $oForm->getInputPassword('VARpasswordFieldENDVAR', array('type' => 'password', 'class' => 'form-control')) ?>

	</p>
	<p>
		<label><?php echo tr('confirmezMotDePasse') ?></label>
		<?php echo $oForm->getInputPassword('VARpasswordFieldENDVARConfirm', array('type' => 'password', 'class' => 'form-control')) ?>
	</p>


	<p><input class="btn" type="submit" value="<?php echo tr('creerCompte') ?>" /> <a href="<?php echo _root::getLink('VARmoduleParentENDVAR_VARmoduleChildENDVAR::login') ?>"><?php echo tr('pageDeLogin') ?></a> </p>


	<?php if ($this->tMessage and isset($this->tMessage['success'])): ?>
		<p><?php echo implode($this->tMessage['success']) ?> </p>
	<?php endif; ?>
</form>
