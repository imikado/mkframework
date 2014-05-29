<h1>Cr&eacute;er un module d'authentification Bootstrap</h1>
<form action="" method="POST">

	<table>
		
		<?php if($this->tRowMethodes):?>
			<tr>
			<th>Choisissez la classe modele &agrave; utiliser</th>
			<td>
				<select name="classmodel">
					
					<?php if($this->tRowMethodes):?>
						<?php foreach($this->tRowMethodes as $sKey => $sValue):?>
						<option <?php if(_root::getParam('classmodel')==$sKey):?>selected="selected"<?php endif;?> value="<?php echo $sKey?>"><?php echo $sValue?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php if(isset($this->tMessage['classmodel'])):?>
					<p class="error"><?php echo implode(',',$this->tMessage['classmodel'])?></p>
				<?php endif;?>
			</td>
		</tr>
			<tr>
				<th>Nom du module d'authentification</th>
				<td><input type="text" name="modulename" value="<?php echo _root::getParam('modulename','auth')?>"/>
					<?php if(isset($this->tMessage['modulename'])):?>
					<p class="error"><?php echo implode(',',$this->tMessage['modulename'])?></p>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<th>Module vers lequel rediriger apr&egrave;s authentification</th>
				<td>
					<select name="redirect">
						<?php foreach($this->tModuleAndMethod as $sModule => $tMethod):?>
							<?php foreach($tMethod as $sMethod):?>
							<option <?php if(_root::getParam('redirect')==$sModule.'::'.$sMethod):?>selected="selected"<?php endif;?> value="<?php echo $sModule.'::'.substr($sMethod,1)?>">module "<?php echo $sModule?>" action "<?php echo $sMethod?>()"</option>
							<?php endforeach;?>
						<?php endforeach;?>
					</select>
					<?php if(isset($this->tMessage['redirect'])):?>
						<p class="error"><?php echo implode(',',$this->tMessage['redirect'])?></p>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<th>Champ de login</th>
				<td>
					<select name="loginField">
						<?php foreach($this->tColumnAccount as $sField):?>
						<option value="<?php echo $sField?>"><?php echo $sField ?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Champ de mot de passe</th>
				<td>
					<select name="passField">
						<?php foreach($this->tColumnAccount as $sField):?>
						<option value="<?php echo $sField?>"><?php echo $sField ?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			
		<?php else:?>
			
			<tr>
				<td colspan="2" style="padding:10px">
				<h2 style="border:2px solid red;padding:4px">Erreur: aucune classe mod&egrave;le utilisable :(</h2>
					
				<strong>Il vous faut ajoutez ces deux m&eacute;thodes à votre classe mod&egrave;le de vos comptes de connexion</strong><br/>
				Une m&eacute;thode "getListAccount()"<br />
				<i>Qui retournera un tableau index&eacute; de vos comptes de connexion</i><br/>
				<div style="margin-top:4px;border:1px dotted gray;background:#eee;padding:4px">
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">public&nbsp;function&nbsp;</span><span style="color: #0000BB">getListAccount</span><span style="color: #007700">(){</span><br />
					&nbsp;&nbsp;<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">$tAccount</span><span style="color: #007700">=</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">findAll</span><span style="color: #007700">();</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">$tLoginPassAccount</span><span style="color: #007700">=array();</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">if(</span><span style="color: #0000BB">$tAccount</span><span style="color: #007700">){</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">foreach(</span><span style="color: #0000BB">$tAccount&nbsp;</span><span style="color: #007700">as&nbsp;</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">){</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FF8000">//on cree ici un tableau indexe par nom d'utilisateur et mot de pase</span><br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">$tLoginPassAccount</span><span style="color: #007700">[</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">login</span><span style="color: #007700">][</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">pass</span><span style="color: #007700">]=</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">;<br /></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">return&nbsp;</span><span style="color: #0000BB">$tLoginPassAccount</span><span style="color: #007700">;</span><br />
					<br />
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					
				</div>
				<p style="padding-left:0px;font-weight:bold">PS: N'oubliez pas de remplacer "login" et "pass" par vos champs de nom d'utilisateur et mot de passe en base de donn&eacute;es</p>
				Et une m&eacute;thode "hashPassword()"<br />
				<i>Qui retournera le hashage(empreinte) du mot de passe (ne pas stoquer les mots de passe en clair)</i><br/>
				<div style="margin-top:4px;border:1px dotted gray;background:#eee;padding:4px">
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">public&nbsp;function&nbsp;</span><span style="color: #0000BB">hashPassword</span><span style="color: #007700">(</span><span style="color: #0000BB">$sPassword</span><span style="color: #007700">){</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FF8000">//utiliser ici la methode de votre choix pour hasher votre mot de passe</span><br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">return&nbsp;</span><span style="color: #0000BB">sha1</span><span style="color: #007700">(</span><span style="color: #0000BB">$sPassword</span><span style="color: #007700">);</span><br />
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
				</div>
			
				<p style="padding-left:0px">Ajoutez ces deux m&eacute;thodes dans la classe mod&egrave;le concern&eacute;e puis r&eacute;actualiser la page</p>
				
				</td>
			</tr>
		<?php endif;?>
	
	</table>

<?php if($this->tRowMethodes):?>
<a id="createon" name="createon"></a>
<p style="text-align:right"><input type="submit" value="G&eacute;n&eacute;rer le module d'authentification"/></p>
<?php endif;?>

</form>
<?php if(isset($this->tMessage['success'])):?>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
<?php endif;?>
<br /><br /><br />
