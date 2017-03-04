<?php
/*
 examplemodule
 *
 examplePermissionId

 exampleUser_login
 exampleUser_id
 exampleUser_groupsId
 * */
?>
<h2>Liste des permissions</h2>
<table class="table table-striped">
	<tr>
		<th>Groupe</th>
		<th>Action</th>
		<th>Element</th>
		<th></th>
	</tr>
	<?php if($this->tPermission):?>
		<?php foreach($this->tPermission as $oPermission):?>
		<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
			<td><?php echo $oPermission->groupName?></td>
			<td><?php echo $oPermission->actionName?></td>
			<td><?php echo $oPermission->itemName?></td>
			<td>

				<a href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::edit',array(
										'id'=>$oPermission->VARpermission_idENDVAR
									)
							)?>">Modifier</a>
				|
				<a href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::delete',array(
										'id'=>$oPermission->VARpermission_idENDVAR
									)
							)?>">Supprimer</a>
			</td>
		</tr>
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="5">Aucune ligne</td>
		</tr>
	<?php endif;?>
</table>
<p><a class="btn btn-primary" href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::new') ?>">New</a></p>

<h2>Liste des utilisateurs</h2>
<table class="table table-striped">
	<tr>
		<th>User</th>
		<th>Groupe</th>
		<th></th>
	</tr>
	<?php if($this->tUser):?>
		<?php foreach($this->tUser as $oUser):?>
		<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
			<td><?php echo $oUser->VARuser_loginENDVAR?></td>
			<td><?php if(isset($this->tJoinGroup[$oUser->VARuser_fk_group_idENDVAR])):
				echo $this->tJoinGroup[$oUser->VARuser_fk_group_idENDVAR];
			 endif;?></td>
			 <td><a href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleRightsManagerENDVAR::editUser',array(
										'id'=>$oUser->VARuser_idENDVAR
									)
							)?>">Modifier</a></td>
		</tr>
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="3">Aucune ligne</td>
		</tr>
	<?php endif;?>
</table>
