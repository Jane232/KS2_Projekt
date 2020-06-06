<?php
// written by @Jane232

//Backend-Admin-Verwaltung
session_start();
require_once("functions.php");
$con = db();
require_once($rel."config/useraccess.php");
// je nach ausgewählter Seite
if (isset($_GET['display'])) {
    if ($_GET['display'] == 'register') {
        require("register.php");
    } elseif ($_GET['display'] == 'edit') {
        require("pageregister.php");
    }
}

?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Admin"); ?>
<body>
	<?php require("{$rel}config/menu.php");?>
  <div class="pageBody">
	<br>
	<a href="admin.php"> <h1> Administrator-Seite </h1></a>
	<?php
  // je nach ausgewählter Seite
    if (isset($_GET['display'])) {
        if ($_GET['display'] == 'register') {
            echo "<div class='backend-user-register'>";
            require("register2.php");
            echo "</div>";
        } elseif ($_GET['display'] == 'edit') {
            require("pageregister2.php");
        }
    } else {//auswahl der Möglichkeiten
        echo '
        		<div class="backgroundkasten">
        			<center>
        			<table>
        				<tr>
        					<td><a href="admin.php?display=register"> Nutzer registrieren</a></td>
        					<td><a href="admin.php?display=edit"> Seite verlinken</a></td>
        				</tr>
        			</table>
        			</center>
        		</div>
          ';
    }
  ?>
  </div>
</body>

</html>
