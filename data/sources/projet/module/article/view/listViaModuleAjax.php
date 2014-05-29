<?php
//avec tri
$oTable = new module_table('complex5ajax');
$oTable->enablePagination();
$oTable->setPaginationLimit(2);

$oTable->setAjaxLink('article::listAjax');

$oTable->setClass('tb_list');
$oTable->setCycleClass(array(null,'alt'));

$oTable->addHeaderWithOrder('Titre','titre');
$oTable->addHeaderWithOrder('Auteur','auteur_id');
$oTable->addHeaderWithOrder('Priority','priority');
$oTable->addHeader('');


if($this->tArticle){
	foreach($this->tArticle as $oArticle){
		
		$sAuteur=null; 
		if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ 
			$sAuteur= $this->tJoinAuteur[ $oArticle->auteur_id]; 
		}
		
		$oTable->addLine(array(
			$oArticle->titre,
			$sAuteur,
			$oArticle->priority,
			'<a href="'.$this->getLink('article::edit',array(
													'id'=>$oArticle->getId()
												) 
										).'">Edit</a>
			|
			<a href="'.$this->getLink('article::show',array(
													'id'=>$oArticle->getId()
												) 
										).'">Show</a>
			|
			<a href="'.$this->getLink('article::delete',array(
													'id'=>$oArticle->getId()
												) 
										).'">Delete</a>'
		
		));
	}
}
echo $oTable->build()->show();
