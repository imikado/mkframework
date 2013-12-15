<?php
Class module_menu extends abstract_module{
		
	public function _index(){
		
		$tLink=array(
			'Articles' => 'article::list',
			'Articles pagine' => 'article::listPagination',
			'Graphiques' => 'chart::examples',
			'Prive' => 'prive::list',
		);

		if(_root::getACL()->can('edit','acl') ){
			$tLink['Manage accounts']='account::list';
			$tLink['Manage groups']='group::list';
			$tLink['Manage permission']='permission::list';
		}
		
		
		
		$oView=new _view('menu::index');
		$oView->tLink=$tLink;
		
		return $oView;
	}
}
