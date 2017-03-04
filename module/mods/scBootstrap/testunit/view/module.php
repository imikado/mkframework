<?php include('module/mods/all/testunit/view/index.php') ?>

<style>
p{
	margin:0px;
}
</style>

<?php
$tOption=array(
	 
	module_mods_all_testunit::FORM =>'formulaire',
	module_mods_all_testunit::DISPLAY =>'affichage',
	module_mods_all_testunit::DISPLAYFROMBDD =>'affichagefrombdd',

);
?>

<h2 class="title"><?php echo tr('module')?></h2>
<?php if($this->bStillExist):?><p class="error"><?php echo trR('fichierExisteDeja',array('#FICHIER#'=>$this->sPathStillExist))?></p><?php endif;?>
<h3><?php echo tr('methods')?></h3>
<form action="" method="POST">

<table style="margin-left:10px">
<?php if($this->tMethod):?>
<?php foreach($this->tMethod as $i => $sMethod):?>
	<tr>
		<td><input type="hidden"  name="tMethod[<?php echo $i?>]" value="<?php echo $sMethod?>"/>  <?php echo $sMethod?></td>

		<td>

		<select name="tOption[<?php echo $i?>]">
			<option></option>
			<?php foreach($tOption as $sKey => $sLabel):?>
			<option value="<?php echo $sKey?>"><?php echo tr($sLabel)?></option>
			<?php endforeach;?>
		</select>

	
	</td>
		

	</tr>
<?php endforeach;?>
<?php endif;?>
</table>

<p style="margin:14px 0px"><input type="submit" value="<?php echo tr('Generer')?>"/></p>

</form>

<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>