<?php
class module_mods_all_example extends abstract_moduleBuilder{

	protected $sModule='mods_all_example';
	protected $sModuleView='mods/all/example';

	private $msg=null;
	private $detail=null;
	private $tError=null;
	
	public function _index(){
		$tMessage=$this->process();

		$oTpl= $this->getView('index');
		//$oTpl->var=$var;
		
		$oTpl->msg=$this->msg;
		$oTpl->detail=$this->detail;
		$oTpl->tError=$this->tError;
		
		return $oTpl;
	}
	private function process(){
		if(_root::getRequest()->isPost()==false){
			return null;
		}

		$this->msg=tr('coucheModeleGenereAvecSucces');
		$this->detail=trR('CreationDuFichierVAR',array('#FICHIER#'=>'model/model_'.$sTable));

		$this->projectMkdir('module/'.$sModuleMenuName);

		/*SOURCE*/$oSourceModel=$this->getObjectSource('example.php');
		/*SOURCE*/$oSourceModel->setPattern('#maTable#',$maTable);

		$sSnippet=$oSourceModel->getSnippet(
							'monSnippet',
							array(
								'#maVar#'=>$maValeur)
							);

		/*SOURCE*/$oSourceModel->setPattern('#sSnippet#',$sSnippet);
		/*SOURCE*/$oSourceModel->save();
	}

}