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
	protected $tStyleColumn;
	
	protected $sTableOption=null;
	
	protected $bodyNbLine=25;
	protected $bodyHeight=300;
	
	protected $tableWidth=50;
	
	protected $bPaginationEnabled=0;
	protected $iPaginationLimit=0;
	protected $iPaginationPage=0;
	protected $iPaginationMax;
	protected $sPaginationParam='page';
	
	protected $bPaginationServerEnabled=0;
	
	protected $ajaxLink=null;
	
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
	
	/** retourne l'offset de la requete LIMIT offset,limit
	 * @param int $iLimit limite (nb de lignes affichees par page)
	 * */
	public static function getOffset($iLimit){
		return ((int)self::getParam('page',1)-1)*$iLimit;
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
		if(!isset($this->tAltCycle[$id])){
			return null;
		}
		
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
	
	public function setColumnStyle($tStyleColumn){
		$this->tStyleColumn=$tStyleColumn;
	}
	
	public function setLimitLine($bodyNbLine,$bodyHeight){
		$this->bodyNbLine=$bodyNbLine;
		$this->bodyHeight=$bodyHeight;
	}
	
	public function setWidthTable($tableWidth){
		$this->tableWidth=$tableWidth;
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
	
	//pagination
	public function enablePagination(){
		$this->bPaginationEnabled=1;
	}
	public function setPaginationLimit($iLimit){
		$this->iPaginationLimit=$iLimit;
	}
	public function selectPaginationPage($iPage){
		$this->iPaginationPage=($iPage-1);
		if($this->iPaginationPage==-1) $this->iPaginationPage=0;
	}
	public function setPaginationParam($sVar){
		$this->sPaginationParam=$sVar;
	}
	//pagination server
	public function enablePaginationServer(){
		$this->bPaginationServerEnabled=1;
	}
	public function setPaginationMax($iMax){
		$this->iPaginationMax=$iMax;
	}
	
	public function setAjaxLink($sNav){
		$this->ajaxLink=$sNav;
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
		$oView->tStyleColumn=$this->tStyleColumn;
		
		$oView->bodyNbLine=$this->bodyNbLine;
		$oView->bodyHeight=$this->bodyHeight;
		
		$oView->tableWidth=$this->tableWidth;
		
		if($this->bPaginationServerEnabled){
			$this->selectPaginationPage( self::getParam($this->sPaginationParam) );
			$oView->ajaxLink=$this->ajaxLink;
			
			$oViewPagination=new _view('table::pagination');
			$oViewPagination->iPage=$this->iPaginationPage;
			$oViewPagination->iMax=ceil( ($this->iPaginationMax/$this->iPaginationLimit) );
			$oViewPagination->sParamPage=$this->sPaginationParam;
			$oViewPagination->ajaxLink=$this->ajaxLink;

			$oView->oModulePagination=$oViewPagination;
			
		}else if($this->bPaginationEnabled){
	
			$this->selectPaginationPage( self::getParam($this->sPaginationParam) );
			$oView->tLine=$this->getPaginationPage();
			$oView->ajaxLink=$this->ajaxLink;
			
			$oViewPagination=new _view('table::pagination');
			$oViewPagination->iPage=$this->iPaginationPage;
			$oViewPagination->iMax=ceil( ($this->iPaginationMax/$this->iPaginationLimit) );
			$oViewPagination->sParamPage=$this->sPaginationParam;
			$oViewPagination->ajaxLink=$this->ajaxLink;

			$oView->oModulePagination=$oViewPagination;
			
		}
		
		
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
	
	private function getPaginationPage(){
		$tPartElement=array();
		
		$this->iPaginationMax=count($this->tLine);
		
		$iMin=$this->iPaginationPage*$this->iPaginationLimit;
		$iPart=$iMin+$this->iPaginationLimit;
		if($iPart > $this->iPaginationMax){
			$iPart=$this->iPaginationMax;
		}
		
		for($i=$iMin;$i<$iPart;$i++){
			$tPartElement[]=$this->tLine[$i];
		}
		return $tPartElement;
	}
}
