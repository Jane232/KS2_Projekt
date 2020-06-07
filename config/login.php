<?php
// written by @Jane232

  // PHP-teil des Logins
  $login_erfolg = true;

  if (isset($_POST['username'])&&isset($_POST['password'])) { //check ob gesetzt
      if (checkCaptcha($_POST['captcha'])) {//Überprüft ob Captcha richtig eingegeben ist
          // DB-Abfrage des Passworts(gehasht) von user
          $stm = $con->prepare("SELECT password FROM users WHERE name=?;");
          $stm->bind_param("s", $_POST['username']);
          $stm-> execute();
          $result = $stm->get_result();
          $stm -> close();
          if ($result->num_rows === 0) { // wenn nicht vorhanden
              $login_erfolg = false;
          } else {
              $logedin = false;
              while ($row = $result->fetch_assoc()) {
                  if (password_verify($_POST['password'], $row['password'])) { // wenn Passwörter übereinstimmen
                      $logedin = true;
                      $_SESSION["user"] = $_POST['username'];
                      header("Location: index.php");
                  }
              }
          }
      } else {
          $captcha = false;
      }
  }
  $index = "1";
?>
<!--
written by @Jane232

HTML-teil des Logins
-->
<?php
$scale = (isset($_COOKIE['screenmode'])) ? ($_COOKIE['screenmode'] == "portrait") ? 2 : 1 : 1 ; // Je nach Bildschirmausrichtung (Handy/etc) wird das Bild doppelt so groß gemacht.
genCaptcha($_COOKIE['theme'], 3, $scale); // Generiert ein Bild mit 3 Buchstaben
 ?>
<script src="<?php echo $rel; ?>Js/jquery-3.4.1.min.js"> </script>


<div id="particles-js"></div> <!-- Polygone im Hintergrund-->
<form class="input-box" action="" method="post" >

    <h1>Login</h1>
    <?php
      if (isset($captcha)) {
          $userFill = 'value = "'.$_POST['username'].'"';
          $passFill = 'value = "'.$_POST['password'].'"';
      } else {
          $userFill = "";
          $passFill = "";
      }
    ?>
        <input type="text" placeholder= "Nutzername" name="username" <?php echo $userFill; ?> required>
        <input type="password" placeholder= "Passwort" name="password" <?php echo $passFill; ?>autocomplete="off" required>
        <!-- Captcha -->
        <input type="text" placeholder= "Captcha" name="captcha" autocomplete="off" required>
        <span id="captcha"><img src="data:image/png;base64,<?php echo $_SESSION["img"]; ?>" alt="Captcha"></span>

        <button type="submit" name="login" value="1">Login</button>

        <center class="log-reg-ausgabe">
            <?php
            //feedback wenn nicht eingeloggt
                if ($login_erfolg == false) {
                    echo 'Falscher Benutzername oder falsches Passwort <br>';
                } elseif (isset($captcha)) {
                    echo "Captcha falsch eingegeben!<br>";
                }
            ?>
            Registriere dich  <a href="index.php?display=register"> hier</a>
        </center>

</form>
<!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
<script src="<?php echo $rel; ?>Js/particles.min.js"></script>
<script src="<?php echo $rel; ?>Js/app.js"></script>
<script>
function readCookie(name) {
var nameEQ = name + "=";
var ca = document.cookie.split(';');
for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
}
return null;
}

if (readCookie("visited")!== "true") {
  alert("Du befindest dich auf einer gespiegelten Seite (jpis.hopto.org/informatik), dein Handeln auf dieser Seite hat kein Einfluss auf das Orginal (jpis.hopto.org)");
  document.cookie = "visited = true; ; path=/";
}
</script>
