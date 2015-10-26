<?php 
/*
 examplemodule
 * 
 exampleUser_login
exampleUser_groupsId

 * */


$oForm=new plugin_form($this->oUser);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" >

<table class="tb_edit">
	
	<tr>
		<th>Login</th>
		<td><?php echo $this->oUser->#exampleUser_login#?></td>
	</tr>
	<tr>
		<th>Groupe</th>
		<td><?php echo $oForm->getSelect('#exampleUser_groupsId#',$this->tJoinGroup);?></td>
	</tr>

</table>

<p>
	<input type="submit" value="Modifier" /> <a href="<?php echo $this->getLink('#examplemodule#::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
