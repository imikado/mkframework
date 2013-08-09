<?php
Class module_calendrier extends abstract_module{
	
	public $bNavigationEnabled=true;
	public $sModuleAction='';
	public $tParam='';
	public $sClass='';
	public $sClassOn='';
	public $bLienEnabled=true;
	
	public $sNavMois='mois';
	public $sNavAnnee='annee';
	public $sNavJour='jour';
	
	public $tMois;
	
	public function module_calendrier(){
		$this->tMois=array(
		'',
		'Janvier',
		'Fevrier',
		'Mars',
		'Avril',
		'Mai',
		'Juin',
		'Juillet',
		'Aout',
		'Septembre',
		'Octobre',
		'Novembre',
		'Decembre',
		);
	}
	
	public function build(){
		$oView=new _view('calendrier::list');
		
		//-----------------------------CONFIGURATION------------------------------
		//ici on parametre
		$oView->navigation=$this->bNavigationEnabled; //oui non
		$oView->sModuleAction=$this->sModuleAction;
		$oView->tParam=$this->tParam;
		$oView->sClass=$this->sClass;
		$oView->sClassOn=$this->sClassOn;
		$oView->bLienEnabled=$this->bLienEnabled;
		
		$oView->sNavMois=$this->sNavMois;
		$oView->sNavAnnee=$this->sNavAnnee;
		$oView->sNavJour=$this->sNavJour;
		
		$oView->tMois=$this->tMois;
		//-----------------------------Fin CONFIGURATION----------------------------
		return $oView;
	}
	
}
