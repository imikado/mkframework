<form action="" method="POST">
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( !in_array($sColumn,$this->tId)) continue;?>
	<input type="hidden" name="<?php echo $sColumn ?>" value="<?php echo $this->oArticle->$sColumn ?>" />
<?php endforeach;?>	
<table>
<?php foreach($this->tColumn as $sColumn):?>
	<?php if( in_array($sColumn,$this->tId)) continue;?>
	
	<tr>
		<th><?php echo $sColumn ?></th>
		<td>
			<?php if($sColumn == 'texte'):?>
				<textarea name="<?php echo $sColumn ?>"><?php echo $this->oArticle->$sColumn ?></textarea>
			<?php else:?>
				<input name="<?php echo $sColumn ?>" value="<?php echo $this->oArticle->$sColumn ?>" />
			<?php endif;?>
			
			<?php if($this->tMessage and isset($this->tMessage[$sColumn])): echo implode(',',$this->tMessage[$sColumn]); endif;?>
		</td>
	</tr>
<?php endforeach;?>
</table>

<input type="hidden" name="token" value="<?php echo $this->token?>" />
<?php if($this->tMessage and isset($this->tMessage['token'])): echo $this->tMessage['token']; endif;?>

<input type="submit" value="Modifier" />
</form>
