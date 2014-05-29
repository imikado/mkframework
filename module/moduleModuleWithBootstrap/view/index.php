<h1>Cr&eacute;er un module avec Bootstrap</h1>
<div class="table">
<form action="" method="POST">
	<table>
		<tr>
			<th>Module</th>
			<td><input name="module"  style="width:200px;"/></td>
		</tr>
		<tr>
			<th>Actions</th>
			<td><textarea name="actions" style="width:200px;height:100px"></textarea></td>
		</tr>
	</table>
	Entrez les actions suivi d'un retour chariot
	<input type="submit" value="G&eacute;n&eacute;rer"/>
</form>
</div>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
