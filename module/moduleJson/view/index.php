<h1><?php echo tr('menuProject_link_createDatabaseJson')?></h1>
<div class="table">
<form action="" method="POST">
<table>
	<tr>
		<th>Table</th>
		<td><input type="text" name="sTable"  style="width:200px;"/></td>
	</tr>
	<tr>
		<th>Champs</th>
		<td>id (primaire)<br /><textarea name="sField"  style="width:200px;height:100px"></textarea></td>
	</tr>
</table>
Entrez les champs suivi d'un retour chariot
<input type="submit" value="G&eacute;n&eacute;rer" />
</form>
</div>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
