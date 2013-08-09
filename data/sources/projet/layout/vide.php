<?php 
$oFunctionClose=new plugin_jquery('closeDiv');
$oFunctionClose->addModifyElement('rendu','hide');
echo $oFunctionClose->getJs();
?>
<div style="text-align:right;background:gray"><a href="#" onclick="closeDiv()">Fermer</div>
<?php echo $this->load('main')?>


