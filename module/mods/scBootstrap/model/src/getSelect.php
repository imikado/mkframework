
	public function getSelect(){
		$tab=$this->findAll();
		$tSelect=array();
		if($tab){
		foreach($tab as $oRow){
			$tSelect[ $oRow->exampleselectkey ]=$oRow->exampleselectval;
		}
		}
		return $tSelect;
	}
	
