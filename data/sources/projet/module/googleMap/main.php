<?php
class module_googleMap extends abstract_moduleembedded{
	
	protected $tPosition;
	protected $iWidth;
	protected $iHeight;
	protected $iZoom;
	
	public function __construct(){
		$this->tPosition=array();
		$this->iWidth=500;
		$this->iHeight=500;
		$this->iZoom=1;
	}
	
	public function getMap(){
		
		$oView=new _view('googleMap::map');
		$oView->tPosition=$this->tPosition;
		$oView->iWidth=$this->iWidth;
		$oView->iHeight=$this->iHeight;
		$oView->iZoom=$this->iZoom;
		
		return $oView;
		
	}
	
	public function addPosition($sAdresse,$sTitle=null,$sLink=null){
		$this->tPosition[]=array($sAdresse,$sTitle,$sLink);
	}
	
	public function setWidth($iWidth){
		$this->iWidth=$iWidth;
	}
	public function setHeight($iHeight){
		$this->iHeight=$iHeight;
	}
	public function setZoom($iZoom){
		$this->iZoom=$iZoom;
	}
}
