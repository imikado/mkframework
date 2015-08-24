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
	
	public static $oTools;
	
	public static function getTools(){
		return self::$oTools;
	}
	
	public function before(){
		self::$oTools=new module_builderTools();
		
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	private function getList(){
		$oProjetModel=new model_mkfbuilderprojet;
		$tProjet=$oProjetModel->findAll();
		
		sort($tProjet);//tri par ordre alphabetique
		
		$oTpl=new _tpl('builder::list');
		$oTpl->tProjet=$tProjet;
		
		return $oTpl;
	}
	
	public function _index(){
		_root::redirect('builder::new');
	}
	
	public function _list(){
	
		$oTpl=$this->getList();
		
		$this->oLayout->add('main',$oTpl);
		
	}
	
	public function _new(){
		if(_root::getRequest()->isPost() ){
		
			 $sProject=_root::getParam('projet');
		
		    if(isset($_POST['opt']) and $_POST['opt']=='withexamples'){
		        model_mkfbuilderprojet::getInstance()->create( _root::getParam('projet') );
		        self::getTools()->updateLayoutTitle( _root::getParam('projet') );
		    }else if(isset($_POST['opt']) and $_POST['opt']=='withBootstrap'){
		        model_mkfbuilderprojet::getInstance()->createEmpty( $sProject );
		        
		        //copy bootstrap
		        model_mkfbuilderprojet::getInstance()->copyFromTo('data/sources/fichiers/layout/bootstrap.php','data/genere/'.$sProject.'/layout/bootstrap.php');
		        
		        //update title
		        self::getTools()->updateFile( _root::getParam('projet'), array('examplesite'=>$sProject), 'layout/bootstrap.php' );
		        
		        //update layout
		        self::getTools()->updateFile( _root::getParam('projet'), array('template1'=>'bootstrap'), 'module/default/main.php' );

		    }else{
			    model_mkfbuilderprojet::getInstance()->createEmpty( _root::getParam('projet') );
			    self::getTools()->updateLayoutTitle( _root::getParam('projet') );
			}
			_root::redirect( 'builder::list' );
		}
	
		$oTpl=new _tpl('builder::new');
		$oTpl->iswritable=is_writable(_root::getConfigVar('path.generation'));

		$this->oLayout->add('main',$oTpl);

		
	}
	
	public function _export(){
		$tReturn=$this->processExport();

		$this->oLayout->setLayout('templateProjet');
		
		$oTplList=$this->getList();
		
		$this->oLayout->add('list',$oTplList);
		
		$this->oLayout->addModule('nav','menu::export');
		
		$oTpl=new _tpl('builder::export');
		$oTpl->tReturn=$tReturn;
		

		$this->oLayout->add('main',$oTpl);
		
	}

	private function processExport(){
		if(!_root::getRequest()->isPost()){
			return array();
		}


		$sFrom='data/genere/'._root::getParam('from').'/';
		$sTo=_root::getParam('to').'/'._root::getParam('from');

		$oDir=new _dir($sTo);
		if($oDir->exist()){
			return array('error'=>'Repertoire '.$sTo.' existe deja');
		}

		if(!in_array(_root::getParam('lib'),array('link','copy'))){
			return array('error'=>'Veuillez s&eacute;lectionner un choix pour la librairie du framework');
		}

		$oModelProject=model_mkfbuilderprojet::getInstance()->copyFromTo($sFrom,$sTo);

		if(_root::getParam('lib')=='link'){

			$sLib=__DIR__;
			$sLib=str_replace('module/builder','lib/framework/',$sLib);

			$this->updateLibPathInConf($sTo,$sLib);

			$detail='Projet cr&eacute;e dans '.$sTo;
			$detail.='<br/>Dans votre projet, la librairie du framework pointe sur '.$sLib;

			return array('ok'=>'Projet bien export&eacute; sur '.$sTo,'detail'=>$detail);
		}else if(_root::getParam('lib')=='copy'){

			$oDir=new _dir($sTo.'/lib/');
			$oDir->save();
			//copy du framework
			$oModelProject=model_mkfbuilderprojet::getInstance()->copyFromTo('lib/framework',$sTo.'/lib/mkframework');

			$sLib='../lib/mkframework/';

			$this->updateLibPathInConf($sTo,$sLib);

			$detail='Projet cr&eacute;e dans '.$sTo;
			$detail.='<br/>Dans votre projet, la librairie du framework a ete copie dans '.$sLib;

			return array('ok'=>'Projet bien export&eacute; sur '.$sTo,'detail'=>$detail);
		}


	}

	private function updateLibPathInConf($sProject,$sLib){
		//replace link library
		$oIniFile=new _file($sProject.'/conf/site.ini.php');
		$tIni=$oIniFile->getTab();

		$tNewIni=array();

		$bSection=0;
		foreach ($tIni as $line) {
			if(preg_match('/\[path\]/', $line)){
				$bSection=1;
			}else if($bSection && substr($line,0,3)=='lib'){
				$line='lib='.$sLib;
			}

			$tNewIni[]=$line;
		}

		$oIniFile->setContent(implode($tNewIni,""));
		$oIniFile->save();
	}

	public function _edit(){
		
		$this->oLayout->setLayout('templateProjet');
		
		$oTplList=$this->getList();
		
		$this->oLayout->add('list',$oTplList);
		
		$this->oLayout->addModule('nav','menu::projet');
		
		$oTpl=new _tpl('builder::edit');
		$this->oLayout->add('main',$oTpl);
		
		//CRUD
		if(_root::getParam('action')=='crud'){//OK
			$this->oLayout->addModule('main','moduleCrud::index');//OK
		/*CRUD Guriddo*/}else if(_root::getParam('action')=='crudguriddo'){//OK
			$this->oLayout->addModule('main','moduleCrudGuriddo::index');//OK
		/*Bootstrap*/}else if(_root::getParam('action')=='crudWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleCrudBootstrap::index');//OK
		}elseif(_root::getParam('action')=='crudreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudReadonly::index');//OK
		/*Bootstrap*/}elseif(_root::getParam('action')=='crudreadonlyWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleCrudReadonlyBootstrap::index');//OK
		}elseif(_root::getParam('action')=='crudembedded'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbedded::index');//OK
		/*Bootstrap*/}elseif(_root::getParam('action')=='crudembeddedWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbeddedWithBootstrap::index');//OK	
			
		}elseif(_root::getParam('action')=='crudembeddedreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbeddedReadonly::index');//OK
		
		//auth
		}elseif(_root::getParam('action')=='authmodule'){//OK
			$this->oLayout->addModule('main','moduleAuth::index');//OK	
		/*Bootstrap*/}elseif(_root::getParam('action')=='authmoduleWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleAuthBootstrap::index');//OK		
			
		}elseif(_root::getParam('action')=='authwithinscriptionmodule'){//OK
			$this->oLayout->addModule('main','moduleAuthWithInscription::index');//OK	
		/*Bootstrap*/}elseif(_root::getParam('action')=='authwithinscriptionmoduleWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleAuthWithInscriptionBootstrap::index');//OK	
	
		//module
		}elseif(_root::getParam('action')=='module'){//OK
			$this->oLayout->addModule('main','moduleModule::index');//OK
		/*bootstrap*/}elseif(_root::getParam('action')=='moduleWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleModuleWithBootstrap::index');//OK
		}elseif(_root::getParam('action')=='moduleembedded'){//OK
			$this->oLayout->addModule('main','moduleModuleEmbedded::index');//OK
		/*Bootstrap*/}elseif(_root::getParam('action')=='moduleembeddedWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleModuleEmbeddedWithBootstrap::index');//OK
		
		
		//model
		}elseif(_root::getParam('action')=='model'){//OK
			$this->oLayout->addModule('main','moduleModel::index');//OK
		
		//sgbd
		}elseif(_root::getParam('action')=='xml'){//OK
			$this->oLayout->addModule('main','moduleXml::index');//OK
		}elseif(_root::getParam('action')=='xmlindex'){//OK
			$this->oLayout->addModule('main','moduleXmlIndex::index');//OK
		}elseif(_root::getParam('action')=='csv'){
			$this->oLayout->addModule('main','moduleCsv::index');//OK
		}elseif(_root::getParam('action')=='sqlite'){
			$this->oLayout->addModule('main','moduleSqlite::index');
		}elseif(_root::getParam('action')=='json'){
			$this->oLayout->addModule('main','moduleJson::index');
		}elseif(_root::getParam('action')=='jsonindex'){
			$this->oLayout->addModule('main','moduleJsonIndex::index');
			
		//menu
		}elseif(_root::getParam('action')=='addmodulemenu'){//OK
			$this->oLayout->addModule('main','moduleMenu::index');//OK
		/*bootstrap*/}elseif(_root::getParam('action')=='addmodulemenuWithBootstrap'){//OK
			$this->oLayout->addModule('main','moduleMenuBootstrap::index');//OK
		
		//vues
		}elseif(_root::getParam('action')=='addviewtablemoduletablesimple'){//OK
			$this->oLayout->addModule('main','moduleViewTable::simple');//OK
		//vues
		}elseif(_root::getParam('action')=='addviewtablemoduletablewithorder'){//OK
			$this->oLayout->addModule('main','moduleViewTable::complexWithOrder');//OK
		}elseif(_root::getParam('action')=='addviewtablemoduletablewithorderclic'){//OK
			$this->oLayout->addModule('main','moduleViewTable::complexWithOrderAndClic');//OK
		}elseif(_root::getParam('action')=='addviewform'){//OK
			$this->oLayout->addModule('main','moduleViewForm::simple');//OK
			
		//code
		}elseif(_root::getParam('action')=='addrightsmanager'){//OK
			$this->oLayout->addModule('main','moduleCoderightsmanager::index');//OK
		}		
		
		
	}
	
	public function _editembedded(){
		$this->oLayout->setLayout('templateProjetEmbedded');
		
		$this->oLayout->addModule('nav','menu::projetEmbedded');
		
		$oTpl=new _tpl('builder::edit');
		$this->oLayout->add('main',$oTpl);
		
		//CRUD
		if(_root::getParam('action')=='crud'){//OK
			$this->oLayout->addModule('main','moduleCrud::index');//OK
		}elseif(_root::getParam('action')=='crudreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudReadonly::index');//OK
		}elseif(_root::getParam('action')=='crudembedded'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbedded::index');//OK
		}elseif(_root::getParam('action')=='crudembeddedreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbeddedReadonly::index');//OK
		
		//auth
		}elseif(_root::getParam('action')=='authmodule'){//OK
			$this->oLayout->addModule('main','moduleAuth::index');//OK	
	
		//module
		}elseif(_root::getParam('action')=='module'){//OK
			$this->oLayout->addModule('main','moduleModule::index');//OK
		}elseif(_root::getParam('action')=='moduleembedded'){//OK
			$this->oLayout->addModule('main','moduleModuleEmbedded::index');//OK
		
		//model
		}elseif(_root::getParam('action')=='model'){//OK
			$this->oLayout->addModule('main','moduleModel::index');//OK
		
		//sgbd
		}elseif(_root::getParam('action')=='xml'){//OK
			$this->oLayout->addModule('main','moduleXml::index');//OK
		}elseif(_root::getParam('action')=='xmlindex'){//OK
			$this->oLayout->addModule('main','builder::xmlindex');//OK
		}elseif(_root::getParam('action')=='csv'){
			$this->oLayout->addModule('main','moduleCsv::index');//OK
		}elseif(_root::getParam('action')=='sqlite'){
			$this->oLayout->addModule('main','moduleSqlite::index');
			
		//menu
		}elseif(_root::getParam('action')=='addmodulemenu'){//OK
			$this->oLayout->addModule('main','moduleMenu::index');//OK
		}
	}
	
	
	
	
	public function after(){
		$this->oLayout->show();
	}
	//------------------------------------------------
	
	
	
	
	
	
	
	
	
	
}
?>
