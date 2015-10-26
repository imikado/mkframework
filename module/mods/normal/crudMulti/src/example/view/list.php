<table class="tb_list">
	<tr>
		#icith#
	</tr>
	<?php if($this->#tExamplemodel#):?>
		<?php foreach($this->#tExamplemodel# as $#oExamplemodel#):?>
		<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
			#ici#
		</tr>	
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="#colspan#">Aucune ligne</td>
		</tr>
	<?php endif;?>
</table>
#linkEditList#
