<?php

$oForm = new plugin_form($this->oData);
$oForm->setMessage($this->tMessage);
?>

		
<form class="form-horizontal" action="" method="POST" />
	 
	
	#colonnestr#
	 

<div class="form-group">
	<div class="col-sm-2"></div>
	<div class="col-sm-10"><input class="btn btn-primary" type="submit" value="Modifier" /></div>
</div>
			
<?php echo $oForm->getToken('token',$this->token)?>
</form>