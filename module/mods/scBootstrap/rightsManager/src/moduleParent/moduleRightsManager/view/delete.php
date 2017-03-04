<?php
$oForm = new plugin_sc_form($this->oPermission);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" >

<table class="table table-striped">

	<tr>
		<th>Groupe</th>
		<th>Action</th>
		<th>Element</th>
	</tr>

	<tr>
		<td>
			<?php if(isset($this->tJoinGroup) and isset($this->tJoinGroup[$this->oPermission->VARpermission_fk_group_idENDVAR])): echo $this->tJoinGroup[$this->oPermission->VARpermission_fk_group_idENDVAR]; endif; ?>
		</td>

		<td>
			<?php if(isset($this->tJoinAction) and isset($this->tJoinAction[$this->oPermission->VARpermission_fk_action_idENDVAR])): echo $this->tJoinAction[$this->oPermission->VARpermission_fk_action_idENDVAR]; endif; ?>
		</td>

		<td>
			<?php if(isset($this->tJoinItem) and isset($this->tJoinItem[$this->oPermission->VARpermission_fk_item_idENDVAR])): echo $this->tJoinItem[$this->oPermission->VARpermission_fk_item_idENDVAR]; endif; ?>
		</td>
	</tr>

</table>

<p>
	Confirmez-vous la suppression ? <input class="btn btn-success" type="submit" value="Oui" /> <a  class="btn btn-danger" href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::list')?>">Non</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

<?php echo $oForm->getErrorMessageBox('message')?>

</form>
