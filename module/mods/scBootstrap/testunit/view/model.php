<?php include('module/mods/all/testunit/view/index.php') ?>

<style>
p{
	margin:0px;
}
</style>

<h2 class="title"><?php echo tr('model')?></h2>
<form action="" method="POST">

<?php if($this->tMethod):?>
<?php foreach($this->tMethod as $sMethod):?>
	<p><input type="checkbox"  name="tMethod[]" value="<?php echo $sMethod?>"/> <?php echo $sMethod?></p>
<?php endforeach;?>
<?php endif;?>


<p style="margin:14px 0px"><input type="submit" value="<?php echo tr('Generer')?>"/></p>

</form>

<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>