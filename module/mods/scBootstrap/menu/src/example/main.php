<?php
Class module_#MODULE# extends abstract_moduleembedded{
		
	public function _index(){
		
		$tLink=array(
			#TABLEAUICI#
		);
		
		$oView=new _view('#MODULE#::index');
		$oView->tLink=$tLink;
		
		return $oView;
	}
}
