<?php $tHidden=array('resume')?>
<table>
	<tr>
		<?php foreach($this->tColumn as $sColumn):?>
			<?php if(in_array($sColumn,$tHidden)) continue ?>
			<th><?php echo $sColumn?></th>
		<?php endforeach;?>
		<th></th>
	</tr>
	<?php if($this->tAuteur):?>
	<?php foreach($this->tAuteur as $oAuteur):?>
	<tr>
		
		<td><?php echo $oAuteur->nom ?></td>

		<td><?php echo $oAuteur->prenom ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('auteurxml::showXml',array(
																	'id'=>$oAuteur->getId()
																) 
														)?>">XML</a>
			
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('auteurxml::listXml') ?>">XML List</a></p>
