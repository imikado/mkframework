<table >
	<tr >
		<?php foreach($this->tColumn as $sColumn):?>
			<th ><?php echo  $sColumn ?></th>
		<?php endforeach;?>
		<th ></th>
	</tr>

	<?php if($this->tArticle):?>
	<?php foreach($this->tArticle as $oArticle):?>
	<tr class="<?php echo plugin_tpl::alternate(array('white','gray'),'boucle1')?>">
		<?php foreach($this->tColumn as $sColumn):?>
			<td >
				<?php if($sColumn=='auteur_id'):?>
					<?php echo $oArticle->findAuteur()->nom.'d'?> 
				<?php else:?>
					<?php echo $oArticle->$sColumn?>
				<?php endif;?>
			</td>
		<?php endforeach;?>

		<td >
			<a href="<?php echo $this->getLink(array('article::edit','id'=>$oArticle->getId()));?>">Edit</a>
			<?php if($sColumn=='id'):?>
			<a href="<?php echo $this->getLink(array('article::show','id'=>$oArticle->getId()));?>">Show</a>
			<?php endif;?>
		</td>

	</tr>
	<?php endforeach;?>
	<?php endif;?>	
</table>


<p ><a href="<?php echo $this->getLink('article::new');?>">New</a>
</p>

