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

	public function __construct($iWidth=null,$iHeight=null,$tData=null){
		$this->iWidth=$iWidth;
		$this->iHeight=$iHeight;
		$this->tData=$tData;
	}
	
	/**
	* charge les donnees du graphique
	* @access public
	* @param array $tData: tableau de donnees
	*/
	public function setData($tData){
		$this->tData=$tData;
	}
	
	private function setType($sType){
		if(!in_array($sType,array(self::$PIE,self::$HISTO,self::$LINES))){
			return false;
		}
		$this->sType=$sType;
	}
	/**
	* indique de choisir le type histogramme
	* @access public
	*/
	public function chooseHisto(){
		$this->setType(self::$HISTO);
	}
	/**
	* indique de choisir le type camembert
	* @access public
	*/
	public function choosePie(){
		$this->setType(self::$PIE);
	}
	/**
	* indique de choisir le type lignes
	* @access public
	*/
	public function chooseLines(){
		$this->setType(self::$LINES);
	}
	/**
	* retourne le code du graphique a afficher
	* @access public
	* @return string retourne le code du graphique
	*/
	public function show(){
		if($this->sType==self::$PIE){
			$oPie=new plugin_chartPie($this->iWidth,$this->iHeight,$this->tData);
			return $oPie->show();
		}elseif($this->sType==self::$HISTO){
			$oPie=new plugin_chartHisto($this->iWidth,$this->iHeight,$this->tData);
			return $oPie->show();
		}elseif($this->sType==self::$LINES){
			$oPie=new plugin_chartLine($this->iWidth,$this->iHeight,$this->tData);
			return $oPie->show();
		}
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
	
	public function __construct($iWidth=null,$iHeight=null,$tData=null){
		$this->iWidth=$iWidth;
		$this->iHeight=$iHeight;
		$this->tData=$tData;
		
		$this->tColor=array(
				'green',
				'blue',
				'red',
		);
		
		self::$uid+=1;
		
		$this->id='canvasPluginChart'.self::$uid;
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
	
	public function show(){
		$this->loadCanvas();
		
		foreach($this->tData as $tLine){
			foreach($tLine as $j => $iValue){
			
				if($j==0){
					continue;
				}
			
				if($iValue > $this->iMax){
					$this->iMax=$iValue;
				}
			}
		}
		$iWidthBar=($this->iWidth-200)/(count($this->tData[0])-1);
		$iWidthBar=$iWidthBar*0.8;
		
		$iWidthSpace=$iWidthBar*0.2;
		
		$this->startScript();
		
		
		
		$j=0;
		foreach($this->tData as $i=> $tLine){
			
			foreach($tLine as $j => $iValue){
				
				$j2=$j-1;
				
				if($j==0){
					$lastX=null;
					$lastY=null;
					continue;
				}
				 
				$iHeight=1-(($iValue/$this->iMax)*($this->iHeight));
				
				$x=$j2*($iWidthBar+3);
				$y=$this->iHeight+$iHeight;
				
				$this->rect($x,$y,6,6,$this->tColor[$i]);
				
				if($j>1){
					$this->lineFromTo($lastX,$lastY,$x,$y,$this->tColor[$i]);
					plugin_debug::addSpy('line '.$i, 'line '.$lastX.' '.$lastY.' '.$x.' '.$y.' ');
				}
				
				$lastX=$x;
				$lastY=$y;
				
			}
		}
		
		foreach($this->tData as $i => $tLine){
			$sLabel=$tLine[0];
			
			$x=200;
			$y=$i*20+50;
			
			$this->rect($x,$y-8,10,10,$this->tColor[$i]);
			$this->text($x+16,$y,$sLabel);
			
		}
		
		
		$this->lineFromTo(0,0,0,$this->iHeight);
		$this->lineFromTo(0,$this->iHeight,$this->iWidth-200,$this->iHeight);
		
		$this->endScript();
		
		return $this->sHtml;
	}
}
