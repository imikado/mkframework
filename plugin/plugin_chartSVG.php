<?php
/*
This file is part of Mkframework.

Mkframework is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License.

Mkframework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

*/
/** 
* plugin_chart classe pour generer des graphiques
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_chartSVG{

	public static $PIE='PIE';
	public static $HISTO='HISTO';
	public static $LINES='LINES';
	public static $BAR='BAR';
	
	private $iWidth;
	
	private $oChart;

	public function __construct($sType,$iWidth=null,$iHeight=null){
		$this->iWidth=$iWidth;
		$this->iHeight=$iHeight;
		
		if($sType==self::$PIE){
			$this->oChart=new plugin_chartPieSVG($this->iWidth,$this->iHeight);
		}else if($sType==self::$HISTO){
			$this->oChart=new plugin_chartHistoSVG($this->iWidth,$this->iHeight);
		}else if($sType==self::$LINES){
			$this->oChart=new plugin_chartLineSVG($this->iWidth,$this->iHeight);
		}else if($sType==self::$BAR){
			$this->oChart=new plugin_chartBarSVG($this->iWidth,$this->iHeight);
		}else{
			throw new Exception('sType non reconnu, attendu: (PIE,HISTO,LINES,BAR)');
		}
	}
	
	/**
	* charge les donnees du graphique
	* @access public
	* @param array $tData: tableau de donnees
	*/
	public function setData($tData){
		$this->oChart->setData($tData);
	}
	
	/**
	* retourne le code du graphique a afficher
	* @access public
	* @return string retourne le code du graphique
	*/
	public function show(){ 
		return $this->oChart->show();
	}
	
	public function addGroup($sLabel,$sColor){
		$this->oChart->addGroup($sLabel,$sColor);
	}
	public function addPoint($x,$y){
		$this->oChart->addPoint($x,$y);
	}
	
	public function setMarginLeft($x){
		$this->oChart->setMarginLeft($x);
	}
	public function setMaxX($x){
		$this->oChart->setMaxX($x);
	}
	public function setMinX($x){
		$this->oChart->setMinX($x);
	}
	public function setMaxY($x){
		$this->oChart->setMaxY($x);
	}
	public function setMinY($x){
		$this->oChart->setMinY($x);
	}
	public function addMarkerY($y,$color='#ccc'){
		$this->oChart->addMarkerY($y,$color);
	}
	public function setPaddingX($padding){
		$this->oChart->setPaddingX($padding);
	}
	public function setPaddingY($padding){
		$this->oChart->setPaddingY($padding);
	}
	public function setGridY($y,$color){
		$this->oChart->setGridY($y,$color);
	}
	public function setTextSizeLegend($size){
		$this->oChart->setTextSizeLegend($size);
	}
	public function setCoordLegend($x,$y){
		$this->oChart->setCoordLegend($x,$y);
	}
	public function setStepX($stepX){
		$this->oChart->setStepX($stepX);
	}
	public function setStepY($stepY){
		$this->oChart->setStepY($stepY);
	}
	
}
class abstract_pluginChartSVG{
	protected $tData;
	protected $iWidth;
	protected $height;
	
	protected $id;
	
	public static $uid=0;
	
	protected $iMax=0;
	
	protected $sHtml;
	protected $sSvg;
	
	protected $tColor;
	
	protected $iMarginLeft;
	protected $iMinX;
	protected $iMaxX;	
	protected $iMinY;
	protected $iMaxY;
	
	protected $tMarkerY=array();
	
	protected $paddingX;
	protected $paddingY;
	
	protected $gridY;
	
	protected $textsizeLegend;
	
	protected $legendX=200;
	protected $legendY=50;
	
	protected $stepX=null;
	protected $stepY=null;
	
	public function __construct($iWidth=null,$iHeight=null){
		$this->iWidth=$iWidth;
		$this->iHeight=$iHeight;
		
		$this->tColor=array(
				'green',
				'blue',
				'red',
		);
		
		self::$uid+=1;
		
		$this->id='canvasPluginChart'.self::$uid;
		
		$this->iMarginLeft=0;
		$this->textsizeLegend=12;
		
		$this->sSvg=null;
	}
	public function setData($tData){
		$this->tData=$tData;
	}
	public function setColorTab($tColor){
		$this->tColor=$tColor;
	}
	public function setMarginLeft($iMarginLeft){
		$this->iMarginLeft=$iMarginLeft;
	}
	public function setMaxX($iMaxX){
		$this->iMaxX=$iMaxX;
	}
	public function setMinX($iMinX){
		$this->iMinX=$iMinX;
	}
	public function setMaxY($iMaxX){
		$this->iMaxY=$iMaxX;
	}
	public function setMinY($iMinX){
		$this->iMinY=$iMinX;
	}
	public function addMarkerY($y,$color='#444'){
		$this->tMarkerY[]=array($y,$color);
	}
	public function setPaddingX($padding){
		$this->paddingX=$padding;
	}
	public function setPaddingY($padding){
		$this->paddingY=$padding;
	}
	public function setGridY($y,$color){
		$this->gridY=array($y,$color);
	}
	public function setTextSizeLegend($size){
		$this->textsizeLegend=$size;
	}
	public function setCoordLegend($x,$y){
		$this->legendX=$x;
		$this->legendY=$y;
	}
	
	public function setStepX($stepX){
		$this->stepX=$stepX;
	}
	public function setStepY($stepY){
		$this->stepY=$stepY;
	}
	
	
	public function loadCanvas(){
		 
	}
	
	public function startScript(){
		
		$this->sSvg.='<svg width="'.$this->iWidth.'px" height="'.$this->iHeight.'px">  ';
		
		$this->sSvg.='<style>
		.chartRect{
		cursor:help;
		}
		</style>';
		
	}
	public function endScript(){
		$this->sSvg.='</svg>';
	}
	
	protected function rect($x,$y,$iWidth,$iHeight,$sColor,$alt=null){
		$this->sSvg.='<rect class="chartRect" id="rect'.$x.$y.'" x="'.$x.'" y="'.$y.'" ';
		$this->sSvg.='width="'.$iWidth.'" height="'.$iHeight.'" style="fill:'.$sColor.'">';
		$this->sSvg.='<title>'.$alt.'</title>';
		$this->sSvg.='</rect>';
		
		 
		
	}
	
	protected function partPie($x,$y,$diameter,$degStart,$degEnd,$sColor){
		$this->sHtml.='context.fillStyle="'.$sColor.'";'."\n";
		$this->sHtml.='context.beginPath(); '."\n";
		$this->sHtml.='context.arc('.$x.','.$y.','.$diameter.','.$degStart.','.$degEnd.');'."\n";
		$this->sHtml.='context.lineTo('.$x.','.$y.');'."\n";
		$this->sHtml.='context.fill();'."\n";
		
		
		
		$this->sSvg.='<path d="M'.$x.','.$y.' L10,10 A'.$x+($diameter/2).','.$y+($diameter/2).' 0 0,1  z" ';
		$this->sSvg.='fill="'.$sColor.'"  />';
	}
	
	protected function text($x,$y,$sText,$sColor='black',$font='10px arial'){
		$this->sHtml.='context.font="'.$font.'";'."\n";
		$this->sHtml.='context.fillStyle="'.$sColor.'";   '."\n";
		$this->sHtml.='context.fillText("'.$sText.'",'.$x.','.$y.');'."\n";
		
		$this->sSvg.='<text x="'.$x.'" y="'.$y.'" fill="'.$sColor.'">'.$sText.'</text>';
	}
	
	protected function lineFromTo($x,$y,$x2,$y2,$sColor='black',$opacity=1){
		
		$this->sSvg.='<line x1="'.$x.'" y1="'.$y.'" x2="'.$x2.'" y2="'.$y2.'" ';
		$this->sSvg.='style="stroke:'.$sColor.';stroke-width:2" stroke-opacity="'.$opacity.'" />';
	}
}
class plugin_chartPieSVG extends abstract_pluginChartSVG{
	
	public function show(){
		
		$iTotal=0;
		foreach($this->tData as $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$iTotal+=$iValue;
		}
		
		$this->startScript();
		
		$diameter=($this->iWidth/4)-10;
		
		$x=$diameter+2;
		$y=$diameter+2;
		
		
		$degTotal=6.3;
		
		$degStart=0;
		
		$this->sHtml.='context.beginPath(); '."\n";
		$this->sHtml.='context.arc('.$x.','.$y.','.$diameter.',0,Math.PI*2);'."\n";
		
		$tPct=array();
		
		foreach($this->tData as $j => $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$pct=($iValue/$iTotal);
			$degEnd=$pct*$degTotal;
			$degEnd+=$degStart;
			
			$tPct[$j]=$pct*100;
			
			$this->partPie($x,$y,$diameter,$degStart,$degEnd,$this->tColor[$j]);
			
			$degStart=$degEnd;
			
		}
		
		foreach($this->tData as $i => $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$x=$this->legendX;
			$y=$i*20+$this->legendY;
			
			$this->rect($x,$y-8,10,10,$this->tColor[$i]);
			$this->text($x+16,$y,$sLabel.': '.$tPct[$i].'%','#000',$this->textsizeLegend);
			
		}
		
		$this->endScript();
		
		return "pas encore disponible";
		
		
	}
		
		
	
}
class plugin_chartHistoSVG extends abstract_pluginChartSVG{
	
	public function show(){
		
		foreach($this->tData as $tLine){
			list($sLabel,$iValue)=$tLine;
			
			if($iValue > $this->iMax){
				$this->iMax=$iValue;
			}
		}
		$iWidthBar=($this->iWidth-200)/count($this->tData);
		$iWidthBar=$iWidthBar*0.8;
				
		$this->startScript();
		
		$j=0;
		foreach($this->tData as $j=> $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$iHeight=(($iValue/$this->iMax)*($this->iHeight-24));
			
			$this->rect($j*($iWidthBar+3),$this->iHeight-$iHeight,($iWidthBar),$iHeight,$this->tColor[$j],$iValue);
			
			$j++;
		}
		
		//legend
		$i=0;
		foreach($this->tData as $j => $tDetail){
			$sLabel=$tDetail[0];
			
			$x=$this->legendX;
			$y=$i*20+$this->legendY;
			
			$this->rect($x,$y-8,10,10,$this->tColor[$j]);
			$this->text($x+16,$y,$sLabel,'#000',$this->textsizeLegend);
			
			$i++;
		}
		
		$this->lineFromTo(0,0,0,$this->iHeight);
		$this->lineFromTo(0,$this->iHeight,$this->iWidth-200,$this->iHeight);
		
		$this->endScript();
		
		return $this->sSvg;
	}
}
class plugin_chartLineSVG extends abstract_pluginChartSVG{
	
	private $tmpGroup;
	
	
	
	
	public function show(){
		
		$iMaxX=0;
		$iMaxY=0;
		
		$iMinX='';
		$iMinY='';
		
		
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				foreach($tDetail['tPoint'] as $tPoint){
				
					list($x,$y)=$tPoint;
					
					if($iMaxX < $x){
						$iMaxX=$x;
					}
					if($iMaxY < $y){
						$iMaxY=$y;
					}
					
					if($iMinX=='' or $iMinX > $x){
						$iMinX=$x;
					}
					if($iMinY=='' or $iMinY > $y){
						$iMinY=$y;
					}
					
				}
			}
		}
		
		
		if($this->iMaxX){
			$iMaxX=$this->iMaxX;
		}
		if($this->iMinX){
			$iMinX=$this->iMinX;
		}
		if($this->iMaxY){
			$iMaxY=$this->iMaxY;
		}
		if($this->iMinY!=null){
			$iMinY=$this->iMinY;
		}
		
		if($this->paddingX ){
			$iMinX-=$this->paddingX;
			$iMaxX+=$this->paddingX;
		}
		if($this->paddingY ){
			$iMinY-=$this->paddingY;
			$iMaxY+=$this->paddingY;
		}
		
		$this->startScript();
		
		$iHeight=$this->iHeight-10;
		$iWidth=$this->iWidth-200-$this->iMarginLeft;
		
		if($this->gridY){
			$step=$this->gridY[0];
			$color=$this->gridY[1];
			
			for($y=$iMinY;$y<$iMaxY;$y+=$step){
				
				$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				$this->lineFromTo($this->iMarginLeft,$y2,$this->iWidth-200,$y2,$color,0.5	);
			}
			
		}
		
		if($this->tMarkerY){
			foreach($this->tMarkerY as $tLineY){
				
				list($y,$color)=$tLineY;
				$y=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				
				$this->lineFromTo($this->iMarginLeft,$y,$this->iWidth-200,$y,$color,0.5	);
			}
		}
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				$lastX=null;
				$lastY=null;
				foreach($tDetail['tPoint'] as $j => $tPoint){
					
					list($x,$y)=$tPoint;
					
					$x2=(($x-$iMinX)/($iMaxX-$iMinX))*$iWidth+$this->iMarginLeft;
					$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
					
					$x3=$x2-3;
					$y3=$y2-3;
					
					if($x3<=0){
						$x3=0;
					}
					if($y3<=0){
						$y3=0;
					}
					
					$this->rect($x3,$y3,6,6,$tDetail['color'],$y);
					
					if($j>0){
						$this->lineFromTo($lastX,$lastY,$x2,$y2,$tDetail['color']);
						
					}
					
					$lastX=$x2;
					$lastY=$y2;
					
				}
			}
		}
		
		//legend
		$i=0;
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				$sLabel=$sGroup;
				
				$x=$this->legendX;
				$y=$i*20+$this->legendY;
				
				$this->rect($x,$y-8,10,10,$tDetail['color']);
				$this->text($x+16,$y,$sLabel,'#000',$this->textsizeLegend);
				
				$i++;
			}
		}
		
		$this->lineFromTo($this->iMarginLeft,0,$this->iMarginLeft,$this->iHeight-10);
		$this->lineFromTo($this->iMarginLeft,$this->iHeight-10,$this->iWidth-200,$this->iHeight-10);
		
		
		//step
		if($this->stepX !== null){
			for($x=($iMinX);$x<$iMaxX;$x+=$this->stepX){
				$x2=(($x-$iMinX)/($iMaxX-$iMinX))*$iWidth+$this->iMarginLeft;
				
				$this->lineFromTo($x2,($this->iHeight-10),$x2,($this->iHeight-5) );
				
				$this->text($x2+2,($this->iHeight),$x);
			}
		}else{
			$this->text(0,$this->iHeight,$iMinX);
		
			$this->text($this->iWidth-200,$this->iHeight,$iMaxX);
		}
		
		//step
		if($this->stepY !== null){
			for($y=($iMinY);$y<$iMaxY;$y+=$this->stepY){
				$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				
				$this->lineFromTo($this->iMarginLeft-5,$y2,$this->iMarginLeft,$y2 );
				
				$this->text(0,$y2,$y);
			}
		}else{
			$this->text(0,10,$iMaxY);
			$this->text(0,$this->iHeight-10	,$iMinY);
		}
		
		
		$this->endScript();
		
		return $this->sSvg;
	}
	
	
	public function addGroup($sLabel,$sColor){
		$this->tmpGroup=$sLabel;
		
		$this->tData[$this->tmpGroup]['label']=$sLabel;
		$this->tData[$this->tmpGroup]['color']=$sColor;
	}
	public function addPoint($x,$y){
		$this->tData[$this->tmpGroup]['tPoint'][]=array($x,$y);
	}
	
}
class plugin_chartBarSVG extends abstract_pluginChartSVG{
	
	private $tmpGroup;
	
	
	
	
	public function show(){		
		$iMaxX=0;
		$iMaxY=0;
		
		$iMinX='';
		$iMinY='';
		
		
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				foreach($tDetail['tPoint'] as $tPoint){
				
					list($x,$y)=$tPoint;
					
					if($iMaxX < $x){
						$iMaxX=$x;
					}
					if($iMaxY < $y){
						$iMaxY=$y;
					}
					
					if($iMinX=='' or $iMinX > $x){
						$iMinX=$x;
					}
					if($iMinY=='' or $iMinY > $y){
						$iMinY=$y;
					}
					
				}
			}
		}
		
		if($this->iMaxX){
			$iMaxX=$this->iMaxX;
		}
		if($this->iMinX){
			$iMinX=$this->iMinX;
		}
		if($this->iMaxY){
			$iMaxY=$this->iMaxY;
		}
		if($this->iMinY!=null){
			$iMinY=$this->iMinY;
		}
		
		if($this->paddingX ){
			$iMinX-=$this->paddingX;
			$iMaxX+=$this->paddingX;
		}
		if($this->paddingY ){
			$iMinY-=$this->paddingY;
			$iMaxY+=$this->paddingY;
		}
		
		$this->startScript();
		
		$iHeight=$this->iHeight-10;
		$iWidth=$this->iWidth-200-$this->iMarginLeft-(4);
		
		if($this->gridY){
			$step=$this->gridY[0];
			$color=$this->gridY[1];
			
			for($y=$iMinY;$y<$iMaxY;$y+=$step){
				
				$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				$this->lineFromTo($this->iMarginLeft,$y2,$this->iWidth-200,$y2,$color,0.5	);
			}
			
		}
		
		if($this->tMarkerY){
			foreach($this->tMarkerY as $tLineY){
				
				list($y,$color)=$tLineY;
				$y=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				
				$this->lineFromTo($this->iMarginLeft,$y,$this->iWidth-200,$y,$color,0.5	);
			}
		}
	
		$k=0;
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				$lastX=null;
				$lastY=null;
				foreach($tDetail['tPoint'] as $tPoint){
					
					list($x,$y)=$tPoint;
					
					$x2=(($x-$iMinX)/($iMaxX-$iMinX))*$iWidth+$this->iMarginLeft;
					$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
					
					$x3=$x2;
					$y3=$y2-3;
					
					if($x3<=0){
						$x3=0;
					}
					if($y3<=0){
						$y3=0;
					}
					
					$this->rect($x3+($k*8),$y3,6,$iHeight-$y3,$tDetail['color'],$y);
					
				 
					$lastX=$x2;
					$lastY=$y2;
					
				}
				$k++;
			}
		}
		
		//legend
		$i=0;
		if($this->tData){
			foreach($this->tData as $sGroup => $tDetail){
				$sLabel=$sGroup;
				
				$x=$this->legendX;
				$y=$i*20+$this->legendY;
				
				$this->rect($x,$y-8,10,10,$tDetail['color']);
				$this->text($x+16,$y,$sLabel,'#000',$this->textsizeLegend);
				
				$i++;
			}
		}
		
		$this->lineFromTo($this->iMarginLeft,0,$this->iMarginLeft,$this->iHeight-10);
		$this->lineFromTo($this->iMarginLeft,$this->iHeight-10,$this->iWidth-200,$this->iHeight-10);
		
		
		//step
		if($this->stepX !== null){
			for($x=($iMinX);$x<$iMaxX;$x+=$this->stepX){
				$x2=(($x-$iMinX)/($iMaxX-$iMinX))*$iWidth+$this->iMarginLeft;
				
				$this->lineFromTo($x2,($this->iHeight-10),$x2,($this->iHeight-5) );
				
				$this->text($x2+2,($this->iHeight),$x);
			}
		}else{
			$this->text(0,$this->iHeight,$iMinX);
		
			$this->text($this->iWidth-200,$this->iHeight,$iMaxX);
		}
		
		//step
		if($this->stepY !== null){
			for($y=($iMinY);$y<$iMaxY;$y+=$this->stepY){
				$y2=(1-($y-$iMinY)/($iMaxY-$iMinY))*$iHeight;
				
				$this->lineFromTo($this->iMarginLeft-5,$y2,$this->iMarginLeft,$y2 );
				
				$this->text(0,$y2,$y);
			}
		}else{
			$this->text(0,10,$iMaxY);
			$this->text(0,$this->iHeight-10	,$iMinY);
		}
		
		
		$this->endScript();
		
		return $this->sSvg;
	}
	
	
	public function addGroup($sLabel,$sColor){
		$this->tmpGroup=$sLabel;
		
		$this->tData[$this->tmpGroup]['label']=$sLabel;
		$this->tData[$this->tmpGroup]['color']=$sColor;
	}
	public function addPoint($x,$y){
		$this->tData[$this->tmpGroup]['tPoint'][]=array($x,$y);
	}
	
}
