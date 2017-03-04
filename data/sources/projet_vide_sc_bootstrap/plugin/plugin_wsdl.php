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
* plugin_wsdl classe pour generer le fichier wsdl d'un webservice
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_wsdl{
	
	protected $tMethod;
	protected $tmpMethod;
	protected $url;
	protected $sName='webservice';
	
	public function setName($sName){
		$this->sName=$sName;
	}
	
	public function setUrl($url){
		$this->url=$url;
	}
	
	public function addFunction($sName){
		$this->tmpMethod=$sName;
	}
	
	public function addParameter($sName,$sType){
		$this->tMethod[$this->tmpMethod]['param'][$sName]=$sType;
	}
	
	public function addReturn($sName,$sType){
		$this->tMethod[$this->tmpMethod]['return'][$sName]=$sType;
	}
	
	public function getWsdl(){
		$sWsdl='<definitions xmlns:tns="'.$this->url.'" targetNamespace="'.$this->url.'" ';
		$sWsdl.='xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" ';
		$sWsdl.='xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap-enc="http://schemas.xmlsoap.org/soap/encoding/" ';
		$sWsdl.='xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" name="'.$this->sName.'">';

			$sWsdl.='<types>';
				$sWsdl.='<xsd:schema targetNamespace="'.$this->url.'"/>';
			$sWsdl.='</types>';

			$sWsdl.='<portType name="'.$this->sName.'Port">';
				foreach($this->tMethod as $sMethod => $foo){
				$sWsdl.='<operation name="'.$sMethod.'">';
					$sWsdl.='<documentation>documenation</documentation>';
					$sWsdl.='<input message="tns:'.$sMethod.'In"/>';
					$sWsdl.='<output message="tns:'.$sMethod.'Out"/>';
				$sWsdl.='</operation>';
				}
			$sWsdl.='</portType>';


			$sWsdl.='<binding name="'.$this->sName.'Binding" type="tns:'.$this->sName.'Port">';
				$sWsdl.='<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>';
				foreach($this->tMethod as $sMethod => $foo){
				$sWsdl.='<operation name="'.$sMethod.'">';
					$sWsdl.='<soap:operation soapAction="'.$this->url.'#'.$sMethod.'"/>';
					$sWsdl.='<input>';
						$sWsdl.='<soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"  />';
					$sWsdl.='</input>';
					$sWsdl.='<output>';
						$sWsdl.='<soap:body use="encoded" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"  />';
					$sWsdl.='</output>';
				$sWsdl.='</operation>';
				}
			$sWsdl.='</binding>';


			$sWsdl.='<service name="'.$this->sName.'Service">';
				$sWsdl.='<port name="'.$this->sName.'Port" binding="tns:'.$this->sName.'Binding">';
					$sWsdl.='<soap:address location="'.$this->url.'"/>';
				$sWsdl.='</port>';
			$sWsdl.='</service>';

			foreach($this->tMethod as $sMethod => $tParam){
			$sWsdl.='<message name="'.$sMethod.'In">';
				foreach($tParam['param'] as $sParam => $sType){
					$sWsdl.='<part name="'.$sParam.'" type="xsd:'.$sType.'"/>';
				}
			$sWsdl.='</message>';
			}

			foreach($this->tMethod as $sMethod => $tParam){
			$sWsdl.='<message name="'.$sMethod.'Out">';
				foreach($tParam['return'] as $sParam => $sType){
					$sWsdl.='<part name="'.$sParam.'" type="xsd:'.$sType.'"/>';
				}
			$sWsdl.='</message>';
			}
 
				
		$sWsdl.='</definitions>';
		
		
		return $sWsdl;
	}
	
	public function show(){
		header ("Content-Type:text/xml");
		echo $this->getWsdl();
	}
	
}
