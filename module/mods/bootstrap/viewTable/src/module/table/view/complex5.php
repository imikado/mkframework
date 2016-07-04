<?php
$sParamOrder=module_table::getParam('order');
$sParamSide=module_table::getParam('side');

$sVarPage='page';
$sValuePage=module_table::getParam($sVarPage,0);

$oPagination=new plugin_jquery('page',array('page','order','side'));
$oPagination->addLinkUpdateElement(_root::getLink($this->ajaxLink,array('tablepage'=>'$page','tableorder'=>'$order','tableside'=>'$side')),'tablep');
echo $oPagination->getJs();
	
?>
<div id="tablep">
<table class="<?php echo $this->sClass?>">
	<thead>
		<tr>
			<?php foreach($this->tHeader as $tDetail):?>
			<th style="vertical-align:top"><?php echo $tDetail['label']?><br />
				<?php if(isset($tDetail['order'])):?>
					<a onclick="page(<?php echo $sValuePage?>,'<?php echo $tDetail['order']?>','asc');return false;" href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'asc',$sVarPage=>$sValuePage))?>"><img src="css/images/flecheUp<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='asc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
					<a onclick="page(<?php echo $sValuePage?>,'<?php echo $tDetail['order']?>','desc');return false;" href="<?php echo module_table::getLink(null,array('order'=>$tDetail['order'],'side'=>'desc',$sVarPage=>$sValuePage))?>"><img src="css/images/flecheDown<?php if($sParamOrder==$tDetail['order'] and $sParamSide=='desc'):?>On<?php else:?>Off<?php endif;?>.png"/></a> 
				<?php endif;?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->tLine as $tDetail):?>
		<tr <?php echo $tDetail['options']?>>
			<?php foreach($tDetail['cell'] as $value):?>
			<td><?php echo $value?></td>
			<?php endforeach;?>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php echo $this->oModulePagination->show()?>
</div>
<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
