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
class plugin_chart{

	public static $PIE='PIE';
	public static $HISTO='HISTO';
	public static $LINES='LINES';

	private $sType;
	
	private $tData;
	private $iWidth;
	private $height;
	
	private $oChart;

	public function __construct($sType,$iWidth=null,$iHeight=null){
		$this->iWidth=$iWidth;
		$this->iHeight=$iHeight;
		
		if($sType==self::$PIE){
			$this->oChart=new plugin_chartPie($this->iWidth,$this->iHeight);
		}else if($sType==self::$HISTO){
			$this->oChart=new plugin_chartHisto($this->iWidth,$this->iHeight);
		}else if($sType==self::$LINES){
			$this->oChart=new plugin_chartLine($this->iWidth,$this->iHeight);
		}else{
			throw new Exception('sType non reconnu, attendu: (PIE,HISTO,LINES)');
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
	
}
class abstract_pluginChart{
	protected $tData;
	protected $iWidth;
	protected $height;
	
	protected $id;
	
	public static $uid=0;
	
	protected $iMax=0;
	
	protected $sHtml;
	
	protected $tColor;
	
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
	}
	public function setData($tData){
		$this->tData=$tData;
	}
	public function setColorTab($tColor){
		$this->tColor=$tColor;
	}
	
	public function loadCanvas(){
		$this->sHtml.='<canvas id="'.$this->id.'" width="'.$this->iWidth.'px" height="'.$this->iHeight.'px" ></canvas>';
		
		$this->startScript();
		
		$this->sHtml.='var canvas = document.getElementById("'.$this->id.'"); ';
		$this->sHtml.='var context = canvas.getContext("2d")';
		
		$this->endScript();
	}
	
	public function startScript(){
		$this->sHtml.='<script>';
	}
	public function endScript(){
		$this->sHtml.='</script>';
	}
	
	protected function rect($x,$y,$iWidth,$iHeight,$sColor){
		$this->sHtml.='context.beginPath();'."\n";
		$this->sHtml.='context.fillStyle="'.$sColor.'";   '."\n";
		$this->sHtml.='context.rect('.$x.','.$y.','.$iWidth.','.$iHeight.');'."\n";
		$this->sHtml.='context.fill();'."\n";
	}
	
	protected function partPie($x,$y,$diameter,$degStart,$degEnd,$sColor){
		$this->sHtml.='context.fillStyle="'.$sColor.'";'."\n";
		$this->sHtml.='context.beginPath(); '."\n";
		$this->sHtml.='context.arc('.$x.','.$y.','.$diameter.','.$degStart.','.$degEnd.');'."\n";
		$this->sHtml.='context.lineTo('.$x.','.$y.');'."\n";
		$this->sHtml.='context.fill();'."\n";
	}
	
	protected function text($x,$y,$sText,$sColor='black'){
		$this->sHtml.='context.fillStyle="'.$sColor.'";   '."\n";
		$this->sHtml.='context.fillText("'.$sText.'",'.$x.','.$y.');'."\n";
	}
	
	protected function lineFromTo($x,$y,$x2,$y2,$sColor='black'){
		$this->sHtml.='context.strokeStyle="'.$sColor.'";'."\n";
		$this->sHtml.='context.beginPath(); '."\n";
		$this->sHtml.='context.moveTo('.$x.','.$y.'); '."\n";
		$this->sHtml.='context.lineTo('.$x2.','.$y2.');'."\n";
		$this->sHtml.='context.stroke();'."\n";
	}
}
class plugin_chartPie extends abstract_pluginChart{
	
	public function show(){
		
		
		$this->loadCanvas();
		
		$iTotal=0;
		foreach($this->tData as $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$iTotal+=$iValue;
		}
		
		$this->startScript();
		
		$x=100;
		$y=100;
		$diameter=50;
		$degTotal=6.3;
		
		$degStart=0;
		
		$this->sHtml.='context.beginPath(); '."\n";
		$this->sHtml.='context.arc('.$x.','.$y.','.$diameter.',0,Math.PI*2);'."\n";
		$this->sHtml.='context.stroke();'."\n";
		
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
			
			$x=200;
			$y=$i*20+50;
			
			$this->rect($x,$y-8,10,10,$this->tColor[$i]);
			$this->text($x+16,$y,$sLabel.': '.$tPct[$i].'%');
			
		}
		
		$this->endScript();
		
		return $this->sHtml;
	}
		
		
	
}
class plugin_chartHisto extends abstract_pluginChart{
	
	public function show(){
		$this->loadCanvas();
		
		foreach($this->tData as $tLine){
			list($sLabel,$iValue)=$tLine;
			
			if($iValue > $this->iMax){
				$this->iMax=$iValue;
			}
		}
		$iWidthBar=($this->iWidth-200)/count($this->tData);
		$iWidthBar=$iWidthBar*0.8;
		
		$iWidthSpace=$iWidthBar*0.2;
		
		$this->startScript();
		
		
		
		$j=0;
		foreach($this->tData as $j=> $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$iHeight=1-(($iValue/$this->iMax)*($this->iHeight-24));
			
			$this->rect($j*($iWidthBar+3),$this->iHeight,($iWidthBar),$iHeight,$this->tColor[$j]);
			
			$j++;
		}
		
		foreach($this->tData as $i => $tLine){
			list($sLabel,$iValue)=$tLine;
			
			$x=200;
			$y=$i*20+50;
			
			$this->rect($x,$y-8,10,10,$this->tColor[$i]);
			$this->text($x+16,$y,$sLabel.': '.$iValue.'');
			
		}
		
		$this->lineFromTo(0,0,0,$this->iHeight);
		$this->lineFromTo(0,$this->iHeight,$this->iWidth-200,$this->iHeight);
		
		$this->endScript();
		
		return $this->sHtml;
	}
}
class plugin_chartLine extends abstract_pluginChart{
	
	private $tmpGroup;
	
	public function show(){
		$this->loadCanvas();
		
		$iMaxX=0;
		$iMaxY=0;
		
		$iMinX='';
		$iMinY='';
		
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
		
		$this->startScript();
		
		$iHeight=$this->iHeight-40;
		$iWidth=$this->iWidth-200;
		
		$deltaX=$iMaxX-$iMinX;
		$deltaY=$iMaxY-$iMinY;
	
		foreach($this->tData as $sGroup => $tDetail){
			$lastX=null;
			$lastY=null;
			foreach($tDetail['tPoint'] as $j => $tPoint){
				
				list($x,$y)=$tPoint;
				
				$x2=(($x-$iMinX)/($iMaxX-$iMinX))*$iWidth;
				$y2=(1-$y/$iMaxY)*$iHeight;
				
				$x3=$x2-3;
				$y3=$y2-3;
				
				if($x3<=0){
					$x3=0;
				}
				if($y3<=0){
					$y3=0;
				}
				
				$this->rect($x3,$y3,6,6,$tDetail['color']);
				
				if($j>0){
					$this->lineFromTo($lastX,$lastY,$x2,$y2,$tDetail['color']);
					
				}
				
				$lastX=$x2;
				$lastY=$y2;
				
			}
		}
		
		$i=0;
		foreach($this->tData as $sGroup => $tDetail){
			$sLabel=$sGroup;
			
			$x=200;
			$y=$i*20+50;
			
			$this->rect($x,$y-8,10,10,$tDetail['color']);
			$this->text($x+16,$y,$sLabel);
			
			$i++;
		}
		
		$this->text(0,10,$iMaxY);
		$this->text(0,(1-$iMinY/$iMaxY)*$iHeight,$iMinY);
		
		$this->lineFromTo(0,0,0,$this->iHeight-10);
		$this->lineFromTo(0,$this->iHeight-10,$this->iWidth-200,$this->iHeight-10);
		
		$this->text(0,$this->iHeight,$iMinX);
		
		$this->text($this->iWidth-200,$this->iHeight,$iMaxX);
		
		$this->endScript();
		
		return $this->sHtml;
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
