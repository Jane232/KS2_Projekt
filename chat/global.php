<?php
// written by @Jane232

//Gruppe mit allen Usern (ChatID = 1)
session_start();
require_once("../config/functions.php");
$con = db();
$user = logedIn();
require_once($rel."chat/chatfunctions.php");
?>

<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo htmlentities($_COOKIE['theme'], ENT_QUOTES, 'utf-8'); ?>">
<?php echo htmlHead($rel, "Chat", '<link rel="stylesheet" href="$rel/chat/chatstyle.css">'); ?>

<body>
  <?php require($rel."/config/menu.php");?>
  <div class="pageBody">

  <div class="backgroundkasten">
    <?php  $_SESSION["chatID"] = 1; // festgelegste ChatID da keine userabhänge ID generiert wird
    echo chatBody($rel, "", "", "Global Chat:"); ?>
  </div>
</div>
</body>

</html>
