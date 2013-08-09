<table class="tb_delete">
	<?php //ici?>
</table>

<form action="" method="POST">
<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Confirmer la suppression" /> <a href="<?php echo $this->getLink('examplemodule::list')?>">Annuler</a>
</form>
<?php/*variables
#lignetd
	<tr>
		<th>examplecolumn</th>
		<td>exampletd</td>
	</tr>
#fin_lignetd

#input<?php echo $this->oExamplemodel->examplecolumn ?>#fin_input
#textarea<?php echo $this->oExamplemodel->examplecolumn ?>#fin_textarea
#select<?php echo $this->tJoinexamplemodel[$this->oExamplemodel->examplecolumn]?>#fin_select
#upload<?php echo $this->oExamplemodel->examplecolumn ?>#fin_upload

variables*/?>
