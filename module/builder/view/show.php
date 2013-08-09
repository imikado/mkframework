<table>
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( in_array($sColumn,$this->tId)) continue;?>
	<tr>
		<th><?php echo $sColumn ?></th>
		<td><?php echo $this->oArticle->$sColumn ?></td>
	</tr>
<?php endforeach;?>
</table>