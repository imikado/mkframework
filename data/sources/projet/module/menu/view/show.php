<?php /*
------------------------------------------------------------

<%h1:$article->titre %>
<%p:$article->texte,array("class"=>"texte") %>
<%link:"editer","article::edit&id=".$article->id %>
<%link:"supprimer","article::delete&id=".param("id","GET"),array("class"=>"supp")%>
<%div:"test",array("class"=>"special")%>
>>>>>>>>>>>>>>>>>>>>>>
<h1><?php echo $article->titre ?></h1>
<p class="texte"><?php echo $article->texte ?></p>
<?php echo $html->getLink("editer","article::edit&id=".$article->id) ?>
<?php echo $html->getLink("supprimer","article::delete&id=".$request->getParam("id",GET),array("class"=>"supp")) ?>
<div class="special">test</div>

------------------------------------------------------------

<%form("action"=>"article::update&id=".$article->id,"action"=>"post")%>
nom <%input("name"=>"test","value"=>"1")%>
prenom <%input("name"=>"retest","value"=>"1")%>
<%submit("value"=>"go")%>
</form>
>>>>>>>>>>>>>>>>>>>>>>
<form action ="article::update&id=1" action="post">
	nom <input type="text" name="test" value="1" />
	prenom <input type="text" name="retest" value="1" />
	<input type="submit" value="go" />
</form>
------------------------------------------------------------

<%tableau%>
	<%ligne%>
		<%case("class"=>"special"):"mon texte" %>
		<%case:$article->id + 2%>
	<%ligne%>
		<%case:"autre texte" %>
		<%case:$article->titre%>
<%fin_tableau%>
>>>>>>>>>>>>>>>>>>>>>>

<table>
	<tr>
		<td class="special"> texte</td>
		<td><?php $article->id + 2?></td>
	</tr>
	<tr>
		<td>autre texte</td>
		<td><?php $article->titre?></td>
	</tr>
</table>
------------------------------------------------------------

<%div("class"=>"long"):
"du texte sur plusieurs lignes
du texte sur plusieurs lignes"
%>
>>>>>>>>>>>>
<?php echo $html->getDiv(
"du texte sur plusieurs lignes
du texte sur plusieurs lignes"
,array("class"=>"long") ) ?>
>>>>>>>>>>>>>>>>>>>>>>>>
<div class="long">
du texte sur plusieurs lignes
du texte sur plusieurs lignes
</div>

------------------------------------------------------------

<%linkJs:"test","alert(getById('titre').value)"%>
>>>>>>>>>>>>
<?php echo $html->getLinkJs("test","alert(getById('titre').value)") ?>
>>>>>>>>>>>>>>>>>>>>>>>>
<a href="#" onclick="alert( document.getElementById('titre').value );return false">test</a>

------------------------------------------------------------
<h1><?= $article->titre?></h1>
<p><?php= $article->texte ?><p>
<%link:"editer","article::edit&id=".$article->id %>
<%link:"supprimer","article::delete&id=".param("id",GET) %>

<%foreach:$articleTab,$article%>
<div>Ligne <%$article->id%></div>
<%endforeach%>

<%table%>
	<%ligne%>
		<%case:mon texte%>
		<%case:<?php $article->id + 2?>%>
<%getTable%>		
	
<table>
	<tr>
		<td>mon texte</td>
		<td><?php $article->id + 2?></td>
	</tr>
</table>

<%form:"article::update&id=".$article->id,POST%>
nom <%input:test,1%>
<%submit:go%>

<form action ="article::update&id=1" action="post">
	nom <input type="text" name="test" value="1" />
	prenom <input type="text" name="retest" value="1" />
	<input type="submit" value="go" />

</form>
*/ ?>