<h1>Inscription</h1>
<?php $oForm=new plugin_form($this->oUser);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST">
   <table>
           <tr>
               <th>Nom d'utilisateur</th>
               <td><?php echo $oForm->getInputText('#loginField#')?></td>
           </tr>
           <tr>
               <th>Mot de passe</th>
               <td><input type="password" name="password" /></td>
           </tr>
           <tr>
               <th>Confirmez le mot de passe</th>
               <td><input type="password" name="password2" /></td>
           </tr>
      
   </table>
   <p><input type="submit" value="S'enregistrer" /> <a href="<?php echo _root::getLink('#MODULE#::login')?>">Page de login</a> </p>


<?php if($this->tMessage and isset($this->tMessage['success'])):?>
  <p><?php echo implode($this->tMessage['success'])?> </p>
<?php endif;?>
</form>
   
