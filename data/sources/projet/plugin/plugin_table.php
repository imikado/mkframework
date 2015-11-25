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
* plugin_table classe pour generer des tableau html
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_table{
	
	protected $sHtml=null;
	protected $ret="\n";
	protected $bTr=0;
	protected $tAltCycle;
	protected $tCountCycle;
	protected $tCycle;
	
	public function __construct($uOption=null){
		$sOption=$this->getOption($uOption);
		
		$this->sHtml.='<table'.$sOption.'>'.$this->ret;
	}
	
	public function setCycle($tCycle,$id=0){
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
	
	public function tr($uOption=null){
		$sOption=$this->getOption($uOption);
		
		if($this->bTr){
			$this->sHtml.='</tr>';
		}
		
		$this->sHtml.='<tr'.$sOption.'>';
		
		$this->bTr=1;
	}
	
	public function td($sValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		$this->sHtml.='<td '.$sOption.'>'.$sValue.'</td>';
	}
	public function tdList($tValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		foreach($tValue as $sValue){
			$this->sHtml.='<td '.$sOption.'>'.$sValue.'</td>';
		}
	}

	public function th($sValue=null,$uOption=null){
		$sOption=$this->getOption($uOption);
		
		$this->sHtml.='<th '.$sOption.'>'.$sValue.'</th>';
	}
	
	public function show(){
		if($this->bTr){
			$this->sHtml.='</tr>';
		}
		$this->sHtml.='</table>';
		
		return $this->sHtml;
	}
	
	private function getOption($uOption){
		if($uOption==null){ return null;}
		if(!is_array($uOption)){ return ' '.$uOption; }
		
		$sOption='';
		foreach($uOption as $sVar => $sVal){
			$sOption.=' '.$sVar.'="'.preg_replace("/'/",'\'',$sVal).'"';
		}
		return $sOption;
	}
	
}
