<?php
// written by @Jane232

//Steitenvariablen:
$title = "Jan Weber";
?>
<?php
require_once("config/functions.php");
$con = db();
?>

<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<!-- data-theme ist ein Parameter der bestimmt ob Seite hell oder dunkel ist  -->
<?php
  // Wenn $_SESSION["user"] gesetzt ist (also user angemeldet war) wird er ausgeloggt -> session gelöscht
  session_start();

  if (isset($_GET['display'])) {
      if ($_GET['display'] == 'register') {
          require("config/register.php");
          $title = "Registrierung";
      } elseif ($_GET['display'] == 'login') {
          if (isset($_SESSION["user"])) {
              session_unset();
          }
          require("config/login.php");
          $title = "Log-in-Seite";
      } elseif ($_GET['display'] == 'logout') {
          if (isset($_SESSION["user"])) {
              session_unset();
              $_GET['display'] = "";
              echo "<script>
              sessionStorage.clear();
              window.location.assign('index.php');
              </script>";
          }
      } elseif ($_GET['display'] == 'account') {
          $title = "Account-Info";
      }
  }
?>

<?php echo htmlHead($rel, $title, '<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@1,600&display=swap" rel="stylesheet">'); ?>

<body>
  <?php require("config/menu.php"); ?>
  <?php
  // je nach dem ob Login oder Registrierung ausgewählt wurde
  if (isset($_GET['display'])) {
      if ($_GET['display'] == 'register') {
          require("config/register2.php");
      } elseif ($_GET['display'] == 'login') {
          require("config/login2.php");
      } elseif ($_GET['display'] == 'logout') {
          $_GET['display'] = "";
          require("config/homepage.php");
      } elseif ($_GET['display'] == 'account') {
          require("config/account.php");
      }
  } else {
      require("config/homepage.php");
  }
  ?>

</body>

</html>
