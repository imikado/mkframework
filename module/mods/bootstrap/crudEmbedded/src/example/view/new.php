<?php 
$oForm=new Plugin\Form($this->#oExamplemodel#);
$oForm->setMessage($this->tMessage);
?>
<form class="form-horizontal" action="" method="POST" #enctype#>
<input type="hidden" name="formmodule" value="#examplemodule#" />

	#ici#
	

<?php echo $oForm->getToken('token',$this->token)?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-success" value="Ajouter" /> <a class="btn btn-link" href="<?php echo module_#examplemodule#::getLink('list')?>">Annuler</a>
	</div>
</div>
</form>
