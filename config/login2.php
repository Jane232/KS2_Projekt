<!--
written by @Jane232

HTML-teil des Logins
-->
<script src="<?php echo $rel; ?>Js/jquery-3.4.1.min.js"> </script>


<div id="particles-js"></div> <!-- Polygone im Hintergrund-->
<form class="input-box" action="" method="post" >

    <h1>Login</h1>

    <input type="text" placeholder= "Nutzername" name="username" required>
    <input type="password" placeholder= "Passwort" name="password" autocomplete="off" required>

    <button type="submit" name="login" value="1">Login</button>

    <center class="log-reg-ausgabe">
        <?php //feedback wenn nicht eingeloggt
            if ($login_erfolg == false) {
                echo 'Falscher Benutzername oder falsches Passwort <br>';
            }
        ?>
        Registriere dich  <a href="index.php?display=register"> hier</a>
    </center>

</form>
<!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
<script src="<?php echo $rel; ?>Js/particles.min.js"></script>
<script src="<?php echo $rel; ?>Js/app.js"></script>
<script>
  alert("Du befindest dich auf einer gespiegelten Seite (jpis.hopto.org/informatik), dein Handeln auf dieser Seite hat kein Einfluss auf das Orginal (jpis.hopto.org)");
</script>
