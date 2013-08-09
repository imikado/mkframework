<div class="pagination">
<ul>
<?php for($i=1;$i<=$this->iMax;$i++):?>
	<?php $tParam=$this->tParam ?>
	<?php $tParam[$this->sParamPage]=$i?>
	<li <?php if($i==($this->iPage+1)):?>class="selectionne"<?php endif;?>><a href="<?php echo _root::getLink($this->sModuleAction,$tParam) ?>">page <?php echo $i?></a></li> 
<?php endfor;?>
</ul>
</div>