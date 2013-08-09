<table class="tb_list">
	<tr>
		<?php //icith?>
		<th></th>
	</tr>
	<?php if($this->tExamplemodel):?>
	<?php foreach($this->tExamplemodel as $oExamplemodel):?>
	<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
		<?php //ici?>
		<td>
			
			<a href="<?php echo module_examplemodule::getLink('edit',array(
													'id'=>$oExamplemodel->getId()
												) 
										)?>">Edit</a>
			|
			<a href="<?php echo module_examplemodule::getLink('show',array(
													'id'=>$oExamplemodel->getId()
												) 
										)?>">Show</a>
			|
			<a href="<?php echo module_examplemodule::getLink('delete',array(
													'id'=>$oExamplemodel->getId()
												) 
										)?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo module_examplemodule::getLink('new') ?>">New</a></p>
<?php/*variables
#lignetd
		<td>exampletd</td>
#fin_lignetd

#ligneth
		<th>exampleth</th>
#fin_ligneth

#input<?php echo $oExamplemodel->examplecolumn ?>#fin_input
#textarea<?php echo $oExamplemodel->examplecolumn ?>#fin_textarea
#select<?php if(isset($this->tJoinexamplemodel[$oExamplemodel->examplecolumn])){ echo $this->tJoinexamplemodel[$oExamplemodel->examplecolumn];}else{ echo $oExamplemodel->examplecolumn ;}?>#fin_select
#upload<?php echo $oExamplemodel->examplecolumn ?>#fin_upload

variables*/?>
