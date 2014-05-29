<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" <?php //enctype?>>
<table class="tb_edit">
	<?php //ici?>
	
	<tr>
		<th></th>
		<td>
			<p>
				<input type="submit" value="Modifier" /> <a href="<?php echo $this->getLink('examplemodule::list')?>">Annuler</a>
			</p>
		</td>
	</tr>
</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

</form>

