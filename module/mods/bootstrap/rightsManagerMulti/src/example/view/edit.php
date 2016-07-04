<?php 
/*
 examplemodule
 * 
 exampleGroupId
 exampleActionId
 exampleItemId
 * */


$oForm=new plugin_form($this->oPermission);
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
			<?php echo $oForm->getSelect('#exampleGroupId#',$this->tJoinGroup,array('class'=>'form-control'));?>
		</td>
		
		<td>
			<?php echo $oForm->getSelect('#exampleActionId#',$this->tJoinAction,array('class'=>'form-control'));?>
		</td>
		
		<td>
			<?php echo $oForm->getSelect('#exampleItemId#',$this->tJoinItem,array('class'=>'form-control'));?>
		</td>
	</tr>

</table>

<p>
	<input class="btn btn-success" type="submit" value="Modifier" /> <a class="btn btn-link" href="<?php echo $this->getLink('#examplemodule#::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
