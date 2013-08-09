<?php
Class module_examplemenu extends abstract_moduleembedded{
		
	public function _index(){
		
		$tLink=array(
			//TABLEAUICI
		);
		
		$oView=new _view('examplemenu::index');
		$oView->tLink=$tLink;
		
		return $oView;
	}
}
