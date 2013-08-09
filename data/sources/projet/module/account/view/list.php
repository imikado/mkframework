<?php $tHidden=array('id')?>
<?php $tListGroup=model_group::getInstance()->getListSelect() ?>
<table>
	<tr>
		
		<th>login</th>

		<th>pass</th>

		<th>nom</th>

		<th>prenom</th>

		<th>groupe</th>

		<th></th>
	</tr>
	<?php if($this->tAccount):?>
	<?php foreach($this->tAccount as $oAccount):?>
	<tr>
		
		<td><?php echo $oAccount->login ?></td>

		<td>******</td>

		<td><?php echo $oAccount->nom ?></td>

		<td><?php echo $oAccount->prenom ?></td>

		<td><?php if(isset($tListGroup[ $oAccount->groupe])) echo $tListGroup[ $oAccount->groupe] ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('account::edit',array(
													'id'=>$oAccount->getId()
												) 
										)?>">Edit</a>
			|
			<a href="<?php echo $this->getLink('account::show',array(
													'id'=>$oAccount->getId()
												) 
										)?>">Show</a>
			|
			<a href="<?php echo $this->getLink('account::delete',array(
													'id'=>$oAccount->getId()
												) 
										)?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('account::new') ?>">New</a></p>

