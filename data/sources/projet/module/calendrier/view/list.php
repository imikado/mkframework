<?php
	$lienOn=$this->bLienEnabled;
	$sModuleAction=$this->sModuleAction;
	$classOn=$this->sClassOn;


	$jourJ=date("d");
	
	if(_root::getParam($this->sNavMois) != null ){
		$iMois=(int)_root::getParam($this->sNavMois);
		$iAnnee=(int)_root::getParam($this->sNavAnnee);
	}
	else{
		$iMois=(int)date("m");
		$iAnnee=(int)date("Y");
	}
	
	$sMois=sprintf('%02d',$iMois);
	$sAnnee=sprintf('%04d',$iAnnee);
	
	
	$sDate=$sAnnee.'-'.$sMois.'-01';
	
	$tabJ=array(
		'',
		'L',
		'M',
		'M',
		'J',
		'V',
		'S',
		'D'
		);
	
	//annee avant
	$iAnneeAvant=$iAnnee-1;
	$tParamTmp=$this->tParam;
	$tParamTmp[ $this->sNavMois ]=$iMois;
	$tParamTmp[ $this->sNavAnnee ]=$iAnneeAvant;
	$sLienAnneeAvant='<a href="'._root::getLink($this->sModuleAction,$tParamTmp).'">&lt;&lt;</a>';
	
	//annee apres
	$iAnneeApres=$iAnnee+1;
	$tParamTmp=$this->tParam;
	$tParamTmp[ $this->sNavMois ]=$iMois;
	$tParamTmp[ $this->sNavAnnee ]=$iAnneeApres;
	$sLienAnneeApres='<a href="'._root::getLink($this->sModuleAction,$tParamTmp).'">&gt;&gt;</a>';
	
	//mois avant	
	$oDate=new plugin_date($sDate);
	$oDate->removeMonth(1);
	$iMoisAvant=$oDate->getMonth();
	$iAnneeAvant=$oDate->getYear();
	$tParamTmp=$this->tParam;
	$tParamTmp[ $this->sNavMois ]=$iMoisAvant;
	$tParamTmp[ $this->sNavAnnee ]=$iAnneeAvant;
	$sLienMoisAvant='<a href="'._root::getLink($this->sModuleAction,$tParamTmp).'">&lt;&lt;</a>';
	
	//mois apres
	$oDate2=new plugin_date($sDate);
	$oDate2->removeMonth(1);
	$iMoisApres=$oDate2->getMonth();
	$iAnneeApres=$oDate2->getYear();
	$tParamTmp=$this->tParam;
	$tParamTmp[ $this->sNavMois ]=$iMoisApres;
	$tParamTmp[ $this->sNavAnnee ]=$iAnneeApres;
	$sLienMoisApres='<a href="'._root::getLink($this->sModuleAction,$tParamTmp).'">&gt;&gt;</a>';
	
	
	$navAnnee=$sLienAnneeAvant." ".$sAnnee." ".$sLienAnneeApres;
	$navMois=$sLienMoisAvant." ".$this->tMois[$iMois]." ".$sLienMoisApres;
?><table>
	<tr>
		<td colspan="7" style="text-align:center">
		<?php echo $navAnnee;?>
		</td>
	</tr>
	<tr>
		<td colspan="7" style="text-align:center">
		<?php echo $navMois;?>
		</td>
	</tr>
	<tr>
		<?php for($j=1;$j<=7;$j++):?>
		<th><?php echo $tabJ[$j] ?></th>
		<?php endfor;?>
	</tr>
	<?php
	//$s semaine
	//$j jour
	$jour=0;
	$finDuMois=false;
	$tParamTmp=$this->tParam;
	$tParamTmp[ $this->sNavMois ]=$sMois;
	$tParamTmp[ $this->sNavAnnee ]=$sAnnee;
	
	$jour=1;
	$premierJour=intVal( date("w", mktime(0, 0, 0, $iMois, 1, $iAnnee) ));
	$dernierJour=intval( date("j", mktime(0, 0, 0, $iMois+1, 0, $iAnnee) ) );
	?>
	<?php for($semaine=0;$semaine <= 6; $semaine++):?>
		<tr>
		<?php for($j=1;$j<=7;$j++):?>
			<?php
			$tParamTmp[$this->sNavJour]=$jour;
			?>
			<?php if($semaine==0):?>
				<?php if($j >= $premierJour):?>
					<td><a href="<?php echo _root::getLink($sModuleAction,$tParamTmp);?>"><?php echo $jour?></a></td>
					<?php $jour+=1;?>
				<?php else:?>
					<td></td>
				<?php endif;?>
			<?php else:?>
				<?php if($jour <= $dernierJour):?>
					<td><a href="<?php echo _root::getLink($sModuleAction,$tParamTmp);?>"><?php echo $jour?></a></td>
					<?php $jour+=1;?>
				<?php else:?>
					<td></td>
				<?php endif;?>
			<?php endif;?>
		<?php endfor;?>
		</tr>
	<?php endfor;?>
</table>
