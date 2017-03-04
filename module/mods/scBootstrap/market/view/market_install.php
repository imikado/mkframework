<?php if($this->error):?>
	<p class="error"><?php echo $this->error?></p>
<?php else:?>
	<p class="success"><?php echo tr('extensionInstalle')?></p>
<?php endif;?>