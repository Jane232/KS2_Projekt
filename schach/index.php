<?php
// written by @Jane232

@session_start();
require_once("../config/functions.php");
$con = db();
$user = logedIn();
require_once($rel."chat/chatfunctions.php");

//Überprüfung ob chessID gesetzt ist
if (isset($_GET['chessID'])) {
    $chessID = $_GET['chessID'];
    // ChessID wird in ChatID-Format gebracht siehte chatfunctions.php chessID()
    // für Kommentarspalte
    $chatID = $_GET['chessID'];
    for ($i=0; $i < 20; $i++) {
        if (strlen($chatID)<16) {
            $chatID = "0".$chatID;
        } else {
            $_SESSION["chatID"] = $chatID;
            break;
        }
    }
} else {
    header("location: Schachübersicht.php");
}
//Überprüfung ob Spieler für Spiel zugelassen/eingetragen
if ($query = $con->prepare("SELECT ID FROM chess WHERE chessID =? AND (user1 = ? OR user2 = ?) ")) {
    $query->bind_param("iss", $chessID, $user, $user);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows === 0) {
        header("location: Schachübersicht.php");
    }
}

?>
<!DOCTYPE html>
<html lang="de" data-theme="<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Schach", '<link rel="stylesheet" href="$rel/chat/chatstyle.css"><link rel="stylesheet" href="$rel/schach/schach.css">'); ?>
<body>
<?php require("{$rel}config/menu.php"); ?>
<script type="text/javascript" charset="utf-8" src="../Js/jquery-3.4.1.min.js"></script>

<div class="pageBody">

    <div id="schach">
        <!-- initialDBRead ist die Datenbankabfrage um alle initalen Werte zu (über)schreiben -->
        <?php require_once("initialDBRead.php"); ?>
        <?php
        //Übertragung der Variablen in Js
        echo "
      <script>
        let user = '$user';
        let chessID = '$chessID';
        let activePlayerFromDB = '$activePlayerFromDB';
        let fieldStringFromDB = '$fieldStringFromDB';
        let user1 = '$user1';
        let user2 = '$user2';
        let numMovesFromDB = '$numMoves';
        let activeGame = '$activeGame';
        let dataWritten = false;
      </script>";

        ?>
        <center>
          <!-- Headline mit Namen des Gegners-->
          <div class="spielMit">
            <h1 ><a href="Schachübersicht.php">
              Spiel mit
            <?php
            // wenn der Gegner das Spiel gelöscht hat wird der Name aus der DB gelöscht dadurch wird dieses Spiel nicht mehr mit ihm in Verbindung gebracht, somit muss _deleted_ mit Gegner ersetzt werden
             if ($opponent != "_deleted_") {
                 echo $opponent;
             } else {
                 echo "Gegner";
             } ?>
           </a></h1>
          </div>

          <!-- Einbindung des Schachfelds-->
            <?php require("{$rel}schach/chess.php"); ?>

        </center>
    </div>
    </div>
<script type="text/javascript">
// wenn Spiel nicht mehr active (beendet) ist, soll die Möglichkeit bestehen bleiben das Spiel zu löschen
if (activeGame !== "active") {
  document.write("<br><center><a href='Schachübersicht.php?show=gameEnd&winner=_deleted_&chessID="+chessID+"'>Du wilst dieses Spiel löschen?</a></center> <br>");
}
</script>

<script type="text/javascript">
  // Setup um Seite neuzuladen (möglichst effizient)
  // Eine Interval-Funktion die alle 2s Abfragen am Server macht
  // 1. AJAX : Überprüfung ob numMoves geändert wurde
  // 2. AJAX : Überprüfung ob der letzte DB-Eintrag vom User kommt
  if (activePlayerFromDB === user && activeGame === "active") {
     dataWritten = true;
  }

  $(document).ready(function () {
      var inteval = setInterval(function () {
        if (dataWritten === true) {
          $.ajax({
              url: 'sendData.php',
              data: {task: "numMoves", chessID: chessID},
              success: function (data) {
                  if (numMovesFromDB !== data) {
                      $.ajax({
                          url: 'sendData.php',
                          data: {task: "readActiveUser", chessID: chessID},
                          success: function (data) {
                              if (data !== user) {
                                  window.location.reload();
                              } else {
                                  numMovesFromDB = numMovesFromDB + 1; // As the move is made
                              }
                          }
                      });
                  }
              }
          });
        }
      }, 2000);
  });


</script>
<!--Chat unter Schachfeld-->
<div class="chat">
  <?php echo chatBody($rel, "", "", "", 'input'); ?>
</div>
</body>

</html>
