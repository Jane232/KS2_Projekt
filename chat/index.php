<?php
// written by @Jane232

// Übersichtsseite aller registrierten Personen um Chats anzufangen
session_start();
require_once("../config/functions.php");
$con = db();
$user = logedIn();
?>
<!DOCTYPE html>
<html lang="de" data-theme ="<?php  echo htmlentities($_COOKIE['theme'], ENT_QUOTES, 'utf-8'); ?>">
<?php echo htmlHead($rel, "Chats", '<link rel="stylesheet" href="$rel/chat/chatstyle.css">'); ?>

<body>
<?php require("{$rel}config/menu.php");?>
<div class="pageBody">
<br>
<div class="Nutzeranzeigen">
<div class="backgroundkasten">
<div class="chatübersicht">
  <?php
    if (!isset($_GET['user'])) {
        //DB-Abfrage von allen Usern
        $stm = $con->prepare("SELECT * FROM users");
        $stm-> execute();
        $result = $stm->get_result();

        if ($result->num_rows === 0) {
            echo "Keine Nutzer vorhanden! <br>";
        } else {
            echo "<h2>Chatübersicht:</h2>";
            echo "<ul>";
            //Gobalchat
            echo "<li><a href= 'global.php'  >Globaler Chat</a></li>";
            while ($row = $result->fetch_assoc()) {
                $linktouser = $link."?user=".$row['name'];
                if ($row['name'] != $user) {
                    //link zu Privatchat
                    echo " <li><a href= 'privatechat.php?chat=".$row['name']."'>{$row['name']}</a></li>";
                } else {
                    // Chat an User selbst = "Notiz an mich selbst"
                    echo " <li><a href= 'privatechat.php?chat=".$row['name']."'>Notiz an mich selbst</a></li>";
                }
            }

            echo "</ul>";
        }
    }
  ?>
</div>
</div>
</div>
</div>
</body>

</html>
