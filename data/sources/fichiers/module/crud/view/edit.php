<?php $oPluginHtml=new plugin_html?>
<form action="" method="POST" <?php //enctype?>>
<table class="tb_edit">
	<?php //ici?>
</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" /> <a href="<?php echo $this->getLink('examplemodule::list')?>">Annuler</a>
</form>
<?php/*variables
#lignetd
	<tr>
		<th>examplecolumn</th>
		<td>exampletd</td>
	</tr>
#fin_lignetd

#input<input name="examplecolumn" value="<?php echo $this->oExamplemodel->examplecolumn ?>" /><?php if($this->tMessage and isset($this->tMessage['examplecolumn'])): echo implode(',',$this->tMessage['examplecolumn']); endif;?>#fin_input
#textarea<textarea name="examplecolumn"><?php echo $this->oExamplemodel->examplecolumn ?></textarea><?php if($this->tMessage and isset($this->tMessage['examplecolumn'])): echo implode(',',$this->tMessage['examplecolumn']); endif;?>#fin_textarea
#select<?php echo $oPluginHtml->getSelect('examplecolumn',$this->tJoinexamplemodel,$this->oExamplemodel->examplecolumn)?><?php if($this->tMessage and isset($this->tMessage['examplecolumn'])): echo implode(',',$this->tMessage['examplecolumn']); endif;?>#fin_select
#upload<input type="file" name="examplecolumn" /><?php if($this->tMessage and isset($this->tMessage['examplecolumn'])): echo implode(',',$this->tMessage['examplecolumn']); endif;?>#fin_upload

variables*/?>
