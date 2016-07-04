<?php 
class module_chart extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('html5');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _examples(){
		
		$oView=new _view('chart::charts');
		
		//--------------------------------
		//--------histo
	    $tData=array(
			array( 2011 , 50),
			array( 2012 , 70),
			array( 2013 , 45)
		);
		
	    $oChartHisto=new plugin_chart('HISTO',400,200);
	    $oChartHisto->setTextSizeLegend('12px arial');
	    $oChartHisto->setData($tData);

	    $oChartHisto->setMarginLeft(20);

	    $oChartHisto->setStepY(10);
	    $oChartHisto->setGridY(10,'#444');
		
		//coordonnees de la legende
	    $oChartHisto->setCoordLegend(220,10);
		
		$oView->oChartHisto=$oChartHisto;
		
		
		//--------------------------------
		//--------pie
		$tData=array(
			array( 'bois' , 120),
			array( 'fer' , 15),
			array( 'or' , 65),
		);
		
		$oChartPie=new plugin_chart('PIE',400,200);
		$oChartPie->setTextSizeLegend('12px arial');
		$oChartPie->setData($tData);
		
		//coordonnees de la legende
	    $oChartPie->setCoordLegend(220,10);
		
		$oView->oChartPie=$oChartPie;
		
		
		//--------------------------------
		//--------lines
	    $oChartLine=new plugin_chart('LINES',400,200);
	    $oChartLine->setTextSizeLegend('12px arial');
	    $oChartLine->setMarginLeft(20);
	    $oChartLine->setPaddingX(1);
	    $oChartLine->setPaddingY(20);
	    
	    //coordonnees de la legende
	    $oChartLine->setCoordLegend(220,10);
	    
	    $oChartLine->setStepX(2);
	    $oChartLine->setStepY(20);
	    
	    //$oChartLine->addMarkerY(100,'#444');
	    $oChartLine->setGridY(20,'#444');
	    $oChartLine->addGroup('or','green');
			$oChartLine->addPoint(2009,100);
			$oChartLine->addPoint(2011,110);
			$oChartLine->addPoint(2014,170);
			
			
		$oChartLine->addGroup('bois','blue');
			$oChartLine->addPoint(2010,80);
			$oChartLine->addPoint(2011,20);
			$oChartLine->addPoint(2013,170);
		
		$oView->oChartLine=$oChartLine;
		
		//--------------------------------
		//--------bar
	    $oChartBar=new plugin_chart('BAR',400,200);
	    $oChartBar->setTextSizeLegend('12px arial');
	    $oChartBar->setMarginLeft(20);
	    $oChartBar->setPaddingX(1);
	    $oChartBar->setPaddingY(20);
	    
	    //coordonnees de la legende
	    $oChartBar->setCoordLegend(220,10);
	    
	    $oChartBar->setStepX(2);
	    $oChartBar->setStepY(20);
	    
	    //$oChartLine->addMarkerY(100,'#444');
	    $oChartBar->setGridY(20,'#444');
	    $oChartBar->addGroup('or','green');
			$oChartBar->addPoint(2010,100);
			$oChartBar->addPoint(2012,110);
			$oChartBar->addPoint(2014,90);
			$oChartBar->addPoint(2016,170);
			
		$oChartBar->addGroup('bois','blue');
			$oChartBar->addPoint(2010,90);
			$oChartBar->addPoint(2012,120);
			$oChartBar->addPoint(2014,80);
			$oChartBar->addPoint(2016,170);
			
		$oChartBar->addGroup('fer','red');
			$oChartBar->addPoint(2010,110);
			$oChartBar->addPoint(2012,120);
			$oChartBar->addPoint(2014,70);
			$oChartBar->addPoint(2016,150);
		
		
		$oView->oChartBar=$oChartBar;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function _examplesSVG(){
		
		$oView=new _view('chart::chartsSvg');
		
		//--------------------------------
		//--------histo
	    $tData=array(
			array( 2011 , 50),
			array( 2012 , 70),
			array( 2013 , 45)
		);
		
	    $oChartHisto=new plugin_chartSVG('HISTO',400,200);
	    $oChartHisto->setTextSizeLegend('12px arial');
	    $oChartHisto->setData($tData);
		
		//coordonnees de la legende
	    $oChartHisto->setCoordLegend(220,10);
		
		$oView->oChartHisto=$oChartHisto;
		
		
		//--------------------------------
		//--------pie
		$tData=array(
			array( 'bois' , 120),
			array( 'fer' , 15),
			array( 'or' , 65),
		);
		
		$oChartPie=new plugin_chartSVG('PIE',400,200);
		$oChartPie->setTextSizeLegend('12px arial');
		$oChartPie->setData($tData);
		
		//coordonnees de la legende
	    $oChartPie->setCoordLegend(220,10);
		
		$oView->oChartPie=$oChartPie;
		
		
		//--------------------------------
		//--------lines
	    $oChartLine=new plugin_chartSVG('LINES',400,200);
	    $oChartLine->setTextSizeLegend('12px arial');
	    $oChartLine->setMarginLeft(20);
	    $oChartLine->setPaddingX(1);
	    $oChartLine->setPaddingY(20);
	    
	    //coordonnees de la legende
	    $oChartLine->setCoordLegend(220,10);
	    
	    $oChartLine->setStepX(2);
	    $oChartLine->setStepY(20);
	    
	    //$oChartLine->addMarkerY(100,'#444');
	    $oChartLine->setGridY(20,'#444');
	    $oChartLine->addGroup('or','green');
			$oChartLine->addPoint(2009,100);
			$oChartLine->addPoint(2011,110);
			$oChartLine->addPoint(2014,170);
			
			
		$oChartLine->addGroup('bois','blue');
			$oChartLine->addPoint(2010,80);
			$oChartLine->addPoint(2011,20);
			$oChartLine->addPoint(2013,170);
		
		$oView->oChartLine=$oChartLine;
		
		//--------------------------------
		//--------bar
	    $oChartBar=new plugin_chartSVG('BAR',400,200);
	    $oChartBar->setTextSizeLegend('12px arial');
	    $oChartBar->setMarginLeft(20);
	    $oChartBar->setPaddingX(1);
	    $oChartBar->setPaddingY(20);
	    
	    //coordonnees de la legende
	    $oChartBar->setCoordLegend(220,10);
	    
	    $oChartBar->setStepX(2);
	    $oChartBar->setStepY(20);
	    
	    //$oChartLine->addMarkerY(100,'#444');
	    $oChartBar->setGridY(20,'#444');
	    $oChartBar->addGroup('or','green');
			$oChartBar->addPoint(2010,100);
			$oChartBar->addPoint(2012,110);
			$oChartBar->addPoint(2014,90);
			$oChartBar->addPoint(2016,170);
			
		$oChartBar->addGroup('bois','blue');
			$oChartBar->addPoint(2010,90);
			$oChartBar->addPoint(2012,120);
			$oChartBar->addPoint(2014,80);
			$oChartBar->addPoint(2016,170);
			
		$oChartBar->addGroup('fer','red');
			$oChartBar->addPoint(2010,110);
			$oChartBar->addPoint(2012,120);
			$oChartBar->addPoint(2014,70);
			$oChartBar->addPoint(2016,150);
		
		
		$oView->oChartBar=$oChartBar;
		
		$this->oLayout->add('main',$oView);
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
