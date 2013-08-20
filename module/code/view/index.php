<?php $src=null; 
	if(_root::getParam('file')!=''){
		$src=_root::getLink('code::editcode',array(
											'project'=>_root::getParam('project'),
											'file'=>'data/genere/'._root::getParam('project').'/'._root::getParam('file')
											)
							);
	}
?>
<div id="title"></div>
<iframe name="codeFrame" id="codeFrame" style="width:840px;height:600px;border:0px" src="<?php echo $src?>"></iframe>
