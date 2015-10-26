<p style="margin:0px;margin-bottom:10px"><a style="display:block;border:1px dotted gray;text-decoration:none;background:#ddd;text-align:center" href="<?php echo _root::getLink('builder::edit',array('id'=>_root::getParam('project')))?>"><?php echo tr('retour')?></a></p>
<?php foreach($this->tFileDir as $sDir => $tContent): ?>
	<p class="dir" id="linkarbo<?php echo $sDir?>"><a href="#" onclick="openclose('arbo<?php echo $sDir?>');return false;"><?php echo $sDir?></a>
		<?php if($sDir=='module'):?>
			<a style="margin-left:10px;color:darkgreen" href="#" onclick="openCrossPopup('addModule');return false">[ <?php echo tr('explorerAjouterUnModule')?> ]</a>
		<?php elseif($sDir=='model'):?>
			<a style="margin-left:10px;color:darkgreen" href="#" onclick="openCrossPopup('genModel');return false">[ <?php echo tr('explorerGenererLaCoucheModele')?> ]</a>
		<?php endif;?>
	</p>
	<div id="arbo<?php echo $sDir?>" style="display:none">
		<?php ksort($tContent['dir']);?>
		<?php foreach($tContent['dir'] as $sDir2 => $tContent2):?>
			<?php $sDirId=$sDir.'::'.$sDir2?>
			<?php $sDirName=$sDir2;?>
			<p class="dir" style="margin-left:20px" id="linkarbo<?php echo $sDirId?>"><a href="#" onclick="openclose('arbo<?php echo $sDirId?>');return false;"><?php echo $sDirName?></a></p>
			<div id="arbo<?php echo $sDirId?>" style="display:none">
				
				<?php foreach($tContent2['dir'] as $sDir3 => $tContent3):?>
					<?php $sDirId=$sDir.'::'.$sDir2.'::'.$sDir3?>
					<?php $sDirName=$sDir3;?>
					<p class="dir" style="margin-left:40px" id="linkarbo<?php echo $sDirId?>"><a href="#" onclick="openclose('arbo<?php echo $sDirId?>');return false;"><?php echo $sDirName?></a></p>
					<div id="arbo<?php echo $sDirId?>" style="display:none">
					
						<?php foreach($tContent3['dir'] as $sDir4 => $tContent4):?>
							<?php $sDirId=$sDir.'::'.$sDir2.'::'.$sDir3.'::'.$sDir4?>
							<?php $sDirName=$sDir4;?>
							<p class="dir" style="margin-left:60px" id="linkarbo<?php echo $sDirId?>"><a href="#" onclick="openclose('arbo<?php echo $sDirId?>');return false;"><?php echo $sDirName?></a></p>
							<div id="arbo<?php echo $sDirId?>" style="display:none">
							
							
							
							</div>
						<?php endforeach;?>
						<?php /*FILE*/foreach($tContent3['file'] as $sFile => $sAdresse):?>
							<?php if(preg_match('/~$/',$sFile) or preg_match('/\.bak$/',$sFile)): continue; endif;?>
							<?php $sType=null;if($sDir=='module' and $sDir3=='view'){ $sType='view::module '.$sDir2;}?>
							<p class="file" style="margin-left:60px" id="link<?php echo $sAdresse?>"><a href="#" onclick="openFile('<?php echo $sType?>','<?php echo $sAdresse?>');return false;"><?php echo $sFile?></a></p>
						<?php endforeach;?>
						
					
					</div>
				<?php endforeach;?>
				<?php /*FILE*/foreach($tContent2['file'] as $sFile => $sAdresse):?>
					<?php if(preg_match('/~$/',$sFile) or preg_match('/\.bak$/',$sFile)): continue; endif;?>
					<?php $sType=null;if($sDir=='module' and $sFile=='main.php'){ $sType='module::'.$sDir2;}?>
					<p class="file" style="margin-left:40px" id="link<?php echo $sAdresse?>"><a href="#" onclick="openFile('<?php echo $sType?>','<?php echo $sAdresse?>');return false;"><?php echo $sFile?></a></p>
				<?php endforeach;?>
				
				
			</div>
		<?php endforeach;?>
		
		<?php /*FILE*/foreach($tContent['file'] as $sFile => $sAdresse):?>
			<?php if(preg_match('/~$/',$sFile) or preg_match('/\.bak$/',$sFile)): continue; endif;?>
			<?php $sType=null;if($sDir=='conf'){ $sType='conf';}elseif($sDir=='layout'){ $sType='layout';}elseif(preg_match('/_/',$sFile)){ list($sType,$foo)=preg_split('/_/',$sFile,0);}?>
			<p class="file" style="margin-left:20px" id="link<?php echo $sAdresse?>"><a href="#" onclick="openFile('<?php echo $sType?>','<?php echo $sAdresse?>');return false;"><?php echo $sFile?></a></p>
		<?php endforeach;?>
	</div>
<?php endforeach;?>
