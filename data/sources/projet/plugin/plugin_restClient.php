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
* plugin_auth classe pour gerer l'authentification
* @author Mika
* @link http://mkf.mkdevs.com/
*/
class plugin_restClient{

	private $sUrl=null;
	private $sFormatToSend=null;
	private $sFormatToReceive=null;

	const JSON_CONTENT_TYPE='Content-Type: application/json';
	const XML_CONTENT_TYPE='Content-Type: application/xml';
	const HTML_CONTENT_TYPE='Content-Type: text/html';

	const JSON='json';
	const XML='xml';
	const URL='url';
	const HTML='html';

	private $tParam=array();
	private $tFormat=array();

	private $sParamXml=null;
	private $sParamXmlRoot='params';
        
	private $sParamJson=null;
	private $sParamUrl=null;

	private $sResponse=null;

	public function __construct($sUrl=null){
		$this->sUrl=$sUrl;

		$this->tFormat=array(
				self::JSON => array(
						'contentType' => self::JSON_CONTENT_TYPE
				),
				self::XML => array(
						'contentType' => self::XML_CONTENT_TYPE
				),
				self::URL => array(
						'contentType' => self::HTML_CONTENT_TYPE
				),
		);
	}

	public function useJson(){
		$this->sFormatToSend=self::JSON;
		$this->sFormatToReceive=self::JSON;
	}
	public function useXml(){
		$this->sFormatToSend=self::XML;
		$this->sFormatToReceive=self::XML;
	}
	public function useUrl(){
		$this->sFormatToSend=self::URL;
		$this->sFormatToReceive=self::HTML;
	}

	public function useJsonResponse(){
		$this->sFormatToReceive=self::JSON;
	}
	public function useXmlResponse(){
		$this->sFormatToReceive=self::XML;
	}
	public function useHtmlResponse(){
		$this->sFormatToReceive=self::HTML;
	}

	public function setParamArray($tParam){
		$this->tParam=$tParam;
	}
	public function setParam($var,$value){
		$this->tParam[$var]=$value;
	}
	public function setParamXml($sXml){
		$this->sParamXml=$sXml;
	}
	public function setParamJson($sJson){
		$this->sParamJson=$sJson;
	}
	public function setParamXmlRoot($sRoot='params'){
		$this->sParamXmlRoot=$sRoot;
	}

	private function getContentType(){
		return $this->tFormat[$this->sFormatToSend]['contentType'];
	}

	public function send(){

            
		if($this->sFormatToSend==null){
			throw new Exception('Veuillez choisir json/xml pour votre client Rest (utiliser enableJson,enableXml)');
		}

		if($this->sFormatToSend==self::JSON){
			if($this->sParamJson!=null){
				$sData=$this->sParamJson;
			}else{
				$oParam=new stdclass();
				foreach($this->tParam as $sVar => $sVal){
					$oParam->$sVar=$sVal;
				}

				$sData=json_encode($oParam);
			}
		}else if($this->sFormatToSend==self::XML){
			if($this->sParamXml!=null){
				$sData=$this->sParamXml;
			}else{
				$oParam=new simplexml_load_string('<'.$this->sParamXmlRoot.'></'.$this->sParamXmlRoot.'>');
				foreach($this->tParam as $sVar => $sVal){
					$oParam->addChild($sVar,$sVal);
				}

				$sData=$oParam->asXml();
			}
		}else if($this->sFormatToSend==self::URL){
			if($this->sParamUrl!=null){
				$sData=$this->sParamUrl;
			}else{
				$sData=http_build_query ($this->tParam);
			}
		}else{
			throw new Exception('Format to send unknow ');
		}

		$oCurl = curl_init();
		curl_setopt($oCurl, CURLOPT_URL, $this->sUrl);
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, array($this->getContentType() ));
		curl_setopt($oCurl, CURLOPT_POST, 1);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sData);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
		$sResponseText = curl_exec($oCurl);

		$http_status = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);

		curl_close($oCurl);

		$this->sResponse=$sResponseText;

		if($http_status=='200'){
			return true;
		}else{
			return false;
		}
	}
	public function getResponseText(){
		return $this->sResponse;
	}
	public function getResponse(){
		if($this->sFormatToReceive==self::JSON){
			$response = json_decode($this->sResponse);
		}else if($this->sFormatToReceive==self::XML){
			$response = simplexml_load_string($this->sResponse);
		}else{
			$response =$this->sResponse;
		}
		return $response;
	}

}