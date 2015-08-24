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
				
				<?php //links?>
				
				
			</td>
		</tr>	
		<?php endforeach;?>
	<?php else:?>
		<tr>
			<td colspan="<?php //colspan?>">Aucune ligne</td>
		</tr>
	<?php endif;?>
</table>
<?php //linknew?>
