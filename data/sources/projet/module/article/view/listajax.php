<?php
$oFunctionShow=new plugin_jquery('showArticle',array('param_id'));
$oFunctionShow->addLinkUpdateElement( $this->getLink('article::showajax',array('id'=> '$param_id')) ,'rendu') ;
$oFunctionShow->addModifyElement('rendu','show');
echo $oFunctionShow->getJs();

$oFunctionEdit=new plugin_jquery('editArticle',array('param_id'));
$oFunctionEdit->addLinkUpdateElement( $this->getLink('article::editajax',array('id'=> '$param_id')) ,'rendu');
$oFunctionEdit->addModifyElement('rendu','show');
echo $oFunctionEdit->getJs();

$oFunctionEdit=new plugin_jquery('newArticle',array('param_id'));
$oFunctionEdit->addLinkUpdateElement( $this->getLink('article::newajax',array('id'=> '$param_id')) ,'rendu');
$oFunctionEdit->addModifyElement('rendu','show');
echo $oFunctionEdit->getJs();


$oFunctionDate=new plugin_jquery('echodate');
$oFunctionDate->addLinkCallFunction( $this->getLink('article::dateajax') , 'print_date');
echo $oFunctionDate->getJs();

?>
<script>
function print_date(response){
	alert(response);
}
</script>

<table>
	<tr>
		<?php foreach($this->tColumn as $sColumn):?>
			<th><?php echo $sColumn?></th>
		<?php endforeach;?>
		<th></th>
	</tr>
	<?php if($this->tArticle):?>
	<?php foreach($this->tArticle as $oArticle):?>
	<tr>
		<?php foreach($this->tColumn as $sColumn):?>
			<?php if($sColumn=='auteur_id'):?>
			<td><?php echo $oArticle->findAuteur()->nom?></td>
			<?php else:?>
			<td><?php echo $oArticle->$sColumn?></td>
			<?php endif;?>
		<?php endforeach;?>
		<td>
			
			<a href="#" onclick="editArticle(<?php echo $oArticle->getId()?>)">Edit</a>
			<a href="#" onclick="showArticle(<?php echo $oArticle->getId()?>)">Show</a>
		</td>
	</tr>	
	<?php endforeach;?>
	<?php endif;?>
</table>
<p><a href="#" onclick="newArticle()">New</a></p>

<p><a href="#" onclick="echodate()">echo date</a></p>
 

<div style="position:absolute;border:1px solid black;top:30px;background:white" id="rendu"></div>




 
