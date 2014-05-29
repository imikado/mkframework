<?php
$sPath=_root::getParam('path','..');
$oDir=new _dir($sPath);
$tDir=$oDir->getList();
$sParent='..';
if(preg_match('/\//', $sPath)){
	$tParent=explode('/',$sPath);
	array_pop($tParent);
	$sParent=implode('/',$tParent);	
}
$tReturn=$this->tReturn;
?>
<?php if(isset($tReturn['error'])):?>
<p class="error" style="padding:8px;border:1px dotted red;margin:8px;"><strong>ERROR:</strong> <?php echo $tReturn['error']?></p>
<?php endif;?>
Vous allez exporter votre projet vers le r&eacute;pertoire ci-dessous:
<h2><?php echo $sPath?></h2>
<div class="smenu">
<ul>
<h3>S&eacute;lectionnez le r&eacute;pertoire o&ugrave; copier le projet:</h3>

<li><a href="<?php echo _root::getLink('builder::export',array(
												'id'=>_root::getParam('id'),
												'path'=>$sParent
												));?>#createon">..</a></li> 
<?php foreach ($tDir as $oDir): ?>
	<li><a href="<?php echo _root::getLink('builder::export',array(
												'id'=>_root::getParam('id'),
												'path'=> $sPath.'/'.$oDir->getName(),
												)
									);?>#createon"><?php echo $oDir->getName();?></a></li> 
<?php endforeach ?>
</ul>
</div>
<form action="" method="POST">
<input type="hidden" name="from" value="<?php echo _root::getParam('id')?>" />
<input type="hidden" name="to" value="<?php echo _root::getParam('path')?>" />

<div class="table">
<h3>Gestion du framework</h3>
<p>
	<input <?php if(_root::getParam('lib')=='link'):?>checked="checked"<?php endif;?> type="radio" name="lib" value="link"/> Ne pas copier le framework et modifier le lien vers celui-ci<br/>
	<input <?php if(_root::getParam('lib')=='copy'):?>checked="checked"<?php endif;?> type="radio" name="lib" value="copy"/> Copier le framework dans le projet
</p>
 
<input type="submit" value="Exporter ici" />
</div>
</form>

<?php if(isset($tReturn['ok'])):?>
<p class="msg" style="color:darkgreen"><?php echo $tReturn['ok']?></p>
<?php endif;?>
<?php if(isset($tReturn['detail'])):?>
<p class="detail"><?php echo $tReturn['detail']?></p>
<?php endif;?>