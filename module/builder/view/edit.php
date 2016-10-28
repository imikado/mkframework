<?php if (_root::getConfigVar('builder.selected.group.label')): ?>
	<h1 style="color:#233035;border-bottom:3px solid #233035;background:transparent">
		<a style="color:#233035;text-decoration: underline" href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'))) ?>"><?php echo tr('menuApplication') ?></a> /

		<?php echo _root::getConfigVar('builder.selected.group.label') ?> / <?php echo _root::getConfigVar('builder.selected.label') ?>
	</h1>
<?php endif; ?>
