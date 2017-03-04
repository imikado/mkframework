<?php class _unitTest{

	public static function getForm(){
		return new _unitTestForm();
	}

	public static function getRequest(){
		return new _unitTestRequest();
	}

}
class _unitTestRequest{
	public function setGet($tGet){
		_root::addRequest($tGet);
		_root::loadRequest();
	}
}
class _unitTestForm{

	protected $tPost=null;
	protected $tGet=null;

	protected $sRequestMethod='GET';

	protected $bToken=false;
	protected $sTokenName='token';
	
	public function setPost($tPost){
		$this->tPost=$tPost;
		$this->sRequestMethod='POST';
	}
	public function setGet($tGet){
		$this->tGet=$tGet;
	}
	public function enableToken($sTokenName='token'){
		$this->bToken=true;
		$this->sTokenName=$sTokenName;
	}


	public function send(){
		$_SERVER['REQUEST_METHOD']=$this->sRequestMethod;
		if($this->bToken){
			$oPluginXsrf=new plugin_xsrf();
			$sToken=$oPluginXsrf->getToken();
			$this->tPost[$this->sTokenName]=$sToken;
		}
		
		_root::addRequest($this->tGet);
		_root::addRequest($this->tPost);
		_root::loadRequest();

    }
}