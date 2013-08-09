<table >
	<tr >
		<th>Titre</th>
		<th>Auteur</th>
		
		<?php if(_root::getACL()->can('edit','article') ):?>
		<th ></th>
		<?php endif;?>
	</tr>

	<?php if($this->tArticle):?>
	<?php foreach($this->tArticle as $oArticle):?>
	<tr class="<?php echo plugin_tpl::alternate(array('white','gray'),'boucle1')?>">

		<td><?php echo $oArticle->titre?></td>
		<td><?php echo $oArticle->findAuteur()->nom?></td>
		
		<?php if(_root::getACL()->can('edit','article') ):?>
		<td>
			<a href="<?php echo $this->getLink(array('prive::edit','id'=>$oArticle->getId()));?>">Edit</a>
		</td>
		<?php endif;?>
	</tr>
	<?php endforeach;?>
	<?php endif;?>	
</table>

<?php if(_root::getACL()->can('add','article') ):?>
<p ><a href="<?php echo $this->getLink('prive::new');?>">New</a></p>
<?php endif;?>	



