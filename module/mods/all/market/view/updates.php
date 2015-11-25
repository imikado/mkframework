<style>
.main .content table th{
	background:#fff;
	text-align: left;
}
.main .content table td{
	text-align: left;
}
.toUpdate td{
	color:#630505;
	font-weight: bold;
	vertical-align: top;
}
p{
	margin-top:20px;
	text-align: right;
}
.author{
	color:#777;
	 font-style: italic;
}
</style>
<?php if($this->message):?>
	<p class="error" style="text-align:center"><?php echo $this->message?></p>
<?php endif;?>
<form action="" method="POST">
<table>
	<tr>
		<th style="width:470px"></th>
		<th style="width:80px"><?php echo tr('local')?></th>
		<th style="width:80px"><?php echo tr('remote')?></th>
		<th></th>

	</tr>
	<?php foreach($this->tModule as $sLabel => $tVersion):?>
		<?php if($tVersion=='title'):?>
			<tr>
				<td colspan="4"><strong><?php echo $sLabel?></strong></td>
			</tr>
		<?php else:?>
		
		<tr <?php if($tVersion['local']<$tVersion['remote']):?>class="toUpdate"<?php endif;?>>
			<td style="padding-left:6px"><?php echo $sLabel?><br/><span class="author"><?php echo $tVersion['author']?></span></td>
			<td><?php echo $tVersion['local']?></td>
			<td><?php echo $tVersion['remote']?></td>
			<td><input <?php if($tVersion['local']>=$tVersion['remote']):?>disabled="disabled"<?php endif;?> type="checkbox" name="toUpdate[]" value="<?php echo $tVersion['id'] ?>"/></td>
		</tr>
		<?php endif;?>
	<?php endforeach;?>
</table>

<p><input type="submit" value="<?php echo tr('mettreAjour')?>"/></p>
</form>