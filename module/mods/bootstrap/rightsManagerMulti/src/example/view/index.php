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
				
				<a href="<?php echo $this->getLink('#examplemodule#::edit',array(
										'id'=>$oPermission->#examplePermissionId#
									) 
							)?>">Modifier</a>
				|
				<a href="<?php echo $this->getLink('#examplemodule#::delete',array(
										'id'=>$oPermission->#examplePermissionId#
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
<p><a class="btn btn-primary" href="<?php echo $this->getLink('#examplemodule#::new') ?>">New</a></p>

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
			<td><?php echo $oUser->#exampleUser_login#?></td>
			<td><?php $tGroup=#model_examplemodel#::getInstance()->findListGroupByUser($oUser->#exampleUser_id#);
			$tGroupName=array();
			if($tGroup){
				foreach($tGroup as $grpId){
					if(isset($this->tJoinGroup[$grpId])){
						$tGroupName[]=$this->tJoinGroup[$grpId];
					}
				}
				echo implode(',',$tGroupName);
			}
			?></td>
			 <td><a href="<?php echo $this->getLink('#examplemodule#::editUser',array(
										'id'=>$oUser->#exampleUser_id#
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
