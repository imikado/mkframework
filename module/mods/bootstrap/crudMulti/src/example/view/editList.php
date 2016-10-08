<form action="" method="POST">
	<?php
	$oForm = new plugin_formMultiRow();
	$oForm->setMessage($this->tMessage)
	?>
	<table class="table table-striped">
		<tr>
			#icith#
			<th></th>
		</tr>
		<?php if($this->#tExamplemodel#):?>
		<?php foreach($this->#tExamplemodel# as $#oExamplemodel#):  ?>
		<tr <?php echo plugin_tpl::alternate(array('', 'class="alt"')) ?>>
			<?php $oForm->add($#oExamplemodel#)  ?>
			#ici#
			<td>

				<?php echo $oForm->getInputHidden('#pkey#') ?>

				#links#


			</td>
		</tr>
		<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="#colspan#">Aucune ligne</td>
		</tr>
		<?php endif; ?>
	</table>
	#linknew#


	<div class="form-group">
		<div style="text-align:right">
			<input type="submit" class="btn btn-success" value="Enregistrer" /> #linkList#
		</div>
	</div>

</form>