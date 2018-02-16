<?php

$oForm = new Plugin\Form($this->oData);
$oForm->setMessage($this->tMessage);
?>

		
<form action="" method="POST" />
	
<table class="tb_edit">
	
	#colonnestr#
		
</table>
		
<p><input type="submit" value="Modifier" /></p>
		
<?php echo $oForm->getToken('token',$this->token)?>
</form>