<!--
 written by @Jane232
HTML-Teil der Registrierungsseite
-->

<div id="particles-js"></div>
<form class="input-box" action="<?php $link ?>" method="post" where="register">

  <h1> Registrierung </h1>
  <!-- Inputform für Registrierung-->
  <input type="text" placeholder="Nutzername" name="username" where="register" autocomplete="off" required>
  <input type="password" placeholder="Passwort" name="password" where="register" autocomplete="off" required>
  <input type="password" placeholder="Passwort verifizieren" where="register" name="password2" autocomplete="off" required>

  <button type="submit" name="login" value="1" where="register">Registrieren</button>

  <center class="log-reg-ausgabe">
    <?php
      //Output (Feedback)
      if (isset($_POST['username']) && isset($passwordinkorrekt)) {
          echo "  Passwörter stimmen nicht überein <br>";
      }
      if (isset($nutzerverg)) {
          echo " Nutzername ist bereits vergeben!<br>";
      }
      if (isset($registriert)) {
          echo " erfolgreich registriert<br> ";
      }
    ?>
    Zurück zum <a href=<?php echo $rel; ?>"index.php?display=login"> Login</a>
  </center>

</form>
<!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
<script src="<?php echo $rel; ?>Js/particles.min.js"></script>
<script src="<?php echo $rel; ?>Js/app.js"></script>
<script src="<?php echo $rel; ?>Js/jquery-3.4.1.min.js"> </script>
