<form action="" method="POST" class="form-signin" role="form">
	<h2 class="form-signin-heading"><?php echo tr('Authentification') ?></h2>

	<input class="form-control" placeholder="<?php echo tr('login') ?>" type="text" name="login" autocomplete="off"/>
	<input class="form-control" placeholder="<?php echo tr('motDePasse') ?>" type="password" name="password" autocomplete="off"/>


	<input type="submit" value="<?php echo tr('seConnecter') ?>" class="btn btn-lg btn-primary btn-block"/>

	<?php if ($this->sError != ''): ?>
		<p style="color:red"><?php echo $this->sError ?></p>
	<?php endif; ?>
</form>
