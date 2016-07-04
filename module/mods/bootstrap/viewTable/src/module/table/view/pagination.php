<?php $sOrder=module_table::getParam('order'); $sSide=module_table::getParam('side');?>
<div class="pagination">
<ul>
<?php for($i=1;$i<=$this->iMax;$i++):?>
	<li <?php if($i==($this->iPage+1)):?>class="selectionne"<?php endif;?>><a href="<?php echo module_table::getLink(null,array($this->sParamPage=>$i,'order'=>$sOrder,'side'=>$sSide)) ?>">page <?php echo $i?></a></li> 
<?php endfor;?>
</ul>
</div>

