<div class="table">
<form action="" method="POST">
<table>
	<tr>
		<th>Table</th>
		<td><input type="text" name="sTable"  style="width:200px;"/></td>
	</tr>
	<tr>
		<th><?php echo tr('label_Champs')?></th>
		<td>id (primaire)<br /><textarea name="sField"  style="width:200px;height:100px"></textarea></td>
	</tr>
</table>
<?php echo tr('label_EntrezLesActions')?>
<input type="submit" value="<?php echo tr('label_Generer')?>" />
</form>
</div>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
