<ul class="projets">
	<?php if ($this->tProjet): ?>
		<?php foreach ($this->tProjet as $sProjet): ?>
			<li <?php if (_root::getParam('id') == $sProjet): ?>class="selectionne"<?php endif; ?>>
				<span><?php echo $sProjet ?></span>

				<a href="<?php
				echo _root::getLink(
					'builder::edit', array('id' => $sProjet)
				)
				?>"><?php echo tr('menuNavProject_link_edit') ?></a>

				<a href="<?php
				echo _root::getLink(
					'code::index', array('project' => $sProjet)
				)
				?>"><?php echo tr('menuNavProject_link_explore') ?></a>


				<a target="_blank" href="<?php echo _root::getConfigVar('path.generation') ?><?php echo $sProjet ?>"><?php echo tr('menuNavProject_link_gotoSite') ?></a>
			</li>
	<?php endforeach; ?>
<?php endif; ?>
</ul>
