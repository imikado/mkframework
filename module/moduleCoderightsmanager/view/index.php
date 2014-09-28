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
<h1>Ajouter une gestion de droits</h1>
<h2>Pr&eacute;sentation</h2>
<p>Pour g&eacute;rer les droits de votre application, nous allons cr&eacute;er d'abord notre base de donn&eacute;es.</p>
<p class="center"><img src="site/css/images/rightsManagerMcd0.png"/></p>

<div>
	<h3>Code SQL</h3><textarea style="width:95%;height:100px">CREATE TABLE `Users` (
`id` int(11) NOT NULL auto_increment,
`login` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
`groups_id` int(11) NOT NULL,
PRIMARY KEY  (`id`)
);

CREATE TABLE `Groups` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
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
<a id="formulaire" name="formulaire"></a>
<h2>Mise en place</h2>
<p>Vous n'&ecirc;tes pas oblig&eacute; d'utiliser les m&ecirc;me noms de champs et de tables, vous allez ci-dessous indiquer pour chaque table le nom de votre classe mod&egrave;le puis s&eacute;lectionnez la correspondance des champs</p>
<p class="center"><img src="site/css/images/rightsManagerMcd.png"/></p>


<?php if($this->tMessage and isset($this->tMessage['error'])):?>
<p class="error"><?php echo $this->tMessage['error']?></p>
<?php endif;?>

<?php $tOption=array('onchange'=>'submit()');?>
<form id="formu" action="#formulaire" method="POST">
<input type="hidden" name="actioncode" id="actioncode"/>
<table class="tb_list">
	<tr>
		<th>Utilisateur</th>
		
		<th>Groupes</th>
		
		<th>Permissions</th>
		
		<th>Elements</th>
		
		
	</tr>
	<tr>
		<td><?php echo $oForm->getSelect('classModelUser',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelGroup',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelPermission',$this->tModel,$tOption)?></td>
		<td><?php echo $oForm->getSelect('classModelItem',$this->tModel,$tOption)?></td>
		
	</tr>
	<tr>
		<?php
		$tFields=array(
			'classModelUser' => array('columnUser',array(1=>'id',2=>'login',3=>'groups_id')),
			'classModelGroup' => array('columnGroup',array(4=>'id',5=>'name')),
			'classModelPermission' => array('columnPermission',array(6=>'id',7=>'items_id',8=>'actions_id',9=>'groups_id')),
			
			'classModelItem' => array('columnItem',array(10=>'id',11=>'name')),
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
		<th colspan="3" class="empty"></th>
		 
		<th>Actions </th>
	</tr>
	
	<tr>
		<td colspan="3" class="empty"></td>
		<td><?php echo $oForm->getSelect('classModelAction',$this->tModel,$tOption)?></td>
		
	</tr>
	
	<tr>
		<td colspan="3" class="empty"></td>
		 
			<?php
			$tFields=array(
				'classModelAction' => array('columnAction',array(12=>'id',13	=>'name')),
				
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
	Nom du module &agrave; g&eacute;n&eacute;rer : module_<?php echo $oForm->getInputText('moduleToGenerate')?>

	nom de la classe model &agrave; g&eacute;n&eacute;rer : model_<?php echo $oForm->getInputText('modelToGenerate')?>
</p>
<p style="background:#ddd;text-align:right"><input onclick="generate()" type="button" value="G&eacute;nerer"/></p>
</form>
<?php if($this->tMessage and isset($this->tMessage['msg'])):?>
<p class="msg"><?php echo $this->tMessage['msg']?></p>
<?php endif;?>
<?php if($this->tMessage and isset($this->tMessage['detail'])):?>
<p class="detail"><?php echo $this->tMessage['detail']?></p>
<?php endif;?>

<?php if($this->tMessage and isset($this->tMessage['code'])):?>
<h1>Ajouter le chargement des droits sur votre module d'authentification</h1>
<p>Editez votre fichier module/<?php echo $this->tMessage['auth']?>/main.php et editer la m&eacute;thode d'authentification</p>
<?php echo $this->tMessage['code'];?>
<?php endif;?>
