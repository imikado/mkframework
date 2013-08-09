<?php $oPluginHtml=new plugin_html?>
<form action="<?php echo $this->getLink('auteur::save',array(
														) 
											)?>" method="POST">

<table>
	
	<tr>
		<th>nom</th>
		<td><input name="nom" /></td>
	</tr>

	<tr>
		<th>prenom</th>
		<td><input name="prenom" /></td>
	</tr>

</table>
<input type="submit" value="Modifier" />
</form>
