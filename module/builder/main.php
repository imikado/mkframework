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
class module_builder extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function _index(){
		_root::redirect('builder::new');
	}
	
	public function getList(){
		$oProjetModel=new model_mkfbuilderprojet;
		$tProjet=$oProjetModel->findAll();
		
		sort($tProjet);//tri par ordre alphabetique
		
		$oTpl=new _tpl('builder::list');
		$oTpl->tProjet=$tProjet;
		
		return $oTpl;
	}
	
	public function _list(){
	
		$oTpl=$this->getList();
		
		$this->oLayout->add('main',$oTpl);
		
	}
	
	public function _new(){
		if(_root::getRequest()->isPost() ){
		
		    if(isset($_POST['withexamples']) and $_POST['withexamples']==1){
		        model_mkfbuilderprojet::getInstance()->create( _root::getParam('projet') );
		        $this->updateLayoutTitle( _root::getParam('projet') );
		    }else{
			    model_mkfbuilderprojet::getInstance()->createEmpty( _root::getParam('projet') );
			    $this->updateLayoutTitle( _root::getParam('projet') );
			}
			_root::redirect( 'builder::list' );
		}
	
		$oTpl=new _tpl('builder::new');
		$oTpl->iswritable=is_writable(_root::getConfigVar('path.generation'));

		$this->oLayout->add('main',$oTpl);

		
	}
	
	public function _edit(){
		
		$this->oLayout->setLayout('templateProjet');
		
		$oTplList=$this->getList();
		
		$this->oLayout->add('list',$oTplList);
		
		$this->oLayout->addModule('nav','menu::projet');
		
		$oTpl=new _tpl('builder::edit');
		$this->oLayout->add('main',$oTpl);
		
		if(_root::getParam('action')=='crud'){
			$this->oLayout->addModule('main','builder::crud');
		}elseif(_root::getParam('action')=='module'){
			$this->oLayout->addModule('main','builder::module');
		}elseif(_root::getParam('action')=='moduleembedded'){
			$this->oLayout->addModule('main','builder::moduleembedded');
		}elseif(_root::getParam('action')=='model'){
			$this->oLayout->addModule('main','builder::model');
		}elseif(_root::getParam('action')=='xml'){
			$this->oLayout->addModule('main','builder::xml');
		}elseif(_root::getParam('action')=='xmlindex'){
			$this->oLayout->addModule('main','builder::xmlindex');
		}elseif(_root::getParam('action')=='csv'){
			$this->oLayout->addModule('main','builder::csv');
		}elseif(_root::getParam('action')=='sqlite'){
			$this->oLayout->addModule('main','builder::sqlite');
		}elseif(_root::getParam('action')=='crudembedded'){
			$this->oLayout->addModule('main','builder::crudembedded');
		}elseif(_root::getParam('action')=='addmodule'){
			$this->oLayout->addModule('main','builder::addmodule');
		}elseif(_root::getParam('action')=='addmodulemenu'){
			$this->oLayout->addModule('main','builder::addmodulemenu');
		}
		
	}
	
	public function _model(){
		$this->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
		$tTables=array();
		$tTableColumn=array();
		
		if(_root::getParam('sConfig') != ''){
			$sConfig= _root::getParam('sConfig');
			$tTables=$this->getListTablesFromConfig( $sConfig );
			$tTableColumn=array();
			foreach($tTables as $sTable){
				$tTableColumn[$sTable]=$this->getListColumnFromConfigAndTable($sConfig,$sTable);
			}
		}
		
		if(_root::getRequest()->isPost()){
			$tEnable=_root::getParam('tEnable');
			$tTables=_root::getParam('tTable');
			$tPrimary=_root::getParam('tPrimary');

			$tSelectEnable=_root::getParam('tSelectEnable');
			$tSelectKey=_root::getParam('tSelectKey');
			$tSelectVal=_root::getParam('tSelectVal');

			foreach($tTables as $i => $sTable){
				if(!in_array($sTable,$tEnable)) continue;
				
				$tSelect=null;
				if(is_array($tSelectEnable) and in_array($sTable,$tSelectEnable)){
					$tSelect=array(
						'key' => $tSelectKey[$i],
						'val' => $tSelectVal[$i],
					);
				}

				$this->genModelAndRowByTableConfigAndId($sTable,$sConfig,$tPrimary[$i],$tSelect);
				$detail.='Creation du fichier model/model_'.$sTable.'.php<br />';
			}
			$msg='Couche mod&egrave;le g&eacute;n&eacute;r&eacute;e avec succ&egrave;s';
		}
		
		$oTpl= new _Tpl('builder::model');
		$oTpl->tTables=$tTables;
		$oTpl->tTableColumn=$tTableColumn;
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tConnexion=_root::getConfigVar('db');
		return $oTpl;
	}
	public function _crud(){
	    $this->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
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
		
		$oTpl= new _Tpl('builder::crud');
		
		if(_root::getParam('class') !='' ){
		
			$sClass=substr(_root::getParam('class'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');


			$tColumn=$this->getListColumnFromClass($sClass);
			$oTpl->sClass=$sClass;
			
			$tId=$this->getIdTabFromClass($sClass);
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
		
		if(_root::getRequest()->isPost() ){
			$sModuleToCreate=_root::getParam('moduleToCreate');
			$sClass=_root::getParam('sClass');
			$tColumn=_root::getParam('tColumn');
			$tType=_root::getParam('tType');
			$tEnable=_root::getParam('tEnable');

			foreach($tColumn as $i => $sColumn){
				if(!in_array($sColumn, $tEnable) ){
					unset($tColumn[$i]);
				}
			}
			
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');
			$oModel=new $sClass;
			$sModule=$sModuleToCreate;
			$this->projetmkdir('module/'.$sModule );
			$this->projetmkdir('module/'.$sModule.'/view');

			$this->genModelMainCrud($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn);
			$this->genModelTplCrud($sModuleToCreate,$sClass,$tColumn,$oModel->getTable());
			
			$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire module/'.$sModule;
			$detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/list.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/edit.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/new.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/show.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/delete.php';
			
			$detail.='<br/><br/>Pour y acc&eacute;der <a href="'._root::getConfigVar('path.generation')._root::getParam('id').'/public/index.php?:nav='.$sModule.'::index">cliquer ici (index.php?:nav='.$sModule.'::index)</a>';
			
		}
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		return $oTpl;
	}
	public function _crudembedded(){
	    $this->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
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
		
		$oTpl= new _Tpl('builder::crudembedded');
		
		if(_root::getParam('class') !='' ){
		
			$sClass=substr(_root::getParam('class'),0,-4);
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');


			$tColumn=$this->getListColumnFromClass($sClass);
			$oTpl->sClass=$sClass;
			
			$tId=$this->getIdTabFromClass($sClass);
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
		
		if(_root::getRequest()->isPost() ){
			$sModuleToCreate=_root::getParam('moduleToCreate');
			$sClass=_root::getParam('sClass');
			$tColumn=_root::getParam('tColumn');
			$tType=_root::getParam('tType');
			$tEnable=_root::getParam('tEnable');

			foreach($tColumn as $i => $sColumn){
				if(!in_array($sColumn, $tEnable) ){
					unset($tColumn[$i]);
				}
			}
			
			require_once(_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php');
			$oModel=new $sClass;
			$sModule=$sModuleToCreate;
			$this->projetmkdir('module/'.$sModule );
			$this->projetmkdir('module/'.$sModule.'/view');

			$this->genModelMainCrudembedded($sModuleToCreate,$oModel->getTable(),$sClass,$tColumn);
			$this->genModelTplCrudembedded($sModuleToCreate,$sClass,$tColumn,$oModel->getTable());
			
			$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire module/'.$sModule;
			$detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/list.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/edit.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/new.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/show.php';
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/delete.php';
			
			$sCode='<?php '."\n";
			$sCode.='//instancier le module'."\n";
			$sCode.='$oModule'.ucfirst(strtolower($sModule)).'=new module_'.$sModule.";\n\n";
			
			$sCode.='//si vous souhaitez indiquer au module integrable des informations sur le module parent'."\n";
			$sCode.='//$oModuleExamplemodule->setRootLink(\'module::action\',array(\'parametre\'=>_root::getParam(\'parametre\')));'."\n\n";
			
			$sCode.='//recupere la vue du module'."\n";
			$sCode.='$oView=$oModule'.ucfirst(strtolower($sModule)).'->_index();'."\n";
			$sCode.="\n";
			$sCode.='//assigner la vue retournee a votre layout'."\n";
			$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
			
			$detail.='<br/><br/>Pour l\'utiliser, indiquez:<br />
			'.highlight_string($sCode,1);
			
		}
		
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tFile=$tFile;
		return $oTpl;
	}
	
	public function _module(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			$sModule=_root::getParam('module');
			$sActions=_root::getParam('actions');
			
			$tAction=explode("\n",$sActions);

			
			if($this->projetmkdir('module/'.$sModule)==true){
			  $detail='Cr&eacute;ation repertoire module/'.$sModule;
			}else{
			  $detail='Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule;
			}
			
			if($this->projetmkdir('module/'.$sModule.'/view')==true){
			  $detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			}else{
			  $detail.='<br />Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule.'/view';
			}

			$this->genModuleMain($sModule,$tAction);
			
			$msg='Module '.$sModule.' (actions: '.implode(',',$tAction).') genere avec succes';
			
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			foreach($tAction as $sAction){
				$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sAction.'.php';
			}
			
			$detail.='<br />Accessible via';
			foreach($tAction as $sAction){
				$detail.='<br />- <a href="data/genere/'._root::getParam('id').'/public/index.php?:nav='.$sModule.'::'.$sAction.'">index.php?:nav='.$sModule.'::'.$sAction.'</a>';
			}
		}
	
		$oTpl= new _Tpl('builder::module');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	public function _moduleembedded(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			$sModule=_root::getParam('module');
			$sActions=_root::getParam('actions');
			
			$tAction=explode("\n",$sActions);

			
			if($this->projetmkdir('module/'.$sModule)==true){
			  $detail='Cr&eacute;ation repertoire module/'.$sModule;
			}else{
			  $detail='Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule;
			}
			
			if($this->projetmkdir('module/'.$sModule.'/view')==true){
			  $detail.='<br />Cr&eacute;ation repertoire module/'.$sModule.'/view';
			}else{
			  $detail.='<br />Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule.'/view';
			}

			$this->genModuleMainEmbedded($sModule,$tAction);
			
			$msg='Module '.$sModule.' (actions: '.implode(',',$tAction).') genere avec succes';
			
			$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
			foreach($tAction as $sAction){
				$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/'.$sAction.'.php';
			}
			
			$sCode='<?php '."\n";
			$sCode.='//instancier le module'."\n";
			$sCode.='$oModule'.ucfirst(strtolower($sModule)).'=new module_'.$sModule.";\n";
			$sCode.='//recupere la vue du module'."\n";
			$sCode.='$oView=$oModule'.ucfirst(strtolower($sModule)).'->_index();'."\n";
			$sCode.="\n";
			$sCode.='//assigner la vue retournee a votre layout'."\n";
			$sCode.='$this->oLayout->add(\'main\',$oView);'."\n";
			
			$detail.='<br/><br/>Pour l\'utiliser, indiquez:<br />
			'.highlight_string($sCode,1);
			
			
		}
	
		$oTpl= new _Tpl('builder::module');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	public function _xml(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			$this->projetmkdir('data/xml/base/'.$sTable);
			
			$this->genBaseXml($sTable,$tField);
			
			$msg='Base '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire data/xml/base/'.$sTable;
			$detail.='<br />Cr&eacute;ation fichier data/xml/base/'.$sTable.'/structure.xml';
			$detail.='<br />Cr&eacute;ation fichier data/xml/base/'.$sTable.'/max.xml';
			
		}
	
		$oTpl= new _Tpl('builder::xml');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	private function xmlindexselect(){
		$this->rootAddConf('conf/connexion.ini.php');
	
		$oTpl= new _Tpl('builder::xmlindexselect');
		$oTpl->tConnexion=_root::getConfigVar('db');
		
		return $oTpl;
	}
	
	public function _xmlindex(){
	
		if(_root::getParam('config')==''){
			return $this->xmlindexselect();
		}
	
		$this->rootAddConf('conf/connexion.ini.php');
		$msg='';
		$detail='';
		$tTables=array();
		$tTableColumn=array();
		
		$sConfig= _root::getParam('config');
		$tTables=$this->getListTablesFromConfig( $sConfig );
		$tTableColumn=array();
		foreach($tTables as $sTable){
			$tTableColumn[$sTable]=$this->getListColumnFromConfigAndTable($sConfig,$sTable);
		}
		
		$tFileIndex=array();
		if(_root::getParam('sTable')!=''){
			$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/data/xml/base/'._root::getParam('sTable').'/index');
			if(!$oDir->exist()){
				$oDir->save();
			}
			$tFile=array();
			foreach($oDir->getListDir() as $oFile){
				if(preg_match('/.index/',$oFile->getName())) 
				$tFileIndex[]=$oFile->getName();
			}
		}
		
		if(_root::getParam('regenerateIndexXml')!=''){
			$this->regenerateIndexXml($sConfig,_root::getParam('sTable'),_root::getParam('regenerateIndexXml'));
		}

	
		if(_root::getRequest()->isPost()){
			$sTable=_root::getParam('sTable');
			$tField=_root::getParam('tField');
			
			$this->projetmkdir('data/xml/base/'.$sTable.'/index');
			
			$this->projetmkdir('data/xml/base/'.$sTable.'/index/'.implode('.',$tField).'.index' );

			$this->regenerateIndexXml($sConfig,$sTable,implode('.',$tField).'.index');

			$msg='Index '.implode('.',$tField).' sur la table '.$sTable.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation repertoire data/xml/base/'.$sTable.'/index';
			$detail.='<br />Cr&eacute;ation repertoire index data/xml/base/'.$sTable.'/index/'.implode('.',$tField);
			$detail.='<br />Reg&eacute;n&eacute;ration de l\'index';
			
			
		}
	
		$oTpl= new _Tpl('builder::xmlindex');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tTables=$tTables;
 		$oTpl->tTableColumn=$tTableColumn;

		$oTpl->tFileIndex=$tFileIndex;
		return $oTpl;
	}
	
	

	
	public function _csv(){
		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sTable=_root::getParam('sTable');
			$tField=explode("\n",_root::getParam('sField'));
			
			$this->genBaseCsv($sTable,$tField);
			
			$msg='Base '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail.='<br />Cr&eacute;ation fichier data/csv/base/'.$sTable.'.csv';
			
		}
	
		$oTpl= new _Tpl('builder::csv');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		return $oTpl;
	}
	public function _sqlite(){
		$this->rootAddConf('conf/connexion.ini.php');
		
		$tConnexion=_root::getConfigVar('db');
		
		$tSqlite=array();
		foreach($tConnexion as $sConfig => $val){
			if(substr($val,0,6)=='sqlite'){
				$tSqlite[ substr($sConfig,0,-4) ]=$val;
			}
		}
		

		$msg='';
		$detail='';
		if(_root::getRequest()->isPost()){
			
			$sDbFilename=_root::getParam('sDbFilename');
			
			$sTable=_root::getParam('sTable');
			$tField=_root::getParam('tField');
			$tType=_root::getParam('tType');
			$tSize=_root::getParam('tSize');
			
			try{
				$oDb = new PDO($sDbFilename);
			}catch( PDOException $exception ){
				die($exception->getMessage());
			}
			$oDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sSql='CREATE TABLE IF NOT EXISTS '.$sTable.'(';
			$sSql.='id  INTEGER PRIMARY KEY AUTOINCREMENT';
			foreach($tField as $i => $sField){
				$sSql.=',';
				$sSql.=$sField.' '.$tType[$i]; if($tType[$i]=='VARCHAR'){ $sSql.='('.$tSize[$i].')';} 
			}
			$sSql.=')';
			
			
			try{
				$oDb->exec($sSql);
			}catch( PDOException $exception ){
				die($exception->getMessage());
			}

			$msg='Table '.$sTable.' (champs: '.implode(',',$tField).') g&eacute;n&eacute;r&eacute; avec succ&egrave;s';
			$detail='Cr&eacute;ation du fichier sqlite '.$sDbFilename;
			
			
		}
	
		$oTpl= new _Tpl('builder::sqlite');
		$oTpl->msg=$msg;
		$oTpl->detail=$detail;
		$oTpl->tSqlite=$tSqlite;
		return $oTpl;
	}
	
	public function _addmodule(){
		$tModule=array(
			'menu',
		);
	
		/*
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/module/');
			$tFile=array();
			$tRowMethodes=array();
			foreach($oDir->getListFile() as $oFile){
				*/
				
		$oView=new _view('builder::addmodule');
		$oView->tModule=$tModule;
		
		return $oView;
		
	}
	public function _addmodulemenu(){
	    
		$detail=null;
		$tError=array();
		$tModuleAndMethod=array();
		$bExist=0;
		if(_root::getRequest()->isPost()){
		    
			$sModule=_root::getParam('modulename');
			$tMethod=_root::getParam('tMethod');
			$tLabel=_root::getParam('tLabel');
		    
		    
		    $ok=1;
		    
		    //check formulaire
		    foreach($tMethod as $i => $sMethod){
				if($tLabel[$i]==''){
					$tError[$i]='Remplissez le libell&eacute; du lien';
					$ok=0;
				}
			}
			
			if($ok){
				
				if($this->projetmkdir('module/'.$sModule)==true){
					$detail='Cr&eacute;ation repertoire module/'.$sModule;

					if($this->projetmkdir('module/'.$sModule.'/view')==true){
						$detail.='<br />Cr&eacute;ation r&eacute;pertoire module/'.$sModule.'/view';
					
						$this->genModuleMenuMain($sModule,$tMethod,$tLabel);

						$msg='Module '.$sModule.' g&eacute;n&eacute;r&eacute; avec succ&egrave;s';

						$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/main.php';
						$detail.='<br />Cr&eacute;ation fichier module/'.$sModule.'/view/index.php';


						$sCode='<?php '."\n";
						$sCode.='//assignez le menu a l\'emplacement menu'."\n";
						$sCode.='$this->oLayout->addModule(\'menu\',\''.$sModule.'::index\');'."\n";

						$detail.='<br/><br/>Pour l\'utiliser, ajoutez dans votre methode before():<br />
						'.highlight_string($sCode,1);
					
					}else{
						$detail.='<br />Warning: repertoire d&eacute;j&agrave; existant module/'.$sModule.'/view';
					}

					
				}else{
				  $detail='Warning: repertoire module/'.$sModule.' d&eacute;j&agrave; existant: modifiez le nom du module menu';
				}
				
			}
			
		    
		}else{
	    
			$tModule=$this->getListModule();
			$tModuleAndMethod=array();
			foreach($tModule as $oModule){
				$sModuleName=$oModule->getName();
				if(in_array($sModuleName,array('menu','builder','example','exampleembedded'))){
					continue;
				}
				include $this->getRootWebsite().'module/'.$sModuleName.'/main.php';
				
				if(get_parent_class('module_'.$sModuleName)!='abstract_module'){
					continue;
				}
				
				$tMethods=get_class_methods('module_'.$sModuleName);
				foreach($tMethods as $i => $sMethod){
					if($sMethod[0]!='_' or substr($sMethod,0,2)=='__'){ 
						unset($tMethods[$i]);
					}
				}
				if(empty($tMethods)){
					continue;
				}
				$tModuleAndMethod[$sModuleName]=$tMethods;
			}
			
			$oDir=new _dir($this->getRootWebsite().'module/menu');
			$bExist=$oDir->exist();
		}
	    
		$oView=new _view('builder::addmodulemenu');
		$oView->tModuleAndMethod=$tModuleAndMethod;
		$oView->detail=$detail;
		$oView->bExist=$bExist;
		$oView->tError=$tError;
		return $oView;
	}
	private function genModuleMenuMain($sModuleMenuName,$tMethod,$tLabel){
	    
	    $sData=null;
	    foreach($tMethod as $i => $sLink){
		$sData.='\''.$tLabel[$i].'\' => \''.$sLink.'\','."\n";
	    }
	    
	    $oMainFile=new _file('data/sources/fichiers/module/menu/main.php');
	    $sContentMain=$oMainFile->getContent();
	    
	    $sContentMain=preg_replace('/\/\/TABLEAUICI/',$sData,$sContentMain);
	    $sContentMain=preg_replace('/examplemenu/',$sModuleMenuName,$sContentMain);
	    
	    
	    $oFileTpl=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleMenuName.'/main.php');
	    $oFileTpl->setContent($sContentMain);
	    $oFileTpl->save();
	    $oFileTpl->chmod(0777);
	    
	    $oViewFile=new _file('data/sources/fichiers/module/menu/view/index.php');
	    $sContentView=$oViewFile->getContent();
	    
	    $oFileTpl2=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModuleMenuName.'/view/index.php');
	    $oFileTpl2->setContent($sContentView);
	    $oFileTpl2->save();
	    $oFileTpl2->chmod(0777);
	    
	}
	
	public function after(){
		$this->oLayout->show();
	}
	//------------------------------------------------
	
	public function getListTablesFromConfig($sConfig){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		return $oModelFactory->getListTable();
	}
	public function getListColumnFromConfigAndTable($sConfig,$sTable){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		$oModelFactory->setTable( $sTable);
		return $oModelFactory->getListColumn();
	}
	public function getListRowsFromConfigAndTable($sConfig,$sTable){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		$oModelFactory->setTable( $sTable);
		return $oModelFactory->findMany('SELECT * FROM '.$sTable);
	}
	
	public function getListColumnFromClass($sClass){
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php';
		require_once( $sPath );
		$oModel=new $sClass;
		
		$sConfig=$oModel->getConfig();
		
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		return $oModel->getListColumn();
	}
	public function regenerateIndexXml($sConfig,$sTable,$sIndex){
		//$sConfig='xml';

		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		return $oModelFactory->getSgbd()->generateIndexForTable($sTable,$sIndex);

		
	}

	public function getIdTabFromClass($sClass){
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php';
		require_once( $sPath );
		$oModel=new $sClass;
		
		return $oModel->getIdTab();
	}
	
	public function rootAddConf($sConf){
		_root::addConf(_root::getConfigVar('path.generation')._root::getParam('id').'/'.$sConf);
		_root::loadConf();
	}
	
	public function genModelAndRowByTableConfigAndId($sTable,$sConfig,$sId,$tSelect=null){
		$sTable=trim($sTable);
		
		$sContentGetSelect=null;
		if(is_array($tSelect)){
			$sContentGetSelect=$this->stringReplaceIn(array(
												'exampleselectkey' => $tSelect['key'],
												'exampleselectval' => $tSelect['val'],
											),
											'data/sources/fichiers/model/getSelect.php'
			);
		}

		$sContent=$this->stringReplaceIn(array(
											'exampletb' => $sTable,
											'exampleid' => $sId,
											'exampleconfig' => $sConfig,
											'\/\/ICI' => $sContentGetSelect,
										),
										'data/sources/projet/model/model_example.sample.php'
		);
		
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/model/model_'.$sTable.'.php' );
		
		if($oFile->exist()){
		  return false;
		}
		
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
		return true;
	}
	public function genModuleMain($sModule,$tAction){
		$sContent=$this->stringReplaceIn(array(
											'_examplemodule' => '_'.$sModule,
											
										),
										'data/sources/projet/module/example/main.php'
		);
		preg_match_all('/#debutaction#(.*)?#finaction#/s',$sContent,$tMatch);
		
		$sMethodeSource=$tMatch[1][0];
		
		$sMethodes='';
		foreach($tAction as $sAction){
			$sAction=trim($sAction);
			if($sAction=='') continue;
			$sMethodes.=preg_replace('/examplemodule/',$sModule,
					  preg_replace('/exampleaction/',$sAction,
														  $sMethodeSource));
														  
		  $oFileTpl=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sAction.'.php');
		  $oFileTpl->setContent('vue '.$sModule.'::'.$sAction);
		  $oFileTpl->save();
		  $oFileTpl->chmod(0777);
		}
		
		$sContent=preg_replace('/\/\/ICI--/',$sMethodes,$sContent);
		
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	public function genModuleMainEmbedded($sModule,$tAction){
		$sContent=$this->stringReplaceIn(array(
											'_examplemodule' => '_'.$sModule,
											'examplemodule' => $sModule,
											
										),
										'data/sources/projet/module/exampleembedded/main.php'
		);
		preg_match_all('/#debutaction#(.*)?#finaction#/s',$sContent,$tMatch);
		
		$sMethodeSource=$tMatch[1][0];
		
		$sMethodes='';
		foreach($tAction as $sAction){
			$sAction=trim($sAction);
			if($sAction=='') continue;
			$sMethodes.=preg_replace('/examplemodule/',$sModule,
					  preg_replace('/exampleaction/',$sAction,
														  $sMethodeSource));
														  
		  $oFileTpl=new _file(_root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sAction.'.php');
		  $oFileTpl->setContent('vue '.$sModule.'::'.$sAction);
		  $oFileTpl->save();
		  $oFileTpl->chmod(0777);
		}
		
		$sContent=preg_replace('/\/\/ICI--/',$sMethodes,$sContent);
		
		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	
	
	public function genModelMainCrud($sModule,$sTableName,$sClass,$tColumn){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$oFile=new _file('data/sources/fichiers/module/crud/main.php');
		$sContent=$oFile->getContent();
		preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
		$sInputSelect=$tMatch[1][0];

		preg_match_all('/#uploadsave(.*)?#fin_uploadsave/s',$sContent,$tMatch);
		$sInputUpload=$tMatch[1][0];
	
		$sInputUpload=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInputUpload);
	
		$sTable='';
		foreach($tColumn as $i => $sColumn){
			$sType=$tType[$i];
			if(substr($sType,0,7)=='select;'){
				$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInputSelect);
				$sTable.=$sInput;
			}
			
		}
		$sContent=$this->stringReplaceIn(array(
										'oExamplemodel' => 'o'.ucfirst($sTableName),
										'tExamplemodel' => 't'.ucfirst($sTableName),
										'examplemodule' => $sModule,
										'examplemodel' => $sTableName,
										'\/\/icishow' => $sTable,
										'\/\/icinew' => $sTable,
										'\/\/iciedit' => $sTable,
										'\/\/icilist' => $sTable,
										'\/\/iciuploadsave' => $sInputUpload,
										'<\?php\/\*variables(.*)variables\*\/\?>' => '',
									),
									'data/sources/fichiers/module/crud/main.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	public function genModelTplCrud($sModule,$sClass,$tColumn,$sTableName){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$tTpl=array('list','show','edit','new','delete');
		
		foreach($tTpl as $sTpl){
			
			$oFile=new _file('data/sources/fichiers/module/crud/view/'.$sTpl.'.php');
			$sContent=$oFile->getContent();	
				
				preg_match_all('/#lignetd(.*)?#fin_lignetd/s',$sContent,$tMatch);
				$sLigne=$tMatch[1][0];
				
				preg_match_all('/#input(.*)?#fin_input/s',$sContent,$tMatch);
				$sInputText=$tMatch[1][0];
				
				preg_match_all('/#textarea(.*)?#fin_textarea/s',$sContent,$tMatch);
				$sInputTextarea=$tMatch[1][0];
				
				preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
				$sInputSelect=$tMatch[1][0];

				preg_match_all('/#upload(.*)?#fin_upload/s',$sContent,$tMatch);
				$sInputUpload=$tMatch[1][0];

				if($sTpl=='list'){
				//TH
				preg_match_all('/#ligneth(.*)?#fin_ligneth/s',$sContent,$tMatch);
				$sLigneTH=$tMatch[1][0];
				}				

				$sTable='';
				$sTableTh='';
				$sEnctype='';
				foreach($tColumn as $i => $sColumn){
					$sType=$tType[$i];
					if($sType=='text' or $sType=='date'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputText);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					}elseif($sType=='textarea'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputTextarea);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					}elseif(substr($sType,0,7)=='select;'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputSelect);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
						$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInput);
					}elseif($sType=='upload'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputUpload);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);

						$sEnctype=' enctype="multipart/form-data"';//changement du enctype du formulaire
					}
					$sTable.=preg_replace('/examplecolumn/',$sColumn,preg_replace('/exampletd/',$sInput,$sLigne));

					$sTableTh.=preg_replace('/exampleth/',$sColumn,$sLigneTH);
				}
				$sContent=$this->stringReplaceIn(array(
												'oExamplemodel' => 'o'.ucfirst($sTableName),
												'tExamplemodel' => 't'.ucfirst($sTableName),
												'examplemodule' => $sModule,
												'<\?php \/\/enctype\?>' => $sEnctype,
												'<\?php \/\/ici\?>' => $sTable,
												'<\?php \/\/icith\?>' => $sTableTh,
												'<\?php\/\*variables(.*)variables\*\/\?>' => '',
											),
											'data/sources/fichiers/module/crud/view/'.$sTpl.'.php'
				);
			
			
			
			
			$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sTpl.'.php' );
			$oFile->setContent($sContent);
			$oFile->save();
			$oFile->chmod(0777);
		}
		
		
		
	}
	public function genModelMainCrudembedded($sModule,$sTableName,$sClass,$tColumn){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$oFile=new _file('data/sources/fichiers/module/crudembedded/main.php');
		$sContent=$oFile->getContent();
		preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
		$sInputSelect=$tMatch[1][0];

		preg_match_all('/#uploadsave(.*)?#fin_uploadsave/s',$sContent,$tMatch);
		$sInputUpload=$tMatch[1][0];
	
		$sInputUpload=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInputUpload);
	
		$sTable='';
		foreach($tColumn as $i => $sColumn){
			$sType=$tType[$i];
			if(substr($sType,0,7)=='select;'){
				$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInputSelect);
				$sTable.=$sInput;
			}
			
		}
		$sContent=$this->stringReplaceIn(array(
										'oExamplemodel' => 'o'.ucfirst($sTableName),
										'tExamplemodel' => 't'.ucfirst($sTableName),
										'oModuleExamplemodule' => 'oModule'.ucfirst($sModule),
										'examplemodule' => $sModule,
										'examplemodel' => $sTableName,
										'\/\/icishow' => $sTable,
										'\/\/icinew' => $sTable,
										'\/\/iciedit' => $sTable,
										'\/\/icilist' => $sTable,
										'\/\/iciuploadsave' => $sInputUpload,
										'<\?php\/\*variables(.*)variables\*\/\?>' => '',
									),
									'data/sources/fichiers/module/crudembedded/main.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/main.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	public function genModelTplCrudembedded($sModule,$sClass,$tColumn,$sTableName){
		//$tColumn=_root::getParam('tColumn');
		$tType=_root::getParam('tType');
		
		$tTpl=array('list','show','edit','new','delete');
		
		foreach($tTpl as $sTpl){
			
			$oFile=new _file('data/sources/fichiers/module/crudembedded/view/'.$sTpl.'.php');
			$sContent=$oFile->getContent();	
				
				preg_match_all('/#lignetd(.*)?#fin_lignetd/s',$sContent,$tMatch);
				$sLigne=$tMatch[1][0];
				
				preg_match_all('/#input(.*)?#fin_input/s',$sContent,$tMatch);
				$sInputText=$tMatch[1][0];
				
				preg_match_all('/#textarea(.*)?#fin_textarea/s',$sContent,$tMatch);
				$sInputTextarea=$tMatch[1][0];
				
				preg_match_all('/#select(.*)?#fin_select/s',$sContent,$tMatch);
				$sInputSelect=$tMatch[1][0];

				preg_match_all('/#upload(.*)?#fin_upload/s',$sContent,$tMatch);
				$sInputUpload=$tMatch[1][0];

				if($sTpl=='list'){
				//TH
				preg_match_all('/#ligneth(.*)?#fin_ligneth/s',$sContent,$tMatch);
				$sLigneTH=$tMatch[1][0];
				}				

				$sTable='';
				$sTableTh='';
				$sEnctype='';
				foreach($tColumn as $i => $sColumn){
					$sType=$tType[$i];
					if($sType=='text' or $sType=='date'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputText);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					}elseif($sType=='textarea'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputTextarea);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
					}elseif(substr($sType,0,7)=='select;'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputSelect);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);
						$sInput=preg_replace('/examplemodel/',substr($sType,7),$sInput);
					}elseif($sType=='upload'){
						$sInput=preg_replace('/examplecolumn/',$sColumn,$sInputUpload);
						$sInput=preg_replace('/oExamplemodel/','o'.ucfirst($sTableName),$sInput);

						$sEnctype=' enctype="multipart/form-data"';//changement du enctype du formulaire
					}
					$sTable.=preg_replace('/examplecolumn/',$sColumn,preg_replace('/exampletd/',$sInput,$sLigne));

					$sTableTh.=preg_replace('/exampleth/',$sColumn,$sLigneTH);
				}
				$sContent=$this->stringReplaceIn(array(
												'oExamplemodel' => 'o'.ucfirst($sTableName),
												'tExamplemodel' => 't'.ucfirst($sTableName),
												'examplemodule' => $sModule,
												'<\?php \/\/enctype\?>' => $sEnctype,
												'<\?php \/\/ici\?>' => $sTable,
												'<\?php \/\/icith\?>' => $sTableTh,
												'<\?php\/\*variables(.*)variables\*\/\?>' => '',
											),
											'data/sources/fichiers/module/crudembedded/view/'.$sTpl.'.php'
				);
			
			
			
			
			$oFile=new _file( _root::getConfigVar('path.generation')._root::getParam('id').'/module/'.$sModule.'/view/'.$sTpl.'.php' );
			$oFile->setContent($sContent);
			$oFile->save();
			$oFile->chmod(0777);
		}
		
		
		
	}
	public function updateLayoutTitle($sProject){
		
		$sContent=$this->stringReplaceIn(array(
										'examplesite' => $sProject
									),
									_root::getConfigVar('path.generation').$sProject.'/layout/template1.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation').$sProject.'/layout/template1.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0777);
	}
	private function stringReplaceIn($tMatch,$sFile){
		$oFile=new _file($sFile);
		$sContent=$oFile->getContent();
		foreach($tMatch as $sPattern => $sReplace){
			$sContent=preg_replace('/'.$sPattern.'/s',$sReplace,$sContent);
		}
		return $sContent;
	}
	private function getListModule(){
	    $oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/module/');
	    return $oDir->getListDir();
	}
	private function getRootWebsite(){
	    return _root::getConfigVar('path.generation')._root::getParam('id').'/';
	}
	private function projetmkdir($sRep){
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/'.$sRep);
		try{
			$oDir->save();
			$oDir->chmod(0777);
		}catch(Exception $e){
			//pas grave si repertoire existe deja, mais on avertir quand meme
			return false;
		}
		return true;
	}
	public function genBaseXml($sTable,$tField){
		$ret="\n";
		$sXmlStructure='<?xml version="1.0" encoding="UTF-8"?>'.$ret;
		$sXmlStructure.='<structure>'.$ret;
		$sXmlStructure.='<colonne primaire="true">id</colonne>'.$ret;
		foreach($tField as $sField){
			if(trim($sField)=='') continue;
			$sXmlStructure.='<colonne>'.trim($sField).'</colonne>'.$ret;
		}
		$sXmlStructure.='</structure>'.$ret;
		
		$sXmlMax='<?xml version="1.0" encoding="ISO-8859-1"?>'.$ret;
		$sXmlMax.='<main>'.$ret;
		$sXmlMax.='<max><![CDATA[1]]></max>'.$ret;
		$sXmlMax.='</main>'.$ret;
		
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/xml/base/'.$sTable.'/';
		$oFile=new _file($sPath.'structure.xml');
		$oFile->setContent($sXmlStructure);
		$oFile->save();
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/xml/base/'.$sTable.'/';
		$oFile=new _file($sPath.'max.xml');
		$oFile->setContent($sXmlMax);
		$oFile->save();
	}
	public function genBaseCsv($sTable,$tField){
		$ret="\n";
		$sep=';';
		
		$sFile='1'.$ret;
		$sFile.='id'.$sep;
		
		foreach($tField as $sField){
			if(trim($sField)=='') continue;
			$sFile.=trim($sField).$sep;
		}
		$sFile.=$ret;
		
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/data/csv/base/'.$sTable.'.csv';
		$oFile=new _file($sPath);
		$oFile->setContent($sFile);
		$oFile->save();
	}
}
?>
