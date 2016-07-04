<?php $oForm=new plugin_form($this->oForm);
$oForm->setMessage($this->tMessage);
?>
<style>
.center{
	text-align:center;
}
.tb_list{
	margin:auto;
}
.tb_list td{
	vertical-align:top;
	text-align:left;
}
.tb_list .field p{
	text-align:left;
	
}
.main .content table th{
	background:white;
}
.tb_list td{
	border:1px solid gray;
}
.tb_list .empty{
	border:0px;
}
.nb{
	background:black;
	color:white;
	padding:2px 4px;
	font-size:12px;
	margin-right:5px;
}
h2{
	padding-left:4px;
	font-size:12px;
}
</style>
<script>
function generate(){
	var a=getById('actioncode');
	if(a){
		a.value="generate";
		var b=getById('formu');
		if(b){
			b.submit();
		}
	}
}
</script>
<h2><?php echo tr('presentation')?></h2>
<p><?php echo tr('pourGererLesDroitsNousAllons')?>.</p>
<p class="center"><img src="site/css/images/rightsManagerMultiMcd0.png"/></p>

<div>
	<h3>Code SQL</h3><textarea style="width:95%;height:100px">CREATE TABLE `Users` (
`id` int(11) NOT NULL auto_increment,
`login` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `Groups` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `GroupsUsers` (
`id` int(11) NOT NULL auto_increment,
`users_id` int(11) NOT NULL,
`groups_id` int(11) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `Permissions` (
`id` int(11) NOT NULL auto_increment,
`items_id` int(11) NOT NULL,
`actions_id` int(11) NOT NULL,
`groups_id` int(11) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `Items` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `Actions` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
PRIMARY KEY  (`id`)
);</textarea></div>
<p>&nbsp;</p>

<h2><?php echo tr('coucheModel')?></h2>
<p><?php echo trR('genererLaCoucheModel',array('#LINK#'=>_root::getLink('builder::edit',array('id'=>_root::getParam('id'),'action'=>'mods_all_model::index') ).'#createon'));?></p>
<p>&nbsp;</p>

<a id="formulaire" name="formulaire"></a>
<h2><?php echo tr('miseEnPlace')?></h2>
<p><?php echo tr('vousNetesPasObligerDutiliserLesmemes')?></p>
<p class="center"><img src="site/css/images/rightsManagerMultiMcd.png"/></p>


<?php if($this->tMessage and isset($this->tMessage['error'])):?>
<p class="error"><?php echo $this->tMessage['error']?></p>
<?php endif;?>

<?php $tOption=array('onchange'=>'submit()');?>
<form id="formu" action="#formulaire" method="POST">
<input type="hidden" name="actioncode" id="actioncode"/>
<table class="tb_list">
	<tr>
		<th><?php echo tr('utilisateur')?></th>
		
		<th><?php echo tr('utilisateurGroupe')?></th>
		
		<th><?php echo tr('permissions')?></th>
		
		<th><?php echo tr('actions')?></th>
		
		
	</tr>
	<tr>
		<td><?php echo $oForm->getSelect('classModelUser',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelGroupUser',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelPermission',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelAction',$this->tModel,$tOption)?></td>
		
	</tr>
	<tr>
		<?php
		$tFields=array(
			'classModelUser' => array('columnUser',array('A'=>'id','B'=>'login')),
			'classModelGroupUser'=>array('columnGroupUser',array('C'=>'users_id','D'=>'groups_id')),

			'classModelPermission' => array('columnPermission',array('G'=>'id','H'=>'items_id','I'=>'actions_id','J'=>'groups_id')),
			'classModelAction' => array('columnAction',array('K'=>'id','L'	=>'name')),
		);
		?>
		
		<?php foreach($tFields as $class => $tDetail):?>
			<?php if(_root::getParam($class) ):
				$tColumn=$this->tColumn[_root::getParam($class)]; ?>
				<td class="field">
					<?php foreach($tDetail[1] as $nb => $sField):?>
						<p><span class="nb"><?php echo $nb;?></span> <?php echo $sField ?>: <br/><?php echo $oForm->getSelect($class.'_'.$sField,$tColumn)?></p>
					<?php endforeach;?>
				</td>
			<?php else:?>
				<td class="empty"></td>
			<?php endif;?>
		<?php endforeach;?>
		
		 
	</tr>
	
	<tr>
		<th class="empty"></th>
		<th><?php echo tr('groupes')?></th>
		<th class="empty"></th>
		<th><?php echo tr('elements')?></th>
		
	</tr>
	
	
	<tr>
		<td class="empty"></td>
		<td><?php echo $oForm->getSelect('classModelGroup',$this->tModel,$tOption)?></td>
		<td class="empty"></td>
		<td><?php echo $oForm->getSelect('classModelItem',$this->tModel,$tOption)?></td>
		
		
	</tr>
	
	<tr>
		<td  class="empty"></td>
		<?php
			$tFields=array(
					'classModelGroup' => array('columnGroup',array('E'=>'id','F'=>'name')),

				
			);
			?>
			
			<?php foreach($tFields as $class => $tDetail):?>
				<?php if(_root::getParam($class) ):
					$tColumn=$this->tColumn[_root::getParam($class)]; ?>
					<td class="field">
						<?php foreach($tDetail[1] as $nb => $sField):?>
							<p><span class="nb"><?php echo $nb;?></span> <?php echo $sField ?>: <br/><?php echo $oForm->getSelect($class.'_'.$sField,$tColumn)?></p>
						<?php endforeach;?>
					</td>
				<?php else:?>
					<td class="empty"></td>
				<?php endif;?>
			<?php endforeach;?>
		 


		<td  class="empty"></td>
		 
			<?php
			$tFields=array(
				
				'classModelItem' => array('columnItem',array('M'=>'id','N'=>'name')),
			);
			?>
			
			<?php foreach($tFields as $class => $tDetail):?>
				<?php if(_root::getParam($class) ):
					$tColumn=$this->tColumn[_root::getParam($class)]; ?>
					<td class="field">
						<?php foreach($tDetail[1] as $nb => $sField):?>
							<p><span class="nb"><?php echo $nb;?></span> <?php echo $sField ?>: <br/><?php echo $oForm->getSelect($class.'_'.$sField,$tColumn)?></p>
						<?php endforeach;?>
					</td>
				<?php else:?>
					<td class="empty"></td>
				<?php endif;?>
			<?php endforeach;?>
		 
	</tr>
	
</table>
<p>
	<?php echo tr('nomDuModuleAgenerer')?> : module_<?php echo $oForm->getInputText('moduleToGenerate')?>

	<?php echo tr('nomDeLaClasseAgenerer')?> : model_<?php echo $oForm->getInputText('modelToGenerate')?>
</p>
<p style="background:#ddd;text-align:right"><input onclick="generate()" type="button" value="<?php echo tr('generer')?>"/></p>
</form>
<?php if($this->tMessage and isset($this->tMessage['msg'])):?>
<p class="msg"><?php echo $this->tMessage['msg']?></p>
<?php endif;?>
<?php if($this->tMessage and isset($this->tMessage['detail'])):?>
<p class="detail"><?php echo $this->tMessage['detail']?></p>
<?php endif;?>

<?php if($this->tMessage and isset($this->tMessage['code'])):?>
<h1><?php echo tr('ajouterLeChargementDesDroits')?></h1>
<p><?php echo sprintf(tr('editezVotreFichier'),$this->tMessage['auth'])?></p>
<?php echo $this->tMessage['code'];?>
<?php endif;?>
