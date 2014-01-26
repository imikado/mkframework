<?php 
class module_chart extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('html5');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _examples(){
		
		$oView=new _view('chart::charts');
		
		 //histo
	    $tData=array(
			array( 2011 , 50),
			array( 2012 , 70),
			array( 2013 , 45)
		);
		
	    $oChartHisto=new plugin_chart(400,200);
	    $oChartHisto->chooseHisto();
	    $oChartHisto->setData($tData);
		
		$oView->oChartHisto=$oChartHisto;
		
		//pie
		$tData=array(
			array( 'bois' , 120),
			array( 'fer' , 15),
			array( 'or' , 65),
		);
		
		$oChartPie=new plugin_chart(400,200);
		$oChartPie->choosePie();
		$oChartPie->setData($tData);
		
		$oView->oChartPie=$oChartPie;
		
		
		 //lines
	    $tData=array(
			array( 2011 , 50,45,35,20),
			array( 2012 , 70,55,45,22),
			array( 2013 , 50,35,20,12),
		);
		
	    $oChartLine=new plugin_chart(400,200);
	    $oChartLine->chooseLines();
	    $oChartLine->setData($tData);
		
		$oView->oChartLine=$oChartLine;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
