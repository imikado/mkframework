<?php echo $this->sHead ?>
<?php $nbLine = 0; ?>
<div class="market">
	<h2><?php echo $this->title ?></h2>

	<p style="text-align:right">

		<?php if (_root::getParam('hideInstalled')): ?>
			<a href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'install', 'market' => _root::getParam('market'), 'hideInstalled' => 0)) ?>#createon"><?php echo tr('afficherLesExtensionsInstalles') ?></a>
		<?php else: ?>
			<a href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => 'mods_scBootstrap_market::index', 'saction' => 'install', 'market' => _root::getParam('market'), 'hideInstalled' => 1)) ?>#createon"><?php echo tr('cacherLesExtensionsInstalles') ?></a>
		<?php endif; ?>
	</p>

	<p><?php echo $this->content ?></p>

	<table>
		<tr>
			<th style="width:670px"><?php echo tr('extensions') ?></th>
			<th style="width:80px"><?php echo tr('local') ?></th>
			<th></th>
			<th></th>
		</tr>

		<?php foreach ($this->tBloc as $oBloc): ?>
			<?php if (_root::getParam('hideInstalled') and isset($this->tLocalIni[$oBloc['id']]) and $this->tLocalIni[$oBloc['id']] == $oBloc['version']): ?>

			<?php else: ?>
				<?php $nbLine++; ?>
				<tr>
					<td><?php echo $oBloc['title'] . ' <i>' . $oBloc['id'] . '</i>' ?><br/><span class="author"><?php echo $oBloc['author'] ?></span></td>
					<td><?php echo $oBloc['version'] ?></td>
					<td>
						<?php if (isset($this->tLocalIni[$oBloc['id']]) and $this->tLocalIni[$oBloc['id']] == $oBloc['version']): ?>
							<span class="installed"><?php echo tr('installe') ?></span> <br/>(<a href="<?php echo module_mods_scBootstrap_market::getMarketLink('detail_' . $oBloc['id']) ?>#createon"><?php echo tr('voir') ?></a>)
						<?php else: ?>

							<a href="<?php echo module_mods_scBootstrap_market::getMarketLink('detail_' . $oBloc['id']) ?>#createon"><?php echo tr('install') ?></a>

						<?php endif; ?></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ($nbLine == 0): ?>
			<tr>
				<td><?php echo tr('noExtension') ?></td>
				<td></td>
				<td></td>
			</tr>
		<?php endif; ?>
	</table>



</div>