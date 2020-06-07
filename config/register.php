<?php
// written by @Jane232

// PHP-Teil der Registrierungsseite

if (isset($_POST['username'])) {
    if (checkCaptcha($_POST['captcha'])) {//Überprüft ob Captcha richtig eingegeben ist
        if ($_POST['password'] == $_POST['password2']) {
            // Varablen übernehmen
            $username = $_POST['username'];
            $password = $_POST['password'];
            // eingegebenes Passwort hashen
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // check ob User bereits erstellt
            $stm = $con->prepare("SELECT * FROM users WHERE name=?;");
            $stm->bind_param("s", $username);
            $stm-> execute();
            $result = $stm->get_result();

            if ($result->num_rows === 0) {
                try {
                    $stm = $con->prepare("INSERT INTO users (name , password, entrydate) VALUES (?, ?, NOW())");
                    if (!$stm) {
                        echo "<p>Fehler!</p>". $con -> error;
                    }
                    $stm->bind_param("ss", $username, $hashed);
                    $stm-> execute();
                    $stm -> close();
                    $registriert = true;
                } catch (Exception $e) {
                    echo "<p>Fehler!</p>". $e;
                }
            } else {
                $nutzerverg = true;
            }
        } else {
            $passwordinkorrekt = true;
        }
    } else {
        $captcha = false;
    }
}
?>
<!--
 written by @Jane232
HTML-Teil der Registrierungsseite
-->
<?php
$scale = (isset($_COOKIE['screenmode'])) ? ($_COOKIE['screenmode'] == "portrait") ? 1.5 : 1 : 1 ;
genCaptcha($_COOKIE['theme'], 5, $scale);
 ?>
<div id="particles-js"></div>
<form class="input-box" action="<?php $link ?>" method="post" where="register">

  <h1> Registrierung </h1>
  <!-- Inputform für Registrierung-->
  <input type="text" placeholder="Nutzername" name="username" where="register" autocomplete="off" required>
  <input type="password" placeholder="Passwort" name="password" where="register" autocomplete="off" required>
  <input type="password" placeholder="Passwort verifizieren" where="register" name="password2" autocomplete="off" required>
  <!-- Captcha -->
    <input type="text" placeholder= "Captcha" name="captcha" autocomplete="off" required>
    <span id="captcha"><img src="data:image/png;base64,<?php echo $_SESSION["img"]; ?>" alt="Captcha"></span>

  <button type="submit" name="login" value="1" where="register">Registrieren</button>

  <center class="log-reg-ausgabe">
    <?php
      //Output (Feedback)

      if (isset($registriert)) {
          echo "erfolgreich registriert<br> ";
      } elseif (isset($captcha)) {
          echo "Captcha falsch eingegeben!<br>";
      } elseif (isset($_POST['username']) && isset($passwordinkorrekt)) {
          echo "Passwörter stimmen nicht überein <br>";
      } elseif (isset($nutzerverg)) {
          echo "Nutzername ist bereits vergeben!<br>";
      }
    ?>
    Zurück zum <a href=<?php echo $rel; ?>"index.php?display=login"> Login</a>
  </center>

</form>
<!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
<script src="<?php echo $rel; ?>Js/particles.min.js"></script>
<script src="<?php echo $rel; ?>Js/app.js"></script>
<script src="<?php echo $rel; ?>Js/jquery-3.4.1.min.js"> </script>
