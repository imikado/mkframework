<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Builder</title>
<link rel="stylesheet" type="text/css" href="site/css/mainCode.css" media="screen" />
<script src="site/js/main.js" type="text/javascript"></script>

</head>
<script>
function openclose(sId){
	var a=getById(sId);
	var b=getById('link'+sId);
	if(a.style.display=='none'){
		a.style.display='block';
		
		if(b){
			b.className='diropen';
		}
	}else{
		a.style.display='none';
		
		if(b){
			b.className='dir';
		}
	}
}
function openDir(sId){
	sId='arbo'+sId;
	var a=getById(sId);
	var b=getById('link'+sId);
	
	if(a){
		a.style.display='block';
		
		if(b){
			b.className='diropen';
		}
	}
}
var tmpLink=null;
function selectFile(sFile){
	if(tmpLink!=null){
		getById(tmpLink).style.backgroundColor="#fff";
	}
	var b=getById('link'+sFile);
	tmpLink='link'+sFile;
	if(b){
		b.style.backgroundColor='#b9d4d5';
	}
	
	var tmpDir='';
	var tDir=sFile.split('/');
	var maxDir=tDir.length-1;
	for(var i=3;i<maxDir;i++){
		
		openDir(tmpDir+tDir[i]);
		tmpDir+=tDir[i]+"::";
	}
	
}
function openFile(sType,sFile){
	var a=getById('codeFrame');
	if(a){
		a.src='<?php echo _root::getLink('code::editcode',array('project'=>_root::getParam('project'),'file'=>null),false)?>'+sFile+'&type='+sType;
	}
	selectFile(sFile);
}
function openCrossPopup(sType){
	var a=getById('popupFrame');
	if(a){
		if(sType=='addModule'){
			a.src='<?php echo _root::getLink('builder::editembedded',array('id'=>_root::getParam('project'),'action'=>'mods_normal_module::index'),false)?>#createon';
		}else if(sType=='genModel'){
			a.src='<?php echo _root::getLink('builder::editembedded',array('id'=>_root::getParam('project'),'action'=>'mods_all_model::index'),false)?>#createon';
		}
	}
	var b=getById('popup');
	if(b){
		b.style.display='block';
	}
}
function help(sClass){
	var a=getById('popupFrame');
	if(a){
		a.src='http://mkdevs.com/doxygen/'+sClass+'.html';
	}
	var b=getById('popup');
	if(b){
		b.style.display='block';
	}
}
function closePopup(){
	var b=getById('popup');
	if(b){
		b.style.display='none';
	}
}
function setTitle(sTitle,sAdresse){
	var a=getById('title');
	if(a){
		a.innerHTML='<h1>'+sTitle+'<span style="font-weight:regular;font-size:14px;color:#444">'+sAdresse+'</span></h1>';
	}
}
</script>
<body>

<div class="main">
	<div class="menu"><?php echo $this->load('menu') ?></div>
	<div class="content">
		<?php echo $this->load('main') ?>
	</div>
</div>

<div id="popup" class="popup">
<p class="fermer"><a href="#" onclick="closePopup();return false">Fermer</a></p>
<iframe id="popupFrame" />
</div>

</body>
</html>
