<style>
.dbtb td{
border-top:0px;
}
.table input{
border:1px solid gray;
}

.dbtb .field{
width:150px;
}
.dbtb .type{
width:150px;
}
.dbtb .size{
width:60px;
}
</style>
<script>
function showHideSize(selectValue,iParam){
	if(selectValue=='VARCHAR'){
		getById('sizeField'+iParam).style.visibility='visible';
	}else{
		getById('sizeField'+iParam).style.visibility='hidden';
	}
}

var iField=0;

function addField(){
	var sFieldLine='<table class="dbtb">';
		sFieldLine+='			<tr>';
		sFieldLine+='				<td class="field"><input type="text" name="tField['+iField+']" /></td>';
			
		sFieldLine+='				<td class="type"><select onchange="showHideSize(this.value,'+iField+')" name="tType['+iField+']">';
		sFieldLine+='					<option value="VARCHAR">Varchar</option>';
		sFieldLine+='					<option value="INTEGER">Integer</option>';
		sFieldLine+='					<option value="TEXT">Text</option>';
		sFieldLine+='					<option value="DATETIME">DateTime</option>';
		sFieldLine+='					<option value="DATE">Date</option>';
		sFieldLine+='				</select></td>';
			
		sFieldLine+='				<td class="size"><input style="visibility:visible" id="sizeField'+iField+'" size="5" type="text" name="tSize['+iField+']" /></td>';
					
		sFieldLine+='			</tr>';
	sFieldLine+='			</table>';

	var tmpDiv=document.createElement('div');
	tmpDiv.innerHTML=sFieldLine;

	getById('fieldTable').appendChild(tmpDiv);
	
	iField++;
}
</script>
<div class="table">
<form action="" method="POST">
<table >
	<tr>
		<th><?php echo tr('label_SelectionnnezUneConfig')?></th>
		<td>
			<select name="sDbFilename">

				<?php foreach($this->tSqlite as $sConfig => $sFilename):?>
					<option value="<?php echo $sFilename?>"> <?php echo $sConfig ?> (<?php echo $sFilename?>)</option>
				<?php endforeach;?>
			</select>
		
		</td>
	</tr>
	<tr>
		<th><?php echo tr('label_NomDeLaTable')?></th>
		<td><input type="text" name="sTable"  style="width:200px;"/></td>
	</tr>
	
	<tr>
		<th><?php echo tr('label_ClePrimaire')?></th>
		<td><input type="text" name="sId" value="id" style="width:200px;"/> </td>
	</tr>
	
	<tr>
		<th><?php echo tr('label_Champs')?><br /><br/><input type="button" onclick="addField()" value="<?php echo tr('label_AjouterUnChamp')?>"/></th>
		<td>
		
			<table class="dbtb">
		
				<tr>
					<th class="field"><?php echo tr('label_Champ')?></th>
					<th class="type"><?php echo tr('label_Type')?></th>
					<th class="size"><?php echo tr('label_Longueur')?></th>
					
					
				</tr>
			</table>
			<div id="fieldTable">
				
			</div>
		
		</td>
	</tr>
</table>
<input type="submit" value="<?php echo tr('label_Generer')?>" />
</form>


<script>addField();</script>
<p class="msg"><?php echo $this->msg?></p>
<p class="detail"><?php echo $this->detail?></p>
</div>
