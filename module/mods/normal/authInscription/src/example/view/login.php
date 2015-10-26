<form action="" method="POST">
	
	<table>
		<tr>
			<th>Nom d'utilisateur</th>
			<td><input type="text" name="login" autocomplete="off"/></td>
		</tr>
		<tr>
			<th>Mot de passe</th>
			<td><input type="password" name="password" /></td>
		</tr>
	</table>
  
	<p><input type="submit" value="Se connecter" /> <a href="<?php echo _root::getLink('#MODULE#::inscription')?>">S'inscrire</a></p>

	<?php if($this->sError!=''):?>
		<p style="color:red"><?php echo $this->sError?></p>
	<?php endif;?>
</form>
