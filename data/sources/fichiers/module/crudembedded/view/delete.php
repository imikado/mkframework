<form action="" method="POST">

<table class="tb_delete">
	<?php //ici?>
	<tr>
		<th></th>
		<td>
			<p>
				<input type="submit" value="Confirmer la suppression" /> <a href="<?php echo module_examplemodule::getLink('list')?>">Annuler</a>
			</p>
		</td>
	</tr>
</table>


<input type="hidden" name="formmodule" value="examplemodule" />
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

</form>

