<?php $tHidden=array('resume')?>

<div style="float:left;width:150px"><?php echo $this->oModuleCalendrier->show()?></div>

<table>
	<tr>
		<?php foreach($this->tColumn as $sColumn):?>
			<?php if(in_array($sColumn,$tHidden)) continue ?>
			<th><?php echo $sColumn?></th>
		<?php endforeach;?>
		<th></th>
	</tr>
	<?php if($this->tArticle):?>
	<?php foreach($this->tArticle as $oArticle):?>
	<tr>
		<?php foreach($this->tColumn as $sColumn):?>
			<?php if(in_array($sColumn,$tHidden)) continue ?>
			<td><?php echo $oArticle->$sColumn?></td>
		<?php endforeach;?>
		<td>
			
			<a href="<?php echo $this->getLink('article::edit',array(
																	'id'=>$oArticle->getId()
																) 
														)?>">Edit</a>
			<a href="<?php echo $this->getLink('article::show',array(
																	'id'=>$oArticle->getId()
																) 
														)?>">Show</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('article::new') ?>">New</a></p>