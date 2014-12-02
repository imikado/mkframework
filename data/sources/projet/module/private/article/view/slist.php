<h2>Sous module: private/article</h2>
<table>
	<tr>
		
		<th>Titre</th>

		<th>Auteur</th>

		<th>Priority</th>

		<th></th>
	</tr>
	<?php if($this->tArticle):?>
	<?php foreach($this->tArticle as $oArticle):?>
	<tr>
		
		<td><?php echo $oArticle->titre ?></td>

		<td><?php if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ echo $this->tJoinAuteur[ $oArticle->auteur_id]; }?></td>

		<td><?php echo $oArticle->priority ?></td>

		<td>
			
			<a href="<?php echo $this->getLink('article::edit',array(
													'id'=>$oArticle->getId()
												) 
										)?>">Edit</a>
			|
			<a href="<?php echo $this->getLink('article::show',array(
													'id'=>$oArticle->getId()
												) 
										)?>">Show</a>
			|
			<a href="<?php echo $this->getLink('article::delete',array(
													'id'=>$oArticle->getId()
												) 
										)?>">Delete</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="<?php echo $this->getLink('article::new') ?>">New</a></p>

