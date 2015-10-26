<form action="" method="POST" class="form-signin" role="form">
	<h2 class="form-signin-heading">Authentification</h2>
	
	<input class="form-control" placeholder="Nom d'utilisateur" type="text" name="login" autocomplete="off"/>
	<input class="form-control" placeholder="Mot de passe" type="password" name="password" autocomplete="off"/>

 
	<input type="submit" value="Se connecter" class="btn btn-lg btn-primary btn-block"/>

	<?php if($this->sError!=''):?>
		<p style="color:red"><?php echo $this->sError?></p>
	<?php endif;?>
</form>
