<?php
// written by @Jane232

//Backendseite: Übersicht aller vergebenen Seiten-Rechte
require_once("../config/functions.php");
$con = db();
?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Übersicht"); ?>

<body>
<?php require("{$rel}config/menu.php");?>
<div class="pageBody">
<br>
<hr>

<div class="backgroundkasten">

<?php

    // Auflistung aller registrierten Seiten mit zugehörigem User
    $stm = $con->prepare("SELECT * FROM pages");
    $stm-> execute();
    $result = $stm->get_result();

    if ($result->num_rows === 0) {
        echo "Keine Seiten vorhanden! <br>";
    } else {
        echo "<h1>Registrierte Seiten:</h1>";
        echo "<center><table> <tr> <td>Nutzer</td> <td>Seite</td>  </tr>";
        while ($row = $result->fetch_assoc()) {
            echo " <tr>
                      <td>{$row['access']}</td>
                      <td>{$row['pagename']}</td>
                    </tr> ";
        }
        echo "</table></center>";
    }
 ?>
</div>

<p class="ende"></p>
</div>
</body>

</html>
