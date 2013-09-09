<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Builder</title>
<link rel="stylesheet" type="text/css" href="site/css/mainCodeFrame.css" media="screen" />
<script src="site/js/main.js" type="text/javascript"></script>
<style>
.helpDoc{
cursor:help;
background:#F2F5A9;
}
.goto{
cursor:help;
background:#F5D0A9;
}

</style>
</head>
<script>
function help(sClass){
	window.parent.help(sClass);
}
function gotofile(sFile){
	document.location.href='<?php echo _root::getLink('code::editcode',array('project'=>_root::getParam('project'),'file'=>'data/genere/'._root::getParam('project').'/'),false)?>'+sFile;
	window.parent.selectFile('data/genere/<?php echo _root::getParam('project')?>/'+sFile);
}
function gotofileandmethod(sFile,sType,sMethod){
	document.location.href='<?php echo _root::getLink('code::editcode',array('project'=>_root::getParam('project'),'file'=>'data/genere/'._root::getParam('project').'/'),false)?>'+sFile+'&type='+sType+'&method='+sMethod;
	window.parent.selectFile('data/genere/<?php echo _root::getParam('project')?>/'+sFile);
}
function gotofileandtype(sFile,sType){
	document.location.href='<?php echo _root::getLink('code::editcode',array('project'=>_root::getParam('project'),'file'=>'data/genere/'._root::getParam('project').'/'),false)?>'+sFile+'&type='+sType;
	window.parent.selectFile('data/genere/<?php echo _root::getParam('project')?>/'+sFile);
}
var tmpLine='';
function editLine(iLine){
	closeEditLine();
	
	var a=getById('line'+iLine);
	if(a){
		a.style.display='block';
		
		tmpLine='line'+iLine;
	}
}
function closeEditLine(){
	if(tmpLine!=''){
		var a=getById(tmpLine);
		if(a){
			a.style.display='none';
		}
	}
}
function addContent(sId,sAction,tTab){
	var a=getById('content'+sId);
	if(a){
		var b=getById('addContent'+sAction);
		if(b){
			var sContent=b.value;
			
			if(sAction=="addAction"){
				var sNomFunction=prompt('Le nom de l\'action','monaction');
				if(sNomFunction){
					sContent=sContent.replace('monaction',sNomFunction);	
				}else{
					closeEditLine()
					return;
				}
				
			}else if(sAction=='addView'){
				var sModule=prompt('Le nom du module',tTab[0]);
				var sView=prompt('Le nom de votre vue',tTab[1]);
				if(sModule && sView){
					sContent=sContent.replace('mymodule',sModule);
					sContent=sContent.replace('myview',sView);	
				}else{
					closeEditLine()
					return;
				}
			}
			
			a.value+=sContent;
			
			var c=getById('line'+sId);
			c.submit();
		}
	}
}
function colorLine(idLine){
	var a=getById('hr'+idLine);
	
	if(a){
		a.style.borderColor='red';
			
	}
	var b=getById('num'+idLine);
	
	if(b){
		b.style.color='red';
			
	}
}
</script>
<body>

		<?php echo $this->load('main') ?>


<textarea id="addContentaddAction" style="display:none">
	
	public function _monaction(){
		
	}
	
</textarea>
<textarea id="addContentaddView" style="display:none">
	
		//create new view
		$oView = new _view('mymodule::myview');
		
		//add the view on the layout
		//$this->oLayout->add('main',$oView);
		
</textarea>


</body>
</html>
