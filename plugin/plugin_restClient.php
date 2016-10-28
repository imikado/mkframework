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
* plugin_restClient classe pour gerer les appels REST
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

	const HTTP_PUT='PUT';
	const HTTP_POST='POST';
	const HTTP_GET='GET';
	const HTTP_PATCH='PATCH';
	const HTTP_DELETE='DELETE';

	private $tParam=array();
	private $tFormat=array();

	private $sParamXml=null;
	private $sParamXmlRoot='params';
        
	private $sParamJson=null;
	private $sParamUrl=null;

	private $sResponse=null;

	private $httpVerb=null;
	private $httpPort=null;

	/** 
	* constructeur
	* @access public
	* @param string $sUrl url du webservice Rest/RestFull
	*/
	public function __construct($sUrl=null,$iPort=80){
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

		$this->useHttpPost();
		$this->setPort($iPort);
	}

	public function setPort($iPort){
		$this->httpPort=$iPort;
	}

	/** 
	* choisis HTTP GET
	* @access public
	*/
	public function useHttpGet(){
		$this->httpVerb=self::HTTP_GET;
	}
	/** 
	* choisis HTTP POST
	* @access public
	*/
	public function useHttpPost(){
		$this->httpVerb=self::HTTP_POST;
	}
	/** 
	* choisis HTTP PUT
	* @access public
	*/
	public function useHttpPut(){
		$this->httpVerb=self::HTTP_PUT;
	}
	/** 
	* choisis HTTP PATCH
	* @access public
	*/
	public function useHttpPatch(){
		$this->httpVerb=self::HTTP_PATCH;
	}
	/** 
	* choisis HTTP DELETE
	* @access public
	*/
	public function useHttpDelete(){
		$this->httpVerb=self::HTTP_DELETE;
	}

	/** 
	* Choisi le format JSON en envoi et en reponse
	* @access public
	*/
	public function useJson(){
		$this->sFormatToSend=self::JSON;
		$this->sFormatToReceive=self::JSON;
	}
	/** 
	* Choisi le format XML en envoi et en reponse
	* @access public
	*/
	public function useXml(){
		$this->sFormatToSend=self::XML;
		$this->sFormatToReceive=self::XML;
	}
	/** 
	* Choisi le format URL en envoi et HTML en reponse
	* @access public
	*/
	public function useUrl(){
		$this->sFormatToSend=self::URL;
		$this->sFormatToReceive=self::HTML;
	}

	/** 
	* Choisi le format JSON en reponse
	* @access public
	*/
	public function useJsonResponse(){
		$this->sFormatToReceive=self::JSON;
	}
	/** 
	* Choisi le format XML en reponse
	* @access public
	*/
	public function useXmlResponse(){
		$this->sFormatToReceive=self::XML;
	}
	/** 
	* Choisi le format HTML en reponse
	* @access public
	*/
	public function useHtmlResponse(){
		$this->sFormatToReceive=self::HTML;
	}

	/** 
	* indique le tableau de parametre
	* @access public
	* @param Array tableau de parametres
	*/
	public function setParamArray($tParam){
		$this->tParam=$tParam;
	}
	/** 
	* indique un parametre (a envoyer)
	* @access public
	* @param string variable
	* @param mix valeur
	*/
	public function setParam($var,$value){
		$this->tParam[$var]=$value;
	}
	/** 
	* indique un XML de parametres (dans le cas d'un appel complexe)
	* @access public
	* @param string xml a envoyer
	*/
	public function setParamXml($sXml){
		$this->sParamXml=$sXml;
	}
	/** 
	* indique un JSON de parametres (dans le cas d'un appel complexe)
	* @access public
	* @param string JSON a envoyer
	*/
	public function setParamJson($sJson){
		$this->sParamJson=$sJson;
	}
	/** 
	* indique la balise XML racine (dans le cas d'un appel XML)
	* @access public
	* @param string xml a envoyer
	*/
	public function setParamXmlRoot($sRoot='params'){
		$this->sParamXmlRoot=$sRoot;
	}

	private function getContentType(){
		return $this->tFormat[$this->sFormatToSend]['contentType'];
	}

	/** 
	* envoi la requete 
	* @access public
	* @return boolean retourne vrai/faux selon le retour (different de 200)
	*/
	public function send(){

            
		if($this->sFormatToSend==null){
			throw new Exception('Veuillez choisir json/xml/url pour votre client Rest (utiliser enableJson,enableXml,enableUrl)');
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
				$sData='?'.http_build_query ($this->tParam);
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

		curl_setopt($oCurl, CURLOPT_HEADER, false);

		curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, $this->httpVerb);

		curl_setopt($oCurl, CURLOPT_PORT, $this->httpPort);

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
	/** 
	* recupere la reponse
	* @access public
	* @return string reponse du serveur
	*/
	public function getResponseText(){
		return $this->sResponse;
	}
	/** 
	* recupere la reponse
	* @access public
	* @return mix reponse object du serveur
	*/
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