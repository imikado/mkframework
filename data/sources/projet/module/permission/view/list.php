<?php $tHidden=array('id')?>
<table>
	<tr>
		
		<th>group</th>

		<th>action</th>

		<th>element</th>

		<th>allowdeny</th>

		<th></th>
	</tr>
	<?php if($this->tPermission):?>
	<?php foreach($this->tPermission as $oPermission):?>
	<tr>
		
		<td><?php echo $oPermission->findGroup()->name ?></td>

		<td><?php echo $oPermission->action ?></td>

		<td><?php echo $oPermission->element ?></td>

		<td><?php echo $oPermission->allowdeny ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('permission::edit',array(
													'id'=>$oPermission->getId()
												) 
										)?>">Edit</a>
			|
			
			<a href="<?php echo $this->getLink('permission::delete',array(
													'id'=>$oPermission->getId()
												) 
										)?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('permission::new') ?>">New</a></p>

