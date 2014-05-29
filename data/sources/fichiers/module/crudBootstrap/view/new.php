<?php $oPluginHtml=new plugin_html?>
<form  class="form-horizontal" action="" method="POST" <?php //enctype?>>

	<?php //ici?>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-success" value="Modifier" /> <a class="btn btn-link" href="<?php echo $this->getLink('examplemodule::list')?>">Annuler</a>
	</div>
</div>
</form>
