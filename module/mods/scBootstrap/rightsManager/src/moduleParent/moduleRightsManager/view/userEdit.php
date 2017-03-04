<?php
$oForm = new plugin_sc_form($this->oUser);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" >

<table class="table table-striped">

	<tr>
		<th>Login</th>
		<td><?php echo $this->oUser->VARuser_loginENDVAR?></td>
	</tr>
	<tr>
		<th>Groupe</th>
		<td><?php echo $oForm->getSelect('VARuser_fk_group_idENDVAR',$this->tJoinGroup,array('class'=>'form-control'));?></td>
	</tr>

</table>

<p>
	<input class="btn btn-success" type="submit" value="Modifier" /> <a class="btn btn-link" href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
