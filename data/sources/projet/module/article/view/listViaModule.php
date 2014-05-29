<h1>Tableau simple</h1>
<?php
//simple
$oTable = new module_table('simple');
$oTable->setClass('tb_list');
$oTable->setCycleClass(array(null,'alt'));

$oTable->setHeader(array(
	'Titre',
	'Auteur',
	'Priority',
	null
));

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
?>
<h1>Tableau complexe 1: avec tri</h1>
<?php
//avec tri
$oTable = new module_table('complex1');
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


?>
<h1>Tableau complexe 2: avec tri et lien sur toute la ligne</h1>
<?php
//avec tri
$oTable = new module_table('complex2');
$oTable->setClass('tb_list2');
$oTable->setCycleClass(array(null,'alt'));
$oTable->addHeaderWithOrder('Titre','titre');
$oTable->addHeaderWithOrder('Auteur','auteur_id');
$oTable->addHeaderWithOrder('Priority','priority');

if($this->tArticle){
	foreach($this->tArticle as $oArticle){
		
		$sAuteur=null; 
		if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ 
			$sAuteur= $this->tJoinAuteur[ $oArticle->auteur_id]; 
		}
		
		$oTable->addLineWithLink(array(
			$oArticle->titre,
			$sAuteur,
			$oArticle->priority,
		
			),
			_root::getLink('article::show',array('id'=>$oArticle->id))
		);
		
	}
}
echo $oTable->build()->show();
?>
<h1>Tableau complexe 2 avec ent&ecirc;te fixe</h1>
<?php
//avec tri
$oTable = new module_table('complex2');
$oTable->setClass('tb_list3');
$oTable->setCycleClass(array(null,'alt'));
$oTable->addHeaderWithOrder('Titre','titre');
$oTable->addHeaderWithOrder('Auteur','auteur_id');
$oTable->addHeaderWithOrder('Priority','priority');

$oTable->setColumnClass(array(
	'titre',
	'auteur',
	'priority',
	'btns'
));

if($this->tArticle){
	foreach($this->tArticle as $oArticle){
		
		$sAuteur=null; 
		if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ 
			$sAuteur= $this->tJoinAuteur[ $oArticle->auteur_id]; 
		}
		
		$oTable->addLineWithLink(array(
			$oArticle->titre,
			$sAuteur,
			$oArticle->priority,
		
			),
			_root::getLink('article::show',array('id'=>$oArticle->id))
		);
		
	}
}
echo $oTable->build()->show();
?>
<h1>Tableau complexe 3 avec ent&ecirc;te fixe</h1>
<?php
//avec tri
$oTable = new module_table('complex3');
$oTable->setClass('tb_list2');
$oTable->setCycleClass(array(null,'alt'));

$oTable->addHeaderWithOrder('Titre','titre');
$oTable->addHeaderWithOrder('Auteur','auteur_id');
$oTable->addHeaderWithOrder('Priority','priority');

$oTable->setWidthTable(340);

$oTable->setColumnStyle(array(
	'width:100px;',
	'width:100px;',
	'width:100px;',
));

$oTable->setLimitLine(3,70);

if($this->tArticle){
	foreach($this->tArticle as $oArticle){
		
		$sAuteur=null; 
		if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ 
			$sAuteur= $this->tJoinAuteur[ $oArticle->auteur_id]; 
		}
		
		$oTable->addLineWithLink(array(
			$oArticle->titre,
			$sAuteur,
			$oArticle->priority,
		
			),
			_root::getLink('article::show',array('id'=>$oArticle->id))
		);
		
	}
}
echo $oTable->build()->show();
?>
<h1>Tableau complexe 4: avec tri et pagination</h1>
<?php
//avec tri
$oTable = new module_table('complex4');
$oTable->enablePagination();
$oTable->setPaginationLimit(2);

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

?>
<h1>Tableau complexe 5: avec tri et pagination + mise a jour ajax</h1>
<?php
//avec tri
$oTable = new module_table('complex5');
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
