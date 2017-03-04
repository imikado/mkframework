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
					echo $this->getLink('VARmoduleParentENDVAR_VARmoduleChildENDVAR::edit', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('edit') ?></a>

					<a class="btn btn-default" href="<?php
					echo $this->getLink('VARmoduleParentENDVAR_VARmoduleChildENDVAR::show', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('show') ?></a>

					<a class="btn btn-danger" href="<?php
					echo $this->getLink('VARmoduleParentENDVAR_VARmoduleChildENDVAR::delete', array(
					    'id' => $VARoTableENDVAR->VARkeyFieldENDVAR
						)
					)
					?>"><?php echo tr('delete') ?></a>


				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="VARcolspanENDVAR"><?php echo tr('AucuneLigne') ?></td>
		</tr>
	<?php endif; ?>
</table>

<p><a class="btn btn-primary" href="<?php echo $this->getLink('VARmoduleParentENDVAR_VARmoduleChildENDVAR::new') ?>"><?php echo tr('New') ?></a></p>
