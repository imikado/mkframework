<h1>Inscription</h1>
<form action="" method="POST">
   <table>
           <tr>
               <th>Nom d'utilisateur</th>
               <td><input type="text" name="login" value="<?php echo _root::getParam('login')?>" /></td>
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
   <p><input type="submit" value="S'enregistrer" /> <a href="<?php echo _root::getLink('exampleauth::login')?>">Page de login</a> </p>

<p><?php echo $this->message?> </p>
</form>
   
