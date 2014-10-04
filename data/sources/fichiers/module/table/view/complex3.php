<?php
$sParamOrder=module_table::getParam('order');
$sParamSide=module_table::getParam('side');

if($this->tStyleColumn):?>
<style>
	<?php foreach($this->tStyleColumn as $i => $style):?>
		<?php if($style==null) continue;?>
		.tableWidth_<?php echo $i?>{
			<?php echo $style;?>
		}
	<?php endforeach;?>
	
	<?php if(count($this->tLine) > $this->bodyNbLine):?>
		.tHead{
			width:<?php echo $this->tableWidth?>px;
			display:block;
		}
		.tbody{
			width:<?php echo $this->tableWidth?>px;
			display:block;
			height:<?php echo $this->bodyHeight?>px;
			overflow-y:scroll;
			float:left;
		}
	<?php endif;?>
	
</style>
<?php endif;?>
<table class="<?php echo $this->sClass?>">
	<thead class="tHead">
		<tr>
			<?php foreach($this->tHeader as $i => $tDetail):?>
			<th class="tableWidth_<?php echo $i?>" style="vertical-align:top"><?php echo $tDetail['label']?><br />
				<?php if(isset($tDetail['order'])):?>
					<a href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'asc'))?>"><img src="css/images/flecheUp<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='asc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
					<a href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'desc'))?>"><img src="css/images/flecheDown<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='desc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
				<?php endif;?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody class="tbody">
	<?php if($this->tLine):?>
		<?php foreach($this->tLine as $i => $tDetail):?>
		<tr <?php echo $tDetail['options']?>>
			<?php foreach($tDetail['cell'] as $i => $value):?>
			<td class="tableWidth_<?php echo $i?>"><a href="<?php echo $tDetail['link']?>"><?php echo $value?></a></td>
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

