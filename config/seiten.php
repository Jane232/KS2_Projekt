<?php
// written by @Jane232

// Übersichtsseite aller verfügbaren/freigeschalteten Seiten
@session_start();
require_once("functions.php");
$con = db();
$user = logedIn();
?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Übersicht"); ?>

<body>
<?php require("{$rel}config/menu.php"); ?>
<div class="pageBody">
<br>
<div class="backgroundkasten">
  <div class="text1">
    <?php
      // ul mit allen freigeschalteten Seiten (als Links) oder "Du hast noch keine personalisierten Seiten!"
      $stm = $con->prepare("SELECT * FROM pages WHERE access=? AND display= '1'");
      $stm->bind_param("s", $user);
      $stm-> execute();
      $result = $stm->get_result();

      if ($result -> num_rows === 0) {
          echo "<p> Du hast noch keine personalisierten Seiten! </p>";
      } elseif ($result -> num_rows > 1) {
          echo "<h2>Deine personalisierten Seiten sind: </h2> ";
          echo "<ul>";
          while ($i = $result->fetch_assoc()) {
              echo "<li> <a href={$rel}{$i["link"]}> ". htmlspecialchars(stripslashes(trim($i["pagename"]))) ."</li>";
          }
          echo "</ul>";
      } elseif ($result -> num_rows > 0) {
          while ($i = $result -> fetch_assoc()) {
              header("location: {$rel}{$i["link"]}");
          }
      }

    ?>
  </div>
</div>

<p class="ende"></p>
</div>
</body>
</html>
