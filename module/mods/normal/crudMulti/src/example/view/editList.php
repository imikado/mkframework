<form action="" method="POST">
<?php $oForm=new plugin_formMultiRow();
$oForm->setMessage($this->tMessage) ?>
<table class="tb_list">
	<tr>
		#icith#
		<th></th>
	</tr>
	<?php if($this->#tExamplemodel#):?>
		<?php foreach($this->#tExamplemodel# as $#oExamplemodel#):?>
		<tr <?php echo plugin_tpl::alternate(array('','class="alt"'))?>>
			<?php $oForm->add($#oExamplemodel#)?>
			#ici#
			<td>
				
				<?php echo $oForm->getInputHidden('#pkey#')?>

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

<p><input type="submit" value="Enregistrer"/> #linkList#</p>
</form>