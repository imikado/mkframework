<?php

class module_#MODULE# extends abstract_module{

public function before(){
$this->oLayout = new _layout('bootstrap');

//$this->oLayout->addModule('menu','menu::index');
}


public function _index(){
//on considere que la page par defaut est la page de listage
$this->_list();
}

#iciMethodList#
#iciMethodEditList#
#iciMethodNew#
#iciMethodEdit#
#iciMethodShow#
#iciMethodDelete#

private function processListSaveMulti(){
if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
return null;
}

/* $oPluginXsrf=new plugin_xsrf();
  if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
  return array('token'=>$oPluginXsrf->getMessage() );
  } */

$tMessage = array();
$bSave = true;

$tPost = _root::getRequest()->getParams();

$tId = _root::getParam('#examplerow_id#', null);
foreach($tId as $key => $id){
$#oExamplemodel#=model_#examplemodel#::getInstance()->findById($id);


$tColumn = #icitColumn#
foreach($tColumn as $sColumn){
if(isset($tPost[$sColumn]) and isset($tPost[$sColumn][$key])){
$#oExamplemodel#->$sColumn=$tPost[$sColumn][$key];
}
}
#iciUpload#

if(!$#oExamplemodel#->save()){
$tMessage[$key] = $#oExamplemodel#->getListError();
$bSave = false;
}
}

if($bSave){
//une fois enregistre on redirige (vers la page liste)
_root::redirect('#examplemodule#::list');
}else{
return $tMessage;
}

}

private function processSave(){
if(!_root::getRequest()->isPost() ){ //si ce n'est pas une requete POST on ne soumet pas
return null;
}

$oPluginXsrf = new plugin_xsrf();
if(!$oPluginXsrf->checkToken( _root::getParam('token') ) ){ //on verifie que le token est valide
return array('token' => $oPluginXsrf->getMessage() );
}

$iId = _root::getParam('id', null);
if($iId==null){
$#oExamplemodel#=new row_#examplemodel#;
}else{
$#oExamplemodel#=model_#examplemodel#::getInstance()->findById( _root::getParam('id',null) );
}

$tColumn = #icitColumn#
foreach($tColumn as $sColumn){
$#oExamplemodel#->$sColumn=_root::getParam($sColumn,null) ;
}
#iciUpload#

if($#oExamplemodel#->save()){
//une fois enregistre on redirige (vers la page liste)
_root::redirect('#examplemodule#::list');
}else{
return $#oExamplemodel#->getListError();
}

}

#iciMethodProcessDelete#


public function after(){
$this->oLayout->show();
}


}

