<?php
$tPostMethod=_root::getParam('tMethod');
$tPostLabel=_root::getParam('tLabel');
?><style>
table{
	border-collapse:collapse;
}
table td{
	padding:0px;
}
.methods td{
	border-bottom:1px dotted #ccc;
}
</style>
<h1>Ajouter un module menu</h1>
<?php if(!_root::getRequest()->isPost()):?>
	<form action="#createon" method="POST">
	<p>Pour c&eacute;er le menu: cochez les actions/pages des modules &agrave; cr&eacute;er et indiquez en face le libell&eacute; du lien</p>
	<p><strong>Nom du module</strong><input type="text" name="modulename" value="menu" /></p>
	<?php if($this->bExist==1):?>
	<p style="color:red">Le r&eacute;pertoire module/menu existe d&eacute;j&agrave;</p>
	<?php endif;?>

	<table style="margin-left:10px">
		<?php $i=0?>
		<?php foreach($this->tModuleAndMethod as $sModule => $tMethod):?>
		<tr>
			<th style="vertical-align:top">Module <u><?php echo $sModule?></u><p style="margin:2px;font-weight:normal;font-style:italic">(classe module_<?php echo $sModule?>)</p></th>
			<td>
				<table class="methods">
					<tr>
						<th></th>
						<th>M&eacute;thode appel&eacute;e</th>
						<th>Libell&eacute; du lien</th>
					</tr>
					<?php foreach($tMethod as $sMethod):?>
					<tr>
						<td><input type="checkbox" name="tMethod[<?php echo $i?>]" value="<?php echo $sModule?>::<?php echo substr($sMethod,1)?>" <?php if(isset($tPostMethod[$i])){ echo 'checked="checked"'; }?> /></td>
						<td style="width:200px"><?php echo $sMethod?>()</td>
						<td><input type="text" name="tLabel[<?php echo $i?>]" value="<?php if(isset($tPostLabel[$i])){ echo $tPostLabel[$i];}?>" />
							<?php if(isset($this->tError[$i])):?>
							<span style="color:red"><?php echo $this->tError[$i]?></span>
							<?php endif;?>
						</td>
					</tr>
					<?php $i++?>
					<?php endforeach;?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<?php endforeach;?>
	</table>
	<a id="createon" name="createon"></a>
	<p style="text-align:right"><input type="submit" value="G&eacute;n&eacute;rer le menu"/></p>
<?php endif;?>
<p><?php echo $this->detail?></p>
</form>
