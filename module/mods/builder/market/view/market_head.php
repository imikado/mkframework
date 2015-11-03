<style>
.main .content table th{
	background:#fff;
	text-align: left;
}
.main .content table td{
	text-align: left;
}
.toUpdate td{
	color:#630505;
	font-weight: bold;
	vertical-align: top;
}
 
.author{
	color:#777;
	 font-style: italic;
}

.main .menu2{
margin-top:10px;
}
.main .menu2 ul{
margin:0px;
padding:0px;
}
.main .menu2 li{
background:#5b899d;
list-style:none;
padding:4px 12px;
margin:6px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-moz-border-radius: 4px;
}
.main .menu2 .selectionne{
background:#233035;
}
.main .menu2 .selectionne a{
font-weight:bold;
}
.main .menu2 a{
text-decoration:none;
color:#fff;
}
h2{
	background:#fff;
	font-size:14px;
	color:#1f556e;
}
h3{
	margin-top:0px;
	font-size:12px;
	color:#1f556e;
	font-weight: normal;
}
.market{
	padding:10px;
}
.installed{
	color:#444;

}
</style>
<div class="menu2">
<ul>
	<?php foreach($this->tNav as $link => $label):?>
	<li <?php if(_root::getParam('market','index')==$link):?>class="selectionne"<?php endif;?> ><a href="<?php echo _root::getLink('builder::marketBuilder',array('action'=>'install','market'=>$link) )?>"><?php echo $label?></a></li>
	<?php endforeach;?>
</ul>
</div>
<div style="clear:both"></div>