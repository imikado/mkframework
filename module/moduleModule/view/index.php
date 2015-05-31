<h1><?php echo tr('menuProject_link_createModule') ?></h1>

<div class="table">
<form action="" method="POST">
	<table>
		<tr>
			<th><?php echo tr('builder::edit_module_module')?></th>
			<td><input name="module"  style="width:200px;"/></td>
		</tr>
		<tr>
			<th><?php echo tr('builder::edit_module_actions')?></th>
			<td><textarea name="actions" style="width:200px;height:100px"></textarea></td>
		</tr>
	</table>
	<?php echo tr('builder::edit_module_entrezLesActionsSuivi')?>
	
	<input type="submit" value="<?php echo tr('builder::edit_module_generer')?>"/>
</form>
</div>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
