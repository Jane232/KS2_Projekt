<?php
// written by @Jane232

  @session_start();
  $title = "Login seite";

  $temprel="";
  while (!isset($rel)) {
      $temprel = "../".$temprel;
      require_once($temprel."config/functions.php");
  }
?>

<nav>
  <div class="logo">
    <h4><a href="<?php echo $rel; ?>index.php">Homepage</a></h4>
  </div>
    <ul class="nav-links">
      <?php

      //wenn eingeloggt dann menüpunkte zeigen
        if (isset($_SESSION["user"])) {
            echo '<li> <a href="'.$rel.'schach/index.php"> Schach</a> </li>';
            echo '<li> <a href="'.$rel.'chat/index.php"> Chat</a> </li>';
            echo '<li> <a href="'.$rel.'../v10/index.php?display=logout">Orginal-Webseite</a> </li>';
            echo '<li> <div class="tooltip"><a href="'.$rel.'index.php?display=account"> Account </a><span class="tooltiptextEinstellungen"> Eingeloggt als: '.$_SESSION["user"].' </span> </div>  </li>';
        } else { // für richtiges spacing
            echo '<li> </li>';
            echo '<li> </li>';
            echo '<li> <a href="'.$rel.'../v10/index.php?display=logout">Orginal-Webseite</a> </li>';
            echo '<li> <a href= "'.$rel.'index.php?display=login"> LogIn </a> </li>' ;
        }
       ?>
      <li> <?php require("{$rel}config/Switch.php"); ?> </li>
    </ul>

    <div class="burger"> <!-- wenn Fenster klein, dann Burgermenü an der Seite -->
        <div class="line1"> </div>
        <div class="line2"> </div>
        <div class="line3"> </div>
    </div>

</nav>
<!--JS für Burger-Animation -->
<script src="<?php echo $rel;?>Js/nav.js"></script>
