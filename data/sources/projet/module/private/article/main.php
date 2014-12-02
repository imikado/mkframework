<?php 
class module_private_article extends abstract_module{
	
	public function before(){
		$this->oLayout=new _layout('template1');
		
		$this->oLayout->addModule('menu','menu::index');
	}
	
	public function _list(){
		
		$oArticleModel=new model_article;
		$tArticle=$oArticleModel->findAllOrderBy(module_table::getParam('order','titre'),module_table::getParam('side'));
		
		$oView=new _view('private/article::slist');
		$oView->tArticle=$tArticle;
		$oView->tColumn=$oArticleModel->getListColumn();//array('id','titre');//

		//on recupere un tableau indexe des auteurs pour afficher leur nom a la place de leur id
		$oView->tJoinAuteur=model_auteur::getInstance()->getSelect();
		

		$this->oLayout->add('main',$oView);
	}
	
	public function after(){
		$this->oLayout->show();
	}
}
