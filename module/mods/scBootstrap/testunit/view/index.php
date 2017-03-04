
<div class="smenu">
	<!--
	<p><?php echo tr('model') ?></p>
	<ul>
	<?php foreach ($this->tModelFile as $sFile): ?>
			<li <?php if ($sFile == _root::getParam('file')): ?>class="selectionne"<?php endif; ?> ><a href="<?php
		echo _root::getLink(_root::getRequest()->getParamNav(), array(
		    'id' => _root::getParam('id'),
		    'action' => _root::getParam('action'),
		    'saction' => 'model',
		    'file' => $sFile))
		?>#foo" /><?php echo $sFile ?></a></li>

<?php endforeach; ?>
	</ul>
	<p><?php echo tr('module') ?></p>
	<ul>
	<?php foreach ($this->tModuleFile as $sFile): ?>
			<li <?php if ($sFile == _root::getParam('file')): ?>class="selectionne"<?php endif; ?> ><a href="<?php
		echo _root::getLink(_root::getRequest()->getParamNav(), array(
		    'id' => _root::getParam('id'),
		    'action' => _root::getParam('action'),
		    'saction' => 'module',
		    'file' => $sFile))
		?>#foo" /><?php echo $sFile ?></a></li>

<?php endforeach; ?>
	</ul>
	-->
	<p><?php echo tr('lancer') ?></p>
	<ul>
		<li <?php if ('launch' == _root::getParam('saction')): ?>class="selectionne"<?php endif; ?> ><a target="_blank" href="<?php
													   echo _root::getLink(_root::getRequest()->getParamNav(), array(
													       'id' => _root::getParam('id'),
													       'action' => _root::getParam('action'),
													       'saction' => 'launch',
													   ))
?>#foo" /><?php echo tr('lancerTest') ?></a></li>
	</ul>

</div>


<a id="foo" name="foo"></a>
