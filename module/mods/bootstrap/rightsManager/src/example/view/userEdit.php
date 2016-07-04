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

<table class="table table-striped">
	
	<tr>
		<th>Login</th>
		<td><?php echo $this->oUser->#exampleUser_login#?></td>
	</tr>
	<tr>
		<th>Groupe</th>
		<td><?php echo $oForm->getSelect('#exampleUser_groupsId#',$this->tJoinGroup,array('class'=>'form-control'));?></td>
	</tr>

</table>

<p>
	<input class="btn btn-success" type="submit" value="Modifier" /> <a class="btn btn-link" href="<?php echo $this->getLink('#examplemodule#::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
