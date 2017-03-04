<?php
class business_abstract {

	protected $oReturn;

	public function sendReturn($bStatus, $tData = null) {
		$this->oReturn=new business_return($bStatus, $tData);
		return $bStatus;
	}
	public function getReturn(){
		return $this->oReturn;
	}

	public function __get($name) {
		throw new Exception('property ' . $name . ' unknown');
	}

}

class business_return {

	private $bStatus = null;
	private $tData = null;

	public function __construct($bStatus, $tData) {
		$this->bStatus = $bStatus;
		$this->tData = $tData;
	}

	public function getStatus() {
		return $this->bStatus;
	}

	public function getData($sVar, $default = null) {
		if (isset($this->tData[$sVar])) {
			return $this->tData[$sVar];
		} else {
			return $default;
		}
	}

	public function getAllData() {
		return $this->tData;
	}

	public function setData($sVar, $uValue) {
		return $this->tData[$sVar] = $uValue;
	}

}
