<table class="tb_list">
	<tr>
		#icith#
		<th></th>
	</tr>
	<?php if($this->#tExamplemodel#):?>
		<?php foreach($this->#tExamplemodel# as $#oExamplemodel#):?>
		<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
			#ici#
			<td>
				
				#links#
				
				
			</td>
		</tr>	
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="#colspan#">Aucune ligne</td>
		</tr>
	<?php endif;?>
</table>
#linknew#
