<h1>Inscription</h1>
<?php $oForm=new plugin_form($this->oUser);
$oForm->setMessage($this->tMessage);
?>
<form action="" method="POST" class="form-signin" role="form">
  
            <p>
               <label>Nom d'utilisateur</label>
               <?php echo $oForm->getInputText('#loginField#',array('class'=>'form-control'))?>
           </p>
           <p>
               <label>Mot de passe</label>
               <input type="password" name="password" class="form-control" />
           </p>
           <p>
               <label>Confirmez le mot de passe</label>
               <input type="password" name="password2" class="form-control"/>
           </p>
      
   
   <p><input type="submit" value="S'enregistrer" /> <a href="<?php echo _root::getLink('#MODULE#::login')?>">Page de login</a> </p>


<?php if($this->tMessage and isset($this->tMessage['success'])):?>
  <p><?php echo implode($this->tMessage['success'])?> </p>
<?php endif;?>
</form>
   
