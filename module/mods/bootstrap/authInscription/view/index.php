<a id="formu" name="formu"></a>

	
<?php if($this->tRowMethodes):?>
	<form action="#formu" method="POST">
		<input type="hidden" name="formu" value="generate"/>
		
		<input type="hidden" name="loginField" value="<?php echo _root::getParam('loginField')?>"/>
		<input type="hidden" name="passField" value="<?php echo _root::getParam('passField')?>"/>

	<table>
		<tr>
			<th><?php echo tr('choisissezLaClasseAutiliser')?></th>
			<td>
				<select name="classmodel" onchange="submit()">
					
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
			<th><?php echo tr('nomDuModuleDauthentification')?></th>
			<td><input type="text" name="modulename" value="<?php echo _root::getParam('modulename','auth')?>"/>
				<?php if(isset($this->tMessage['modulename'])):?>
				<p class="error"><?php echo implode(',',$this->tMessage['modulename'])?></p>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<th><?php echo tr('moduleVersLequelRedirigerApresAuth')?></th>
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
		
		<?php if($this->tColumnAccount):?>
		<tr>
			<th><?php echo tr('champUtilisateur')?></th>
			<td>
				<select name="loginField" >
					<option></option>
					<?php foreach($this->tColumnAccount as $sColumn):?>
					<option <?php if(_root::getParam('loginField')==$sColumn):?>selected="selected"<?php endif;?> value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
					<?php endforeach;?>
				
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo tr('champMdp')?></th>
			<td>
				<select name="passField">
					<option></option>
					<?php foreach($this->tColumnAccount as $sColumn):?>
					<option <?php if(_root::getParam('passField')==$sColumn):?>selected="selected"<?php endif;?> value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
					<?php endforeach;?>
				
				</select>
			</td>
		</tr>
		<?php endif;?>
		
	</table>
	
	<a id="createon" name="createon"></a>
	<p style="text-align:right"><input type="submit" value="<?php echo tr('generer')?>"/></p>
	
	</form>
			
<?php else:?>

	<form action="#formu" method="POST">
		
	<table>
			
		<tr>
			<th><?php echo tr('choisissezLaClasseAutiliser')?></th>
			<td>
			
				<select name="model" onchange="submit()" >
					<option></option>
			<?php foreach($this->tFile as $sFile):?>
				<option value="<?php echo $sFile?>" <?php if(_root::getParam('model')==$sFile):?>selected="selected"<?php endif;?>><?php echo $sFile?></option>
			<?php endforeach;?>
			</select>
			
			
			</td>
		</tr>
		
		<?php if($this->tColumnAccount):?>
		<tr>
			<th><?php echo tr('champUtilisateur')?></th>
			<td>
				<select name="loginField" onchange="submit()">
					<option></option>
					<?php foreach($this->tColumnAccount as $sColumn):?>
					<option <?php if(_root::getParam('loginField')==$sColumn):?>selected="selected"<?php endif;?> value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
					<?php endforeach;?>
				
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo tr('champMdp')?></th>
			<td>
				<select name="passField" onchange="submit()">
					<option></option>
					<?php foreach($this->tColumnAccount as $sColumn):?>
					<option <?php if(_root::getParam('passField')==$sColumn):?>selected="selected"<?php endif;?> value="<?php echo $sColumn?>"><?php echo $sColumn?></option>
					<?php endforeach;?>
				
				</select>
			</td>
		</tr>
		<?php endif;?>
	</table>
	
	
	
	<?php if(!$this->tRowMethodes and (_root::getParam('model') and _root::getParam('loginField') and _root::getParam('passField')) ):?>
		<table>
			<tr>
				<td colspan="2" style="padding:10px">
				<h2 style="border:2px solid red;padding:4px"><?php echo sprintf(tr('ilVousFautModifierLaClasse'),_root::getParam('model'))?></h2>
					
				<strong><?php echo tr('ilVousFautAjouterCesMethodes')?> "<a target="_blank" href="<?php echo _root::getLink('code::index',array(
				'project'=>_root::getParam('id'),
				'file'=>'model/'._root::getParam('model')
				))?>"><?php echo substr(_root::getParam('model'),0,-4)?>"</a> <?php echo tr('deVosComptesDeConnexion')?></strong><br/>
				<?php echo tr('uneMethode')?> "getListAccount()"<br />
				<i><?php echo tr('quiRetourneraUntableauIndexe')?>s</i><br/>
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #0000BB">$tLoginPassAccount</span><span style="color: #007700">[</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB"><?php echo _root::getParam('loginField','login')?></span><span style="color: #007700">][</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB"><?php echo _root::getParam('passField','pass')?></span><span style="color: #007700">]=</span><span style="color: #0000BB">$oAccount</span><span style="color: #007700">;<br /></span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">return&nbsp;</span><span style="color: #0000BB">$tLoginPassAccount</span><span style="color: #007700">;</span><br />
					<br />
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
					
				</div>
				<?php echo tr('etUneMethode')?> "hashPassword()"<br/>
				<i><?php echo tr('quiRetourneraLeHashageDuMdp')?></i></p>
				<i><?php echo tr('pensezAmodifierLeSel')?> <span style="color: #770000">'2votreSelAchanger2'</span> <?php echo tr('pourRendreEmpreintSecurise')?>.</i><br/>
				<div style="margin-top:4px;border:1px dotted gray;background:#eee;padding:4px">
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">public&nbsp;function&nbsp;</span><span style="color: #0000BB">hashPassword</span><span style="color: #007700">(</span><span style="color: #0000BB">$sPassword</span><span style="color: #007700">){</span><br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FF8000">//utiliser ici la methode de votre choix pour hasher votre mot de passe</span><br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #007700">return&nbsp;</span><span style="color: #0000BB">sha1</span><span style="color: #007700">(</span><span style="color: #770000">'2votreSelAchanger2'</span>.<span style="color: #0000BB">$sPassword</span><span style="color: #007700">);</span><br />
					&nbsp;&nbsp;&nbsp;<span style="color: #007700">}</span><br />
				</div>
			
				<p style="padding-left:0px"><?php echo tr('ajoutezCesMethodesDansLaClasse')?> <input type="submit" value="<?php echo tr('reactualisezLaPage')?>"/></p>
				
				</td>
			</tr>
			</table>
		<?php endif;?>
		</form>
<?php endif;?>


<?php if($this->msg):?>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
<?php endif;?>
<br /><br /><br />
