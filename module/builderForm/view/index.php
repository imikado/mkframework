<script>
	function addInput(sId, sName) {

		var a = getById(sId);
		if (a) {
			a.innerHTML += '<input type="text" name="' + sName + '[]"/><br/>';
		}

	}
</script>
<a id="buildForm" name="buildForm"></a>
<a id="buildForm1" name="buildForm1"></a>
<style>
	.bloc{

	}
	.row{
		border:1px solid gray;
		margin:10px 0px;
		background:#fff;
	}
	.label{
		width:300px;
		float:left;
		padding:8px;
		text-align:right;
		font-weight: bold;
		color:#222;
		font-size:12px;
	}
	.input{
		width:340px;
		float:left;
		background:#fff;
		padding:8px;
	}
	.textarea{
		width:340px;
		height:100px;

	}
	.clear{clear:both;}


	.tab{
		padding-left:20px;

	}
	.tab2{
		padding-left:40px;

	}
	.tab3{
		padding-left:60px;
	}

	.code{
		margin-top:4px;
		border:1px dotted gray;
		background:#eee;
		padding:4px;
	}

	.path{
		display:block;
		float:left;
		border:2px solid #ccc;
		padding:8px 14px;
		text-decoration: none;
		margin-right:4px;
		background:#ddeedd;
		font-size:12px;
		color:#333;
	}
	.selected{
		background:#ffffff;
	}
	.navline{
		background:#ddd;
	}
	a.path {
		color:darkblue;
	}
	a.path:hover{
		text-decoration: underline;
	}
</style>
<script>
	function setStep(iStep) {
		var oInputStep = getById('inputStep');
		var oInputNextStep = getById('inputNextStep');
		var oForm = getById('oFormBack');
		if (oInputStep && oInputNextStep) {
			oInputNextStep.value = iStep;
			oInputStep.value = iStep;

			oForm.submit();


		}


	}
</script>

<form action="#buildForm" method="POST" id="oFormBack">
	<?php echo $this->sHiddenFormBack ?>
</form>

<form style="padding:10px" action="#buildForm" method="POST">
	<div class="navline">
		<?php //foreach ($this->tNavPath as $i => $sNav): ?>
		<?php for ($i = 1; $i <= $this->iMaxStep; $i++): ?>
			<?php if ($i < $this->iStep): ?>
				<a class="path <?php if ($this->iStep == $i): ?>selected<?php endif; ?>"   href="#" onclick="setStep(<?php echo $i ?>);
								return false;" > <?php echo $i ?> </a>
			   <?php else: ?>
				<span class="path <?php if ($this->iStep == $i): ?>selected<?php endif; ?>"  ><?php echo $i ?></span>

			<?php endif; ?>

		<?php endfor; ?>
		<?php //endforeach; ?>
		<div style="clear:both"></div>
	</div>
	<br/>

	<?php echo $this->sHiddenForm ?>

	<div class="bloc">
		<?php echo $this->sForm ?>
	</div>

	<p>
		<?php if ($this->iStep < $this->iMaxStep): ?>


			<a href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => _root::getParam('action'))) ?>"><?php echo tr('annuler') ?></a>

			<input type="submit" value="<?php echo tr('next') ?>"/>
		<?php else: ?>
			<a href="<?php echo _root::getLink('builder::edit', array('id' => _root::getParam('id'), 'action' => _root::getParam('action'))) ?>"><?php echo tr('annuler') ?></a>

			<input type="submit" value="<?php echo tr('finish') ?>"/>

			<input type="hidden" name="finish" value="1"/>
		<?php endif; ?>
	</p>
</form>