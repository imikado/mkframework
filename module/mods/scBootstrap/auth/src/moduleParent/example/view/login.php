<style>
.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

<form action="" method="POST" class="form-signin" role="form">
	<h2 class="form-signin-heading"><?php echo tr('Authentification') ?></h2>


	<label for="inputEmail" class="sr-only"><?php echo tr('login') ?></label>
	<input id="inputEmail" class="form-control" placeholder="<?php echo tr('login') ?>" type="text" name="login" autocomplete="off"/>

	<label for="inputPassword" class="sr-only"><?php echo tr('motDePasse') ?></label>
	<input id="inputPassword" class="form-control" placeholder="<?php echo tr('motDePasse') ?>" type="password" name="password" autocomplete="off"/>


	<p><button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo tr('seConnecter') ?></button></p>

	<?php if ($this->sError != ''): ?>
		<p style="color:red"><?php echo $this->sError ?></p>
	<?php endif; ?>
</form>
