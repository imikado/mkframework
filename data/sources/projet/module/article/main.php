<?php 
class module_article extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	
	public function _index(){
	    //on considere que la page par defaut est la page de listage
	    $this->_list();
	}
	
	public function _list(){
		
		$oArticleModel=new Model\Article;
		$tArticle=$oArticleModel->findAllOrderBy(module_table::getParam('order','titre'),module_table::getParam('side'));
		
		$oView=new _view('article::list');
		$oView->tArticle=$tArticle;
		$oView->tColumn=$oArticleModel->getListColumn();//array('id','titre');//

		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		

		$this->oLayout->add('main',$oView);
	}
	public function _listModuleTable(){
		
		$sOrder=module_table::getParam('order');
		if($sOrder==''){
			$sOrder='titre';
		}
		$sSide=module_table::getParam('side');
		if($sSide==''){
			$sSide='ASC';
		}
		
		$oArticleModel=new Model\Article;
		$tArticle=$oArticleModel->findAllOrderBy($sOrder,$sSide);
		
		$oView=new _view('article::listViaModule');
		$oView->tArticle=$tArticle;
		$oView->tColumn=$oArticleModel->getListColumn();//array('id','titre');//

		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		

		$this->oLayout->add('main',$oView);
	}
	
	public function _listAjax(){
		$sOrder=module_table::getParam('order');
		if($sOrder==''){
			$sOrder='titre';
		}
		$sSide=module_table::getParam('side');
		if($sSide==''){
			$sSide='ASC';
		}
		
		$oArticleModel=new Model\Article;
		$tArticle=$oArticleModel->findAllOrderBy($sOrder,$sSide);
		
		$oView=new _view('article::listViaModuleAjax');
		$oView->tArticle=$tArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		
		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		
		print $oView->show();exit;
	}
	
	public function _listPagination(){
		$oArticleModel=new Model\Article;
		$tArticle=$oArticleModel->findAll();
		
		$oModulePagination=new module_pagination;
		$oModulePagination->setModuleAction('article::listPagination');
		$oModulePagination->setParamPage('page');
		$oModulePagination->setLimit(2);
		$oModulePagination->setPage( _root::getParam('page') );
		$oModulePagination->setTab( $tArticle );
		
		$oTpl=new _tpl('article::listPagination');
		$oTpl->tArticle=$oModulePagination->getPageElement();
		$oTpl->tColumn=$oArticleModel->getListColumn();
		$oTpl->oModulePagination=$oModulePagination->build();
		
		$this->oLayout->add('main',$oTpl);
	}
	
	public function _myclass(){

        $oMetier=new my_metier();

        $oView=new _view('article::myclass');
        $oView->oMetier=$oMetier;

        $oMetierNS=new Service\MetierNS();
        $oView->oMetierNS=$oMetierNS;

        $this->oLayout->add('main',$oView);
		
		
	}

	public function _new(){
		$tMessage=$this->save();
	
		$oArticleModel=new Model\Article;
		$oArticle=new Row\Article;
		
		$oView=new _view('article::new');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		
		$oPluginXsrf=new Plugin\XSRF();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}
	
	
	public function _edit(){
		$tMessage=$this->save();
		
		$oArticleModel=new Model\Article;
		$oArticle=$oArticleModel->findById( _root::getParam('id') );
		
		$oView=new _view('article::edit');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		
		$oPluginXsrf=new Plugin\XSRF();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}

	public function _show(){
		$oArticleModel=new Model\Article;
		$oArticle=$oArticleModel->findById( _root::getParam('id') );
		
		$oView=new _view('article::show');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=Model\Auteur::getInstance()->getSelect();
		
		$this->oLayout->add('main',$oView);
	}
	
	public function _delete(){
		$tMessage=$this->delete();

		$oArticleModel=new Model\Article;
		$oArticle=$oArticleModel->findById( _root::getParam('id') );
		
		$oView=new _view('article::delete');
		$oView->oArticle=$oArticle;
		$oView->tColumn=$oArticleModel->getListColumn();
		$oView->tId=$oArticleModel->getIdTab();
		
		

		$oPluginXsrf=new Plugin\XSRF();
		$oView->token=$oPluginXsrf->getToken();
		$oView->tMessage=$tMessage;
		
		$this->oLayout->add('main',$oView);
	}

	public function save(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new Plugin\XSRF();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oArticleModel=new Model\Article;
		$iId=_root::getParam('id',null);
		if($iId==null){
			$oArticle=new Row\Article;
		}else{
			$oArticle=$oArticleModel->findById( _root::getParam('id',null) );
		}
			
		foreach($oArticleModel->getListColumn() as $sColumn){
			if( _root::getParam($sColumn,null) === null ) continue;
			if( in_array($sColumn,$oArticleModel->getIdTab())) continue;
			$oArticle->$sColumn=_root::getParam($sColumn,null) ;
		}
		
		if($oArticle->save()){
			//une fois enregistre on redirige (vers la page d'edition)
			_root::redirect('article::list');
		}else{
			return $oArticle->getListError();
		}
		
	}

	public function delete(){
		if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
			return null;
		}
		
		$oPluginXsrf=new Plugin\XSRF();
		if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
			return array('token'=>$oPluginXsrf->getMessage() );
		}
	
		$oArticleModel=new Model\Article;
		$iId=_root::getParam('id',null);
		if($iId!=null){
			$oArticle=$oArticleModel->findById( _root::getParam('id',null) );
		}
			
		$oArticle->delete();
		//une fois enregistre on redirige (vers la page d'edition)
		_root::redirect('article::list');
		
	}

	public function _newsrss(){
		$oPluginRss=new Plugin\RSS();
		$oPluginRss->setName('news');
		$oPluginRss->setTitre('Titre du site');
		$oPluginRss->setUrl('http://www.urldevotresite.org/');
		$oPluginRss->setDesc('Feed Rss du site');
		$oPluginRss->setAdresseRss('http://urldevotresite.org/index.php?:nav=article::newsrss');
		
		$oModelArticle=new Model\Article;
		$tArticle=$oModelArticle->findAll();
		
		foreach($tArticle as $oArticle){
			if($oArticle->id <7) continue;
		
			$oPluginRss->addNews(array(
					'titre' => html_entity_decode($oArticle->titre,ENT_QUOTES),
					'desc' => $oArticle->desc,
					'date' => $oArticle->date,
					'link' => 'http://urldevotresite.org/post-'.$oArticle->id.'.html',
					'id' => $oArticle->id
			));
		}

		
		print $oPluginRss->getContent();
		exit;
		
	}
	
	public function after(){
		$this->oLayout->show();
	}
	
	
}
