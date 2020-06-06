<?php
// written by @Jane232

  // PHP-teil des Logins
  $login_erfolg = true;

  if (isset($_POST['username'])&&isset($_POST['password'])) { //check ob gesetzt
      // DB-Abfrage des Passworts(gehasht) von user
      $stm = $con->prepare("SELECT password FROM users WHERE name=?;");
      $stm->bind_param("s", $_POST['username']);
      $stm-> execute();
      $result = $stm->get_result();
      $stm -> close();
      if ($result->num_rows === 0) { // wenn nicht vorhanden
          $login_erfolg = false;
      } else {
          $logedin = false;
          while ($row = $result->fetch_assoc()) {
              if (password_verify($_POST['password'], $row['password'])) { // wenn Passwörter übereinstimmen
                  $logedin = true;
                  $_SESSION["user"] = $_POST['username'];
                  header("Location: index.php");
              }
          }
      }
  }
  $index = "1";
