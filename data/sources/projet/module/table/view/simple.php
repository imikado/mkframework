<table class="<?php echo $this->sClass?>">
	<thead>
		<tr>
			<?php foreach($this->tHeader as $sLabel):?>
			<th><?php echo $sLabel?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->tLine as $tDetail):?>
		<tr <?php echo $tDetail['options']?>>
			<?php foreach($tDetail['cell'] as $i => $value):?>
			<td <?php if($this->tClassColumn and isset($this->tClassColumn[$i])):?>class="<?php echo $this->tClassColumn[$i]?>"<?php endif;?>><?php echo $value?></td>
			<?php endforeach;?>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
