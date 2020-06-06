<?php
// written by @Jane232

// Backendseite: Liste aller User
require_once("../config/functions.php");
$con = db();
?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Registrierte Nutzer"); ?>

<body>
<?php require("{$rel}config/menu.php");?>
<div class="pageBody">
<br>

<div class="backgroundkasten">
  <div class="Nutzeranzeigen">

<?php
// Liste aller User mit DB Abfrage
  if (!isset($_GET['user'])) {
      $stm = $con->prepare("SELECT * FROM users");
      $stm-> execute();
      $result = $stm->get_result();

      if ($result->num_rows === 0) {
          echo "Keine Nutzer vorhanden! <br>";
      } else {
          echo "<h1>Registrierte Nutzer:</h1>";
          echo "<center><table> <tr> <td>UserID</td> <td>Username</td>  </tr>";
          while ($row = $result->fetch_assoc()) {
              $linktouser = $link."?user=".$row['name'];
              echo " <tr>
                        <td>{$row['ID']}</td>
                        <td><a href= $linktouser >{$row['name']}</a></td>
                      </tr> ";
          }

          echo "</table></center>";
      }
  } else {
      $stm = $con->prepare("SELECT * FROM pages WHERE access=?;");
      $stm->bind_param("s", $_GET['user']);
      $stm-> execute();
      $result = $stm->get_result();

      echo "<div class='liste'>";
      echo "<div class='goback'> <a href='userlist.php'>zurück zur Übersicht</a> </div>";

      if ($result->num_rows === 0) {
          echo "Dieser Nutzer hat noch keine Seiten freigeschaltet!<br>";
      } else {
          echo "Der User ".$_GET['user']." ist für folgende Seiten freigeschaltet: <br>";


          echo "<table><ul>";
          while ($row = $result->fetch_assoc()) {
              echo "<tr><td><li> <a href={$rel}{$row['link']}>{$row['pagename']}</a> </li></td></tr>";
          }
          echo "</ul></table>";
      }
      echo "</div>";
  }

?>

</div>
</div>
</div>
</body>

</html>
