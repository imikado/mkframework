<?php
class module_mods_all_testunit extends abstract_moduleBuilder{

	protected $sModule='mods_all_testunit';
	protected $sModuleView='mods/all/testunit';

	const FORM = 'form';
	const DISPLAY='display';
	const DISPLAYFROMBDD='displayfrombdd';
	
	const NONE = 'none';

	private $msg=null;
	private $detail=null;
	private $tError=null;

	private $tBlackListMethod=null;
	
	private function installBootstrap(){
		/*SOURCE*/
		$oSourceBootstrap=$this->getObjectSource('bootstrap.php');
		if($oSourceBootstrap->exist()==false){
		$oSourceBootstrap->setPattern(null,null);
		$oSourceBootstrap->save();
		}
		/*SOURCE*/
	}

	private function getViewFull($sView){
		$oTpl= $this->getView($sView);
		$oTpl->tModelFile=$this->getListModel();
		$oTpl->tModuleFile=$this->getListModule();

		$oTpl->msg=$this->msg;
		$oTpl->detail=$this->detail;
		$oTpl->tError=$this->tError;

		return $oTpl;
	}
	
	public function _index(){

		$sAction=_root::getParam('saction');
		if(in_array($sAction,array('model','module','launch'))){
			$oTpl=$this->$sAction();
		}else{
			$oTpl= $this->getViewFull('index');
		}

		return $oTpl;
	}
	public function model(){
		$this->processModel();

		$sFilename=_root::getParam('file');
		$sClass=substr($sFilename,0,-4);

		require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sFilename);

		$tBlackListMethod=get_class_methods('abstract_model');
		$tBlackListMethod[]='getInstance';
		
		$tMethod=array();
		$tMethod0=get_class_methods($sClass);
		foreach($tMethod0 as $sMethod){
			if(!in_array($sMethod,$tBlackListMethod)){
				$tMethod[]=$sMethod;
			}
		}

		$oTpl= $this->getViewFull('model');
		$oTpl->tMethod=$tMethod;


		return $oTpl;
	}
	private function processModel(){
		if(_root::getRequest()->isPost()==false){
			return null;
		}


		$this->installBootstrap();


		$sFilename=_root::getParam('file');
		$sClass=substr($sFilename,0,-4);
		$sRow=str_replace('model_', 'row_', $sClass);


		$this->msg=tr('testUnitGeneres');
		$tDetail=array();
		$tDetail[]=trR('CreationDuFichierVAR',array('#FICHIER#'=>'tests/bootstrap.php'));

		$tDetail[]=trR('CreationDuFichierVAR',array('#FICHIER#'=>'tests/'.$sClass.'Test.php'));
		

		$tMethod=array();
		if(_root::getParam('tMethod')){
			$tMethod=_root::getParam('tMethod');
		}

		/*SOURCE*/$oSourceModel=$this->getObjectSource('model.php');
		/*SOURCE*/$oSourceModel->setPattern('#model_exampleTest#',$sClass.'Test');

		$sCode=null;
		foreach($tMethod as $sMethod){
			$sCode.=$oSourceModel->getSnippet(
							'testMethod',
							array(
								'#testMethod#'=>'test'.ucfirst($sMethod),
								'#method#'=>$sMethod,

								'#model_example#'=>$sClass,
								'#row_example#'=>$sRow,
							) );

		}

		/*SOURCE*/$oSourceModel->setPattern('#code#',$sCode);
		/*SOURCE*/$oSourceModel->save();

		$this->detail=implode('<br/>',$tDetail);


	}

	public function module(){
		$this->processModule();	

		$sModuleFilename=_root::getParam('file');
		$sModuleName=$sModuleFilename;

		include module_builder::getTools()->getRootWebsite().'module/'.$sModuleFilename.'/main.php';

		$tMethods=get_class_methods('module_'.$sModuleName);
		foreach($tMethods as $i => $sMethod){
			if($sMethod[0]!='_' or substr($sMethod,0,2)=='__'){ 
				unset($tMethods[$i]);
			}
		}

		//models
		$tModelMethod=array();
		$tFileModel=$this->getListModel();
		foreach($tFileModel as $sFile){
			$tModelMethod0=$this->getListMethodModel($sFile);
			$sClass=substr(basename($sFile),0,-4);

			foreach($tModelMethod0 as $sModelMethod){
				$tModelMethod[$sClass][]=$sModelMethod;	
			}
			
		}

		$oTpl= $this->getViewFull('module');
		$oTpl->tMethod=$tMethods;
		$oTpl->tModelMethod=$tModelMethod;
		$oTpl->bStillExist=false;

		if(_root::getParam('file') and _root::getParam('saction')){
			if(file_exists( module_builder::getTools()->getRootWebsite().'tests/module_'._root::getParam('file').'Test.php')){
				$oTpl->bStillExist=true;
				$oTpl->sPathStillExist='tests/module_'._root::getParam('file').'Test.php';
			}
		}


		return $oTpl;
	}
	private function processModule(){
		if(_root::getRequest()->isPost()==false){
			return null;
		}


		$this->installBootstrap();


		$this->msg=tr('testUnitGeneres');
		$tDetail=array();
		$tDetail[]=trR('CreationDuFichierVAR',array('#FICHIER#'=>'tests/bootstrap.php'));

		


		$sModuleFilename=_root::getParam('file');
		$sModuleName=$sModuleFilename;

		$tMethod=_root::getParam('tMethod');
		$tOption=_root::getParam('tOption');

		$sClass='module_'.$sModuleName;

		$tDetail[]=trR('CreationDuFichierVAR',array('#FICHIER#'=>'tests/'.$sClass.'Test.php'));
		/*SOURCE*/$oSourceModule=$this->getObjectSource('module.php');
		/*SOURCE*/$oSourceModule->setPattern('#module_defaultTest#',$sClass.'Test');
		/*SOURCE*/$oSourceModule->setPattern('#module_exampleTest#',$sClass.'Test');
		

		$sCode=null;
		foreach($tMethod as $i => $sMethod){
			if($tOption[$i]==self::FORM){

				/*SOURCE*/$sCode.=$oSourceModule->getSnippet(
							'testMethodForm',
							array(
								'#testMethod#'=>'test'.ucfirst($sMethod),
								'#method#'=>$sMethod,

								'#module_example#'=>$sClass,
								'#oModuleExample#'=>'oModule'.ucfirst($sModuleName),

								'#getLink#'=>$sModuleName.'::'.substr($sMethod,1)
							) );


			}else if($tOption[$i]==self::DISPLAYFROMBDD){
				/*SOURCE*/$sCode.=$oSourceModule->getSnippet(
							'testMethodDisplayFromBdd',
							array(
								'#testMethod#'=>'test'.ucfirst($sMethod),
								'#method#'=>$sMethod,

								'#module_example#'=>$sClass,
								'#oModuleExample#'=>'oModule'.ucfirst($sModuleName),
							) );
			}else if($tOption[$i]==self::DISPLAY){

				/*SOURCE*/$sCode.=$oSourceModule->getSnippet(
							'testMethodDisplay',
							array(
								'#testMethod#'=>'test'.ucfirst($sMethod),
								'#method#'=>$sMethod,

								'#module_example#'=>$sClass,
								'#oModuleExample#'=>'oModule'.ucfirst($sModuleName),
							) );
				
			}
		}

		/*SOURCE*/$oSourceModule->setPattern('#code#',$sCode);

		/*SOURCE*/$oSourceModule->save();

		$this->detail=implode('<br/>',$tDetail);

	}



	public function launch(){
	
		module_builder::setLayout('templateProjetLight');
		
		$sPhpUnit='phpunit';
		$tFile=scandir(module_builder::getTools()->getRootWebsite());
		if($tFile){
			foreach($tFile as $sFile){
				
				if(preg_match('/phpunit/i',$sFile)){
					$sPhpUnit=$sFile;
				}
			}
		}
	
		$this->processLaunch();
	
		$oView=$this->getViewFull('launch');
		$oView->phpunit=$sPhpUnit;
		
		return $oView;
	
	}
	private function processLaunch(){
		if(_root::getRequest()->isPost()==false){
			return null;
		}
		
		$cmd=null;
		if(_root::getParam('directory')=='local'){
			$cmd=module_builder::getTools()->getRootWebsite().'/';
		}
		
		$cmd.=_root::getParam('launcher');
		$cmd.=' '.module_builder::getTools()->getRootWebsite().'tests/ 2>&1';
		
		
		$sRetourRaw= shell_exec($cmd);
		$tRetour=explode("\n",$sRetourRaw);
		
		$sStatus=null;
		
		$bStartColorResult=false;
		
		$sRetour=null;
		foreach($tRetour as $i => $line){
		
			if($i == 2){
				$line=str_replace(array('F','.'),array('<span class="ko">F</span> ','<span class="ok">#</span> '),$line);
				
			}
		
			if(preg_match('/Expected/',$line)){
				$bStartColorResult=true;
			}
			
			if($bStartColorResult){
				if(substr($line,0,3)=='---'){
					$line='<span class="expected">--- Attendu</span>';
				}else if(substr($line,0,3)=='+++'){
					$line='<span class="actual">'.$line.'</span>';
				}
				
				if(substr($line,0,1)=='-'){
					$line='<span class="expected">'.$line.'</span>';
				}else if(substr($line,0,1)=='+'){
					$line='<span class="actual">'.$line.'</span>';
				}
			}
		
			if(preg_match('/OK/',$line)){
				$line=str_replace('OK','<span class="ok">OK</span>',$line);
				$sStatus='OK';
			}
			
			if(preg_match('/FAILURES/',$line)){
				$line=str_replace('FAILURES','<span class="ko">FAILURES</span>',$line);
				$sStatus='KO';
			}
			
		
			$sRetour.=$line."<br/>\n";
		}
		
		if($sStatus=='OK'){
			$sRetour.='<p><div style="padding:10px;background:green">OK</div></p>';
		}else{
			$sRetour.='<p><div style="padding:10px;background:darkred">KO</div></p>';
		}
		
		$this->detail=$sRetour;
		
	}



	private function process(){
		if(_root::getRequest()->isPost()==false){
			return null;
		}

		$this->msg=tr('testUnitGeneres');
		$this->detail=trR('CreationDuFichierVAR',array('#FICHIER#'=>'tests/bootstrap.php'));

		/*SOURCE*/$oSourceBootstrap=$this->getObjectSource('bootstrap.php');
		$oSourceBootstrap->setPattern(null,null);
		/*SOURCE*/$oSourceBootstrap->save();
		
		 
		//--module
		$tModule=_root::getParam('tFilenameModule');
		if($tModule){
			foreach($tModule as $sModuleFilename){

				include module_builder::getTools()->getRootWebsite().'module/'.$sModuleFilename.'/main.php';


				$tMethods=get_class_methods('module_'.$sModuleName);
				foreach($tMethods as $i => $sMethod){
					if($sMethod[0]!='_' or substr($sMethod,0,2)=='__'){ 
						unset($tMethods[$i]);
					}
				}
				if(empty($tMethods)){
					continue;
				}
				if(get_parent_class('module_'.$sModuleName)!='abstract_module'){
					continue;
				}

				/*SOURCE*/$oSourceModule=$this->getObjectSource('module.php');
				/*SOURCE*/$oSourceModule->setPattern('#model_exampleTest#',$sClass.'Test');
			

			}
		}

		
	}

	private function getListMethodModel($sFilename){

		$sClass=substr(basename($sFilename),0,-4);

		require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sFilename);

		if($this->tBlackListMethod==null){
			$tBlackListMethod=get_class_methods('abstract_model');
			$tBlackListMethod[]='getInstance';
			$this->tBlackListMethod=$tBlackListMethod;
		}
		
		$tMethod=array();
		$tMethod0=get_class_methods($sClass);
		foreach($tMethod0 as $sMethod){
			if(!in_array($sMethod,$this->tBlackListMethod)){
				$tMethod[]=$sMethod;
			}
		}

		return $tMethod;
	}

	private function getListModel(){

		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array();
		$tRowMethodes=array();
		foreach($oDir->getListFile() as $oFile){
			if(preg_match('/.sample.php/',$oFile->getName()) or !preg_match('/.php$/',$oFile->getName())) continue;
			$tFile[]=$oFile->getName();
			
		}

		return $tFile;
	}

	private function getListModule(){
		$tModuleFilename=array();

		$tModule=module_builder::getTools()->getListModule();
		$tModuleAndMethod=array();
		foreach($tModule as $oModule){
			$sModuleName=$oModule->getName();
			if(!file_exists(module_builder::getTools()->getRootWebsite().'module/'.$sModuleName.'/main.php')){
				continue;
			}
			if(in_array($sModuleName,array('menu','builder','example','exampleembedded'))){
				continue;
			}
			
			$tModuleFilename[]=$sModuleName;
		}
		return $tModuleFilename;
	}

}