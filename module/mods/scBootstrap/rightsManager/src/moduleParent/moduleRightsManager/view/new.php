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
			<?php echo $oForm->getSelect('VARpermission_fk_group_idENDVAR',$this->tJoinGroup,array('class'=>'form-control'));?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('VARpermission_fk_group_idENDVAR_text',array('class'=>'form-control'))?>
		</td>

		<td>
			<?php echo $oForm->getSelect('VARpermission_fk_action_idENDVAR',$this->tJoinAction,array('class'=>'form-control'));?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('VARpermission_fk_action_idENDVAR_text',array('class'=>'form-control'))?>
		</td>

		<td>
			<?php echo $oForm->getSelect('VARpermission_fk_item_idENDVAR',$this->tJoinItem,array('class'=>'form-control'));?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('VARpermission_fk_item_idENDVAR_text',array('class'=>'form-control'))?>
		</td>
	</tr>

</table>

<p>
	<input class="btn btn-success" type="submit" value="Ajouter" /> <a class="btn btn-link" href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
