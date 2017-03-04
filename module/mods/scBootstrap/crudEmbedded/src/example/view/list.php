<table class="table table-striped">
	<tr>
		VARlistThENDVAR
		<th></th>
	</tr>
	<?php if ($this->VARtTableENDVAR): ?>
		<?php foreach ($this->VARtTableENDVAR as $VARoTableENDVAR): ?>
			<tr <?php echo plugin_tpl::alternate(array('', 'class="alt"')) ?>>
				VARlistTdENDVAR
				<td>
					<a class="btn btn-success" href="<?php
					echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('edit', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('edit') ?></a>

					<a class="btn btn-default" href="<?php
					echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('show', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('show') ?></a>

					<a class="btn btn-danger" href="<?php
					echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('delete', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('delete') ?></a>


				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="VARcolspanENDVAR">Aucune ligne</td>
		</tr>
	<?php endif; ?>
</table>

<p><a class="btn btn-primary" href="<?php echo module_VARmoduleParentENDVAR_VARmoduleChildENDVAR::getLink('new') ?>"><?php echo tr('New') ?></a></p>
