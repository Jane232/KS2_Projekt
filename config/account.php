<?php
// written by @Jane232

// aufgerufen bei: index.php?display=account
// Übersicht für User über Einstellungen
$user = logedIn();
?>
<div class="pageBody">
<br>
<div class="backgroundkasten">
<h1 style="text-decoration: underline;">Account-Übersicht:</h1>
<br>
  <div class="text1">
    <center>
    <table style="width: 75%; ">
      <tr>
        <td style="text-align:center; width: 33%;"><a href="config/seiten.php"> Übersicht</a></td>
        <td style="text-align:center; width: 33%;"> <div class="tooltip"> <a href="config/userSettings.php"> ⚙️</a> <span class="tooltiptextEinstellungen">Einstellungen </span> </div></td>
        <td style="text-align:center; width: 33%;"><div class="tooltip"> <a href= "index.php?display=logout"> LogOut </a> <span class="tooltiptext"> Eingeloggt als: <?php echo $_SESSION["user"];?> </span> </div></td>
      </tr>
    </table>
  </center>
  <br>
  </div>
</div>

<p class="ende"></p>
</div>
