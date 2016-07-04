<?php
$sParamOrder=module_table::getParam('order');
$sParamSide=module_table::getParam('side');
?>
<table class="<?php echo $this->sClass?>">
	<thead>
		<tr>
			<?php foreach($this->tHeader as $i =>$tDetail):?>
			<th <?php if($this->tClassColumn and isset($this->tClassColumn[$i])):?>class="<?php echo $this->tClassColumn[$i]?>"<?php endif;?> style="vertical-align:top"><?php echo $tDetail['label']?><br />
				<?php if(isset($tDetail['order'])):?>
					<a href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'asc'))?>"><img src="css/images/flecheUp<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='asc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
					<a href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'desc'))?>"><img src="css/images/flecheDown<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='desc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
				<?php endif;?></th>
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
