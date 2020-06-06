<?php
// written by @Jane232

// Übersichtsseite der Einstellungen
@session_start();
require_once("functions.php");
$user = logedIn();

if (isset($_GET['show'])) {
    if ($_GET['show'] == "deleteUser") {
        $title = "Account löschen";
    } elseif ($_GET['show'] == "changePassword") {
        $title = "Passwort ändern";
    }
} else {
    $title = "Einstellungen";
}
?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, $title); ?>

<body>
<?php require($rel."config/menu.php"); ?>
<div class="pageBody">
<br>
<script src="<?php echo $rel; ?>Js/jquery-3.4.1.min.js"> </script>
<?php
if (isset($_GET['show'])) {
    if ($_GET['show'] == "changePassword") {// Passwort ändern
        if (isset($_POST['password'])) {
            $changePassword = false;
            $stm = $con->prepare("SELECT password FROM users WHERE name=?");
            $stm->bind_param("s", $_SESSION["user"]);
            $stm-> execute();
            $result = $stm->get_result();
            $stm -> close();

            while ($row = $result->fetch_assoc()) {
                if (password_verify($_POST['password'], $row['password'])) { // wenn Passwörter übereinstimmen
                    $changePassword = true;
                } else {
                    $passwortFasch = true;
                }
            }

            if ($changePassword) {
                if ($_POST['newPassword'] == $_POST['newPassword2']) {
                    $password = $_POST['newPassword'];
                    $hashed = password_hash($password, PASSWORD_DEFAULT); // neues Passwort hashen
                } else {
                    $passwordinkorrekt = true;
                }
                if (isset($hashed)) {
                    try {
                        // Neues Passwort in DB schreiben
                        $stm = $con->prepare("UPDATE users SET password = ? WHERE name = ?");
                        if (!$stm) {
                            echo "<p>Fehler!</p>". $con -> error;
                        }
                        $stm->bind_param("ss", $hashed, $_SESSION["user"]);
                        $stm-> execute();
                        $stm -> close();
                        $success = true;
                    } catch (Exception $e) {
                        echo "<p>Fehler bei Passwortänderung</p>";
                    }
                }
            }
        }
        // HTML - Teil der Seite
        echo '<div id="particles-js"></div>
        <!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
        <script src="'.$rel.'Js/particles.min.js"></script>
        <script src="'.$rel.'Js/app.js"></script>';

        echo " <center> <form class= 'input-box' action= '{$link}' method= 'post' where= 'register'>";
        echo "
          <h1>Passwort ändern</h1>
          <br>
          <input type= 'password' placeholder= 'altes Passwort'         name= 'password'       autocomplete= 'off' where='register' required>
          <input type= 'password' placeholder= 'neues Passwort'         name= 'newPassword'    autocomplete= 'off' where='register' required>
          <input type= 'password' placeholder='Passwort verifizieren'   name= 'newPassword2'   autocomplete= 'off' where='register' required>

          <button type='submit' name='login'>Passwort ändern!</button>";

        if (isset($passwortFasch)) {// User Feedback
            echo "altes Passwort falsch! <br>";
        } elseif (isset($passwordinkorrekt)) {
            echo "Die neuen Passwörter stimmen nicht überein <br>";
        } elseif (isset($success)) {
            echo "Passwort erfolgreich geändert <br>";
        }

        echo "<br><a href='userSettings.php'> zurück zu den Einstellungen</a>
         </form> </center> ";
    } elseif ($_GET['show'] == "deleteUser") { // Account löschen
        if (isset($_POST['password'])) {
            $deleteUser = false;
            $stm = $con->prepare("SELECT password FROM users WHERE name=?;");
            $stm->bind_param("s", $_SESSION["user"]);
            $stm-> execute();
            $result = $stm->get_result();
            $stm -> close();

            while ($row = $result->fetch_assoc()) {
                if (password_verify($_POST['password'], $row['password'])) { // wenn Passwörter übereinstimmen
                    $deleteUser = true;
                } else {
                    $passwortFasch = true;
                }
            }

            if ($deleteUser) {
                $stm = $con->prepare("DELETE FROM users WHERE name=?;"); // User aus DB Löschen
                $stm->bind_param("s", $_SESSION["user"]);
                $stm-> execute();
                $stm -> close();
                header("location: ../index.php?display=logout");
            }
        }
        // HTML - Teil der Seite
        echo '<div id="particles-js"></div>
        <!-- Hintergrundanimation von https://vincentgarreau.com/particles.js/ -->
        <script src="'.$rel.'Js/particles.min.js"></script>
        <script src="'.$rel.'Js/app.js"></script>';

        echo " <center> <form class= 'input-box' action= '{$link}' method= 'post' where= 'register'>";
        echo "
      <h1>Benutzerkonto löschen?</h1>
      <br>
      Dieser Schritt ist <u>unumkehrbar</u>!
      <br><br>
      Passwort zur Bestätigung eingeben:
      <input type= 'password' placeholder= 'Passwort' name= 'password' autocomplete= 'off' required>
      <button type='submit' name='login'>Konto löschen!</button>";

        if (isset($passwortFasch)) {// User Feedback
            echo "Passwort falsch! <br>";
        }

        echo "<a href='userSettings.php'> Löschen abbrechen</a>
     </form> </center> ";
    }
} else {
    //Einstellungen (Verlinkungen zu Subseiten)
    echo "
    <div class='backgroundkasten'>
      <h1>Einstellungen</h1>
      <ul class='liste'>
        <li>Passwort <a href='userSettings.php?show=changePassword'>ändern</a></li>
        <li>Konto <a href='userSettings.php?show=deleteUser'>löschen</a> </li>
        <li>Deine aktuelle Ip-Adresse lautet: ".htmlentities($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'utf-8')." </li>
      </ul>
  </div>";
}
 ?>


<p class="ende"></p>
</div>
</body>
</html>
