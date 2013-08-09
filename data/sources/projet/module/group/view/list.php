<?php $tHidden=array('id')?>
<table>
	<tr>
		
		<th>name</th>

		<th></th>
	</tr>
	<?php if($this->tGroup):?>
	<?php foreach($this->tGroup as $oGroup):?>
	<tr>
		
		<td><?php echo $oGroup->name ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('group::edit',array(
													'id'=>$oGroup->getId()
												) 
										)?>">Edit</a>
			|
			<a href="<?php echo $this->getLink('group::delete',array(
													'id'=>$oGroup->getId()
												) 
										)?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('group::new') ?>">New</a></p>

