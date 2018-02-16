<?php 
$oFunctionClose=new Plugin\Jquery('closeDiv');
$oFunctionClose->addModifyElement('rendu','hide');
echo $oFunctionClose->getJs();
?>
<div style="text-align:right;background:gray"><a href="#" onclick="closeDiv()">Fermer</div>
<?php echo $this->load('main')?>


