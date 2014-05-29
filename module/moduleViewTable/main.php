<?php
class module_moduleViewTable{
	
	private function analyze($oTpl){
		$error=null;
		$msg=null;
		$detail=null;
		
	    module_builder::getTools()->rootAddConf('conf/connexion.ini.php');
		
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/model/');
		$tFile=array();
		$tRowMethodes=array();
		foreach($oDir->getListFile() as $oFile){
			if(preg_match('/.sample.php/',$oFile->getName()) or !preg_match('/.php$/',$oFile->getName())) continue;
			$tFile[]=$oFile->getName();
			require_once( $oFile->getAdresse() );
			$sClassFoo=substr($oFile->getName(),0,-4);
			$oModelFoo=new $sClassFoo;
			
			if( method_exists( $oModelFoo, 'getSelect')){
				$tRowMethodes[substr($oFile->getName(),0,-4)]=substr($oFile->getName(),0,-4).'::getSelect()';
			}
		}
		
		if(_root::getParam('model')!=''){
			$sClass=substr(_root::getParam('model'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');
			
			$tBlackListMethod=get_class_methods('abstract_model');
			$tBlackListMethod[]='getInstance';
			
			$tMethod=array();
			$tMethod0=get_class_methods($sClass);
			foreach($tMethod0 as $sMethod){
				if(!in_array($sMethod,$tBlackListMethod)){
					$tMethod[]=$sMethod;
				}
			}
			$oTpl->tMethod=$tMethod;
		}
		
		
		if(_root::getParam('model') !='' and _root::getParam('method')!='' ){
		
			$tModule=module_builder::getTools()->getListModule();
		
			$sClass=substr(_root::getParam('model'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');
			
			$sMethod=_root::getParam('method');
			
			module_builder::getTools()->loadConfig($sClass);
			
			$oModel=new $sClass;
			$tData=array();
			try{
				$tData=$oModel->$sMethod();
				
				if(!$tData){
					$error='Votre methode '.$sMethod.' ne retourne pas d\'enregistrement.<br />';
					$error.='Ce generateur necessite d\'avoir des donnees pour construire le tableau';
				}
				
			}catch(Exception $e){
				$error='La methode '.$sMethod.' n\'est pas compatible avec ce generateur';
			}
			
			$tColumn=array();
			foreach($tData as $oRow){
				if(is_subclass_of($oRow,'abstract_row')){
					$tColumn0=$oRow->getTab();
					
				}else{
					$tColumn0=get_object_vars($oRow);
				}
				$tColumn=array_keys($tColumn0);
				break;
			}
			 


			 
			$oTpl->sClass=$sClass;
			$oTpl->tModule=$tModule;
			
			$tId=module_builder::getTools()->getIdTabFromClass($sClass);
			foreach($tColumn as $i => $sColumn){
				if(in_array($sColumn, $tId) ){
					unset($tColumn[$i]);
				}
			}
			
			$oTpl->tColumn=$tColumn;
			
			$oTpl->tRowMethodes=$tRowMethodes;

			$oModel=new $sClass;
			$oTpl->sModuleToCreate=$oModel->getTable();
			
			
			
		}
		
		
		
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		$oTpl->error=$error;
		
		return $oTpl;
	}
	
	public function _simple(){
		list($msg,$detail)=$this->processSimple();
		
		$oTpl= new _Tpl('moduleViewTable::simple');
		$oTpl->msg=$msg;
		
		$oTpl->error=null;
		$oTpl=$this->analyze($oTpl);
		$oTpl->detail=$detail;
		
		return $oTpl;
	
	}
	
	private function processSimple(){
		
		if(!_root::getRequest()->isPost()){
			return array(null,null);
		}
		
		if(_root::getParam('model') =='' or _root::getParam('method')=='' ){
			return array(null,null);
		}
		
		if(!file_exists('data/genere/'._root::getParam('id').'/module/table')){
			//copie module table + images
			$this->importModuleTable(); 
		}
		
		$sModule=_root::getParam('module');
		$sView=_root::getParam('view');
		
		$tableClass=_root::getParam('tableClass');
		
		$enableAlt=_root::getParam('enableAlt');
		
		$sModel=_root::getParam('model');
		$sMethod=_root::getParam('method');
		
		$arrayAlt=_root::getParam('arrayAlt');
		
		
		$tColumn=_root::getParam('tColumn');
		$tLabel=_root::getParam('tLabel');
		$tType=_root::getParam('tType');
		$tEnable=_root::getParam('tEnable');

		foreach($tColumn as $i => $sColumn){
			if(!in_array($sColumn, $tEnable) ){
				unset($tColumn[$i]);
				unset($tLabel[$i]);
			}
		}
		
		$ret="\n";
		$t="\t";
		
		$sViewContent='<?php '.$ret;
		$sViewContent.='$oTable = new module_table(\'simple\');'.$ret;
		$sViewContent.='$oTable->setClass(\''.$tableClass.'\');'.$ret;
		
		if(!$enableAlt){ 
			$sViewContent.='/*'; 
		}
			
		$sViewContent.='$oTable->setCycleClass( '.var_export($arrayAlt,true ).');'.$ret;
		
		if(!$enableAlt){ 
			$sViewContent.='*/'; 
		}
		
		
		$sViewContent.=$ret;
		$sViewContent.='$oTable->setHeader('.var_export($tLabel,true).');'.$ret;
		$sViewContent.=$ret;
		$sViewContent.='if($this->tData){'.$ret;
		$sViewContent.=$t.'foreach($this->tData as $oData){'.$ret;
		
		
		$sViewContent.=$t.$t.'$oTable->addLine(array('.$ret;
		
			foreach($tColumn as $sColumn){
				$sViewContent.=$t.$t.$t.'$oData->'.$sColumn.','.$ret;
			}
		
		$sViewContent.=$t.$t.'));'.$ret;
		
		$sViewContent.=$t.'}'.$ret;
		$sViewContent.='}'.$ret;
		$sViewContent.=$ret;
		$sViewContent.='echo $oTable->build()->show();';
		
		$oFile=new _file('data/genere/'._root::getParam('id').'/module/'.$sModule.'/view/'.$sView.'.php');
		if($oFile->exist()){
			return array('Vue deja existante',null);
		}
		
		$oFile->setContent($sViewContent);
		$oFile->save();
		$oFile->chmod(0666);
		
		$sModel=str_replace('.php','',$sModel);
		
		$msg='Vue '.$sView.'.php'.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
		$detail='';
		$detail.='Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sView.'.php ';
		
		$sCode='<?php '."\n";
		$sCode.='//recupere les enregistrements'."\n";
		$sCode.='$tData='.$sModel.'::getInstance()->'.$sMethod.'();'.$ret;
		
		$sCode.='//recupere la vue du module'."\n";
		$sCode.='$oView=new _view(\''.$sModule.'::'.$sView.'\');'."\n";
		$sCode.='$oView->tData=$tData;'.$ret;
		$sCode.="\n";
		$sCode.='//assigner la vue retournee a votre layout'."\n";
		$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
		
		$detail.='<br/><br/>Pour l\'utiliser, indiquez dans votre module:<br />
		'.highlight_string($sCode,1);
		
		
		return array($msg,$detail);
		
	}
	
	
	/*---------------------------------------
	COMPLEX WITH ORDER
	---------------------------------------*/
	public function _complexWithOrder(){
		list($msg,$detail)=$this->processComplexWithOrder();
		
		$oTpl= new _Tpl('moduleViewTable::complexWithOrder');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl=$this->analyze($oTpl);
		
		return $oTpl;
	
	}
	
	private function processComplexWithOrder(){
		
		if(!_root::getRequest()->isPost()){
			return array(null,null);
		}
		
		if(_root::getParam('model') =='' or _root::getParam('method')=='' ){
			return array(null,null);
		}
		
		if(!file_exists('data/genere/'._root::getParam('id').'/module/table')){
			//copie module table + images
			$this->importModuleTable();
		
		}
		
		$sModule=_root::getParam('module');
		$sView=_root::getParam('view');
		
		$tableClass=_root::getParam('tableClass');
		
		$enableAlt=_root::getParam('enableAlt');
		
		$sModel=_root::getParam('model');
		$sMethod=_root::getParam('method');
		
		$arrayAlt=_root::getParam('arrayAlt');
		
		
		$tColumn=_root::getParam('tColumn');
		$tLabel=_root::getParam('tLabel');
		$tType=_root::getParam('tType');
		$tEnable=_root::getParam('tEnable');
		
		$tOrderEnable=_root::getParam('tOrderEnable');
		
		$defaultOrder=_root::getParam('tOrderDefault');

		foreach($tColumn as $i => $sColumn){
			if(!in_array($sColumn, $tEnable) ){
				unset($tColumn[$i]);
				unset($tLabel[$i]);
			}
		}
		
		$ret="\n";
		$t="\t";
		
		$sViewContent='<?php '.$ret;
		$sViewContent.='$oTable = new module_table(\'complex1\');'.$ret;
		$sViewContent.='$oTable->setClass(\''.$tableClass.'\');'.$ret;
		
		if(!$enableAlt){ 
			$sViewContent.='/*'; 
		}
			
		$sViewContent.='$oTable->setCycleClass( '.var_export($arrayAlt,true ).');'.$ret;
		
		if(!$enableAlt){ 
			$sViewContent.='*/'; 
		}
		
		
		//header
		$sViewContent.=$ret;
		foreach($tLabel as $i => $sLabel){
			if(in_array($tColumn[$i],$tOrderEnable)){
				$sViewContent.='$oTable->addHeaderWithOrder(\''.$sLabel.'\',\''.$tColumn[$i].'\');'.$ret;
			}else{
				$sViewContent.='$oTable->addHeader(\''.$sLabel.'\');'.$ret;
			}
		}
		
		$sViewContent.=$ret;
		$sViewContent.='if($this->tData){'.$ret;
		$sViewContent.=$t.'foreach($this->tData as $oData){'.$ret;
		
		
		$sViewContent.=$t.$t.'$oTable->addLine(array('.$ret;
		
			foreach($tColumn as $sColumn){
				$sViewContent.=$t.$t.$t.'$oData->'.$sColumn.','.$ret;
			}
		
		$sViewContent.=$t.$t.'));'.$ret;
		
		$sViewContent.=$t.'}'.$ret;
		$sViewContent.='}'.$ret;
		$sViewContent.=$ret;
		$sViewContent.='echo $oTable->build()->show();';
		
		$oFile=new _file('data/genere/'._root::getParam('id').'/module/'.$sModule.'/view/'.$sView.'.php');
		if($oFile->exist()){
			//return array('Vue deja existante',null);
		}
		
		$oFile->setContent($sViewContent);
		$oFile->save();
		$oFile->chmod(0666);
		
		$msg='Vue '.$sView.'.php'.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
		$detail='';
		$detail.='Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sView.'.php ';
		
		$sCode='<?php '."\n";
		$sCode.='//recupere les enregistrements avec en parametre le champ de tri + le sens'."\n";
		$sCode.='$tData=new '.$sModel.'::getInstance()->'.$sMethod.'(module_table::getParam(\'order\',\''.$defaultOrder.'\'),module_table::getParam(\'side\',\'ASC\'));'.$ret;
		
		$sCode.='//recupere la vue du module'."\n";
		$sCode.='$oView=new _view(\''.$sModule.'::'.$sView.'\');'."\n";
		$sCode.='$oView->tData=$tData;'.$ret;
		$sCode.="\n";
		$sCode.='//assigner la vue retournee a votre layout'."\n";
		$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
		
		$detail.='<br/><br/>Pour l\'utiliser, indiquez dans votre module:<br />
		'.highlight_string($sCode,1);
		
		
		return array($msg,$detail);
		
	}
	
	public function complexWithOrderAndClic(){
		list($msg,$detail)=$this->processComplexWithOrderAndClic();
		
		$oTpl= new _Tpl('moduleViewTable::complexWithOrder');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl=$this->analyze($oTpl);
		
		return $oTpl;
	}
	
	private function processComplexWithOrderAndClic(){
		
	}
	
	private function importModuleTable(){
		model_mkfbuilderprojet::getInstance()->copyFromTo('data/sources/fichiers/module/table','data/genere/'._root::getParam('id').'/module/table');
		$tImg=array(
			'flecheDownOff.png',
			'flecheDownOn.png',
			'flecheUpOff.png',
			'flecheUpOn.png',
		);
		foreach($tImg as $sImg){
			model_mkfbuilderprojet::getInstance()->copyFromTo('data/sources/fichiers/module/table/css/images/'.$sImg,'data/genere/'._root::getParam('id').'/public/css/images/'.$sImg);
		}
	}
	
}
/*
<?php
//simple
$oTable = new module_table('simple');
$oTable->setClass('tb_list');
$oTable->setCycleClass(array(null,'alt'));

$oTable->setHeader(array(
	'Titre',
	'Auteur',
	'Priority',
	null
));

if($this->tArticle){
	foreach($this->tArticle as $oArticle){
		
		$sAuteur=null; 
		if(isset($this->tJoinAuteur[ $oArticle->auteur_id])){ 
			$sAuteur= $this->tJoinAuteur[ $oArticle->auteur_id]; 
		}
		
		$oTable->addLine(array(
			$oArticle->titre,
			$sAuteur,
			$oArticle->priority,
			'<a href="'.$this->getLink('article::edit',array(
													'id'=>$oArticle->getId()
												) 
										).'">Edit</a>
			|
			<a href="'.$this->getLink('article::show',array(
													'id'=>$oArticle->getId()
												) 
										).'">Show</a>
			|
			<a href="'.$this->getLink('article::delete',array(
													'id'=>$oArticle->getId()
												) 
										).'">Delete</a>'
		
		));
	}
}
echo $oTable->build()->show();
*/
