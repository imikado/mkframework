<?php 
$oForm=new plugin_form($this->#oExamplemodel#);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" #enctype#>
<input type="hidden" name="formmodule" value="#examplemodule#" />

<table class="tb_edit">
	#ici#
	
	<tr>
		<th></th>
		<td>
			<p>
				<input type="submit" value="Modifier" /> <a href="<?php echo module_#examplemodule#::getLink('list')?>">Annuler</a>
			</p>
		</td>
	</tr>
</table>

<?php echo $oForm->getToken('token',$this->token)?>

</form>

