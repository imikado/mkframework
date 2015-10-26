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
			<?php echo $oForm->getSelect('#exampleGroupId#',$this->tJoinGroup);?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('#exampleGroupId#_text')?>
		</td>
		
		<td>
			<?php echo $oForm->getSelect('#exampleActionId#',$this->tJoinAction);?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('#exampleActionId#_text')?>
		</td>
		
		<td>
			<?php echo $oForm->getSelect('#exampleItemId#',$this->tJoinItem);?>
			<br/>ou <br/>
			<?php echo $oForm->getInputText('#exampleItemId#_text')?>
		</td>
	</tr>

</table>

<p>
	<input type="submit" value="Ajouter" /> <a href="<?php echo $this->getLink('#examplemodule#::list')?>">Annuler</a>
</p>

<?php echo $oForm->getToken('token',$this->token)?>

</form>
