<?php 
class module_table extends abstract_moduleembedded{
	
	public static $sModuleName='table';
	public static $sRootModule;
	public static $tRootParams;
	
	protected $sClass=null;
	
	protected $bTr=0;
	protected $tAltCycle;
	protected $tCountCycle;
	protected $tCycle;
	
	protected $tLine;
	protected $iCurrentLine=-1;
	protected $tHeader;
	protected $tClassColumn;
	
	protected $sTableOption=null;
	
	public function __construct($sView='simple'){
		self::setRootLink(_root::getParamNav(),null);
		
		$this->sView=$sView;
	}
	public static function setRootLink($sRootModule,$tRootParams=null){
		self::$sRootModule=$sRootModule;
		self::$tRootParams=$tRootParams;
	}
	public static function getLink($sAction,$tParam=null){
		return parent::_getLink(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sAction,$tParam);
	}
	public static function getParam($sVar,$uDefault=null){
		return parent::_getParam(self::$sModuleName,$sVar,$uDefault);
	}
	public static function redirect($sModuleAction,$tModuleParam=null){
		return parent::_redirect(self::$sRootModule,self::$tRootParams,self::$sModuleName,$sModuleAction,$tModuleParam);
	}
 
	public function setClass($sClass){
		$this->sClass=$sClass;
	}
	public function setCycleClass($tCycle,$id=0){
		$this->tCycle[$id]=$tCycle;
		$this->tCountCycle[$id]=count($tCycle);
		$this->tAltCycle[$id]=-1;
	}
	public function cycle($id=0){
		$this->tAltCycle[$id]+=1;
		if($this->tAltCycle[$id] >= $this->tCountCycle[$id]){
			$this->tAltCycle[$id]=0;
		}
		return $this->tCycle[$id][ $this->tAltCycle[$id] ];
	}
	
	public function addLine($tCell){
		$sCycle=$this->cycle();
		if($sCycle){
			$sCycle='class="'.$sCycle.'"';
		}
		$this->tLine[]=array(
						'options'=>$sCycle,
						'cell'=>$tCell
						);
	}
	
	public function setHeader($tHeader){
		$this->tHeader=$tHeader;
	}
	
	public function addLineWithLink($tCell,$sLink){
		$this->tLine[]=array(
						'options'=>'class="'.$this->cycle().'"',
						'cell'=>$tCell,
						'link'=>$sLink
						);
	}
	
	public function setColumnClass($tClassColumn){
		$this->tClassColumn=$tClassColumn;
	}
	
	/*
	public function tr($uOption=null){
		$this->iCurrentLine++;
		
		$sOption=$this->getOption($uOption);
		
		$this->tLine[ $this->iCurrentLine ]=array('options'=>$sOption);
	}
	
	public function td($sValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		$this->tLine[ $this->iCurrentLine ]['cell'][]=array('td',$sValue,$sOption);
	}
	public function tdList($tValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		foreach($tValue as $sValue){
			$this->tLine[ $this->iCurrentLine ]['cell'][]=array('td',$sValue,$sOption);
		}
	}

	public function th($sValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		$this->tLine[ $this->iCurrentLine ]['cell'][]=array('th',$sValue,$sOption);
	}*/
	
	public function addHeader($sLabel){
		$this->tHeader[]=array('label'=>$sLabel);
	}
	
	public function addHeaderWithOrder($sLabel,$sOrder=null){
		$this->tHeader[]=array('label'=>$sLabel,'order'=>$sOrder);
	}
	
	public function setLineLink($sLink){
		$this->tLine[ $this->iCurrentLine ]['link']=$sLink;
	}
	
	/*
	Pour integrer au sein d'un autre module:
	
	//instancier le module
	$oModuleExamplemodule=new module_examplemodule();
	
	//si vous souhaitez indiquer au module integrable des informations sur le module parent
	//$oModuleExamplemodule->setRootLink('module::action',array('parametre'=>_root::getParam('parametre')));
	
	//recupere la vue du module
	$oViewModule=$oModuleExamplemodule->_index();
	
	//assigner la vue retournee a votre layout
	$this->oLayout->add('main',$oViewModule);
	*/
	
	public function build(){
		
		$oView=new _view('table::'.$this->sView);
		$oView->sClass=$this->sClass;
		$oView->tLine=$this->tLine;
		$oView->tHeader=$this->tHeader;
		$oView->tClassColumn=$this->tClassColumn;
		
		return $oView;
	}
	
	private function getOption($uOption){
		if($uOption==null){ return null;}
		if(!is_array($uOption)){ return ' '.$uOption; }
		
		$sOption='';
		foreach($uOption as $sVar => $sVal){
			$sOption.=' '.$sVar.'='.'"'.preg_replace("/'/",'\'',$sVal).'"';
		}
		return $sOption;
	}
	
	
}
