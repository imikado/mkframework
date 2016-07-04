<table class="<?php echo $this->sClass?>">
	<thead>
		<tr>
			<?php foreach($this->tHeader as $sLabel):?>
			<th><?php echo $sLabel?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
	<?php if($this->tLine):?>
		<?php foreach($this->tLine as $tDetail):?>
		<tr <?php echo $tDetail['options']?>>
			<?php foreach($tDetail['cell'] as $i => $value):?>
			<td <?php if($this->tClassColumn and isset($this->tClassColumn[$i])):?>class="<?php echo $this->tClassColumn[$i]?>"<?php endif;?>><?php echo $value?></td>
			<?php endforeach;?>
		</tr>
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="<?php echo count($this->tHeader);?>">Aucune ligne</td>
		</tr>
	<?php endif;?>
	</tbody>
</table>
