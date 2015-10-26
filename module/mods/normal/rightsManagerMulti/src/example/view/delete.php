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

<table class="tb_list">
	
	<tr>
		<th>Groupe</th>
		<th>Action</th>
		<th>Element</th>
	</tr>
	
	<tr>
		<td>
			<?php if(isset($this->tJoinGroup) and isset($this->tJoinGroup[$this->oPermission->#exampleGroupId#])): echo $this->tJoinGroup[$this->oPermission->#exampleGroupId#]; endif; ?>
		</td>
		
		<td>
			<?php if(isset($this->tJoinAction) and isset($this->tJoinAction[$this->oPermission->#exampleActionId#])): echo $this->tJoinAction[$this->oPermission->#exampleActionId#]; endif; ?>
		</td>
		
		<td>
			<?php if(isset($this->tJoinItem) and isset($this->tJoinItem[$this->oPermission->#exampleItemId#])): echo $this->tJoinItem[$this->oPermission->#exampleItemId#]; endif; ?>
		</td>
	</tr>

</table>

<p>
	Confirmez-vous la suppression ?<input type="submit" value="Oui" /> <a href="<?php echo $this->getLink('#examplemodule#::list')?>">Non</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
