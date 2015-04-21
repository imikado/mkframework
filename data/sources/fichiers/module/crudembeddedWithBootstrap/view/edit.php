<?php 
$oForm=new plugin_form($this->oExamplemodel);
$oForm->setMessage($this->tMessage);
?>
<form class="form-horizontal" action="" method="POST" <?php //enctype?>>
<input type="hidden" name="formmodule" value="examplemodule" />

	<?php //ici?>

<?php echo $oForm->getToken('token',$this->token)?>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-success" value="Modifier" /> <a class="btn btn-link" href="<?php echo module_examplemodule::getLink('list')?>">Annuler</a>
	</div>
</div>
</form>

