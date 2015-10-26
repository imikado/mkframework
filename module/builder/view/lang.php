<style>
pre{
	background:#eee;
	margin-left:10px;
	border:1px dotted #333;
	width:250px;
	padding:6px;
}
</style>
<?php if($this->messageNOK):?>
	<p class="error"><?php echo $this->messageNOK?></p>
<?php endif;?>

<?php if($this->message):?>
	<?php echo $this->message?>
<?php endif;?>