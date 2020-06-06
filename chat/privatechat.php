<?php
// written by @Jane232

// Privatchat zwischen user und $chatpartner
session_start();
require_once("../config/functions.php");
$con = db();
$user = logedIn();
require_once($rel."chat/chatfunctions.php");

if (isset($_GET['chat'])) {
    $chatInit = chatInit($con, $rel, $_GET['chat']);
    $chatID = chatID($chatInit[0], $chatInit[1]);
    $chatpartner = $chatInit[2];
}
?>

<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo htmlentities($_COOKIE['theme'], ENT_QUOTES, 'utf-8'); ?>">
<?php
if ($chatpartner != $user) {
    $title = " Chat mit {$chatpartner}";
} else {
    $title = " Notiz an mich selbst";
}
echo htmlHead($rel, $title, '<link rel="stylesheet" href="$rel/chat/chatstyle.css">');
?>
  <body>
    <?php require($rel."/config/menu.php");?>
    <div class="pageBody">
      <div class="backgroundkasten">
         <?php echo chatBody($rel, $user, $chatpartner); ?>
      </div>
    </div>
  </body>
</html>

<?php
  $con -> close();
?>
