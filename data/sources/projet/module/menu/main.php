<?php
Class module_menu extends abstract_module{
		
	public function _index(){
		
		$tLink=array(
			'Articles' => 'article::list',
			'Articles pagine' => 'article::listPagination',
			'Articles via module table' => 'article::listModuleTable',
			
			'Utiliser des classes metiers' => 'article::myclass',
			'Appeler des sous module' => 'private_article::list',
			
			'Graphiques' => 'chart::examples',
			'Graphiques SVG' => 'chart::examplesSVG',
			'Google Map' => 'default::googleMap',
			
			'Auteurs xml' => 'auteurxml::list',
			
			'Products "virtuel"'=>'virtualProducts::list',
			
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
