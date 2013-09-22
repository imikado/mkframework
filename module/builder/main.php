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
		
		    if(isset($_POST['withexamples']) and $_POST['withexamples']==1){
		        model_mkfbuilderprojet::getInstance()->create( _root::getParam('projet') );
		        self::getTools()->updateLayoutTitle( _root::getParam('projet') );
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
		}elseif(_root::getParam('action')=='crudreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudReadonly::index');//OK
		}elseif(_root::getParam('action')=='crudembedded'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbedded::index');//OK
		}elseif(_root::getParam('action')=='crudembeddedreadonly'){//OK
			$this->oLayout->addModule('main','moduleCrudEmbeddedReadonly::index');//OK
		
		//auth
		}elseif(_root::getParam('action')=='authmodule'){//OK
			$this->oLayout->addModule('main','moduleAuth::index');//OK	
		}elseif(_root::getParam('action')=='authwithinscriptionmodule'){//OK
			$this->oLayout->addModule('main','moduleAuthWithInscription::index');//OK	
	
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
			$this->oLayout->addModule('main','moduleXmlIndex::index');//OK
		}elseif(_root::getParam('action')=='csv'){
			$this->oLayout->addModule('main','moduleCsv::index');//OK
		}elseif(_root::getParam('action')=='sqlite'){
			$this->oLayout->addModule('main','moduleSqlite::index');
			
		//menu
		}elseif(_root::getParam('action')=='addmodulemenu'){//OK
			$this->oLayout->addModule('main','moduleMenu::index');//OK
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
