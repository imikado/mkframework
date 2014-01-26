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
		
	    $oChartHisto=new plugin_chart('HISTO',400,200);
	    $oChartHisto->setData($tData);
		
		$oView->oChartHisto=$oChartHisto;
		
		//pie
		$tData=array(
			array( 'bois' , 120),
			array( 'fer' , 15),
			array( 'or' , 65),
		);
		
		$oChartPie=new plugin_chart('PIE',400,200);
		$oChartPie->setData($tData);
		
		$oView->oChartPie=$oChartPie;
		
		//lines
	    $oChartLine=new plugin_chart('LINES',400,200);
	    $oChartLine->addGroup('or','green');
			$oChartLine->addPoint(2009,100);
			$oChartLine->addPoint(2011,110);
			$oChartLine->addPoint(2014,170);
			
			
		$oChartLine->addGroup('bois','blue');
			$oChartLine->addPoint(2010,80);
			$oChartLine->addPoint(2011,20);
			$oChartLine->addPoint(2013,170);
		
		$oView->oChartLine=$oChartLine;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
