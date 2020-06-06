<?php
// written by @Jane232

// Übersichtsseite für Alle alten und neuen Schachspiele // gleichzeitig noch eine "Verarbeitungsseite" für verschiede Aufgaben
@session_start();
require_once("../config/functions.php");
$con = db();
$user = logedIn();

?>
<!DOCTYPE html>
<html lang="de" data-theme = "<?php echo $_COOKIE['theme']; ?>">
<?php echo htmlHead($rel, "Schachübersicht", '<link rel="stylesheet" href="$rel/schach/schach.css">'); ?>

<body>
<?php require("{$rel}config/menu.php");?>
<div class="pageBody">
<br>
<div class="backgroundkasten">
<div class="schachÜbersicht">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function checkIfplayerDeleted($con, $opponent, $chessID)
{
    if ($check = $con->prepare("SELECT ID FROM users WHERE name = ?")) {
        $check->bind_param("s", $opponent);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows === 0) {
            $sql = "UPDATE chess SET activeGame = 'inactive' WHERE chessID = $chessID";
            if ($con->query($sql) !== true) {
                return "Error updating record: " . $con->error;
            }
            header('Location: '.trim(htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'utf-8'), './'));
        }
    } else {
        $error = $con->errno . ' ' . $con->error;
        return $error;
    }
    $check -> close();
}
function opponent($user, $user1, $user2)
{
    if ($user1 == $user) {
        $opponent = $user2;
    } else {
        $opponent = $user1;
    }
    return $opponent;
}

if (isset($_GET['show'])) {
    if ($_GET['show'] == "newGame") {// Seite um neues Spiel zu starten
        $stm = $con->prepare("SELECT * FROM users");
        $stm-> execute();
        $result = $stm->get_result();
        // Query sucht alle Spieler (User)
        if ($result->num_rows === 0) {
            echo "Keine Nutzer vorhanden! <br>";
        } else {
            echo "<h2>Mit diesen Nutzern kannst du ein Spiel starten:</h2>";
            echo "<ul>";

            while ($row = $result->fetch_assoc()) {
                if ($row['name'] != $user) {// Alle user werden angezeigt bis auf der suchende
                    echo " <li><a href= 'Schachübersicht.php?gameStart=".$row['name']."'>{$row['name']}</a></li>";
                    // link auf Zwischenseite die das Spiel konfiguriert
                }
            }

            echo "</ul>";
        }
        echo "<br>";
        echo "<a href= 'Schachübersicht.php'>zurück zur Übersicht</a>";
    } elseif ($_GET['show'] == "gameEnd") {// Endscreen nach dem Alert, um weitere Zukunft des Spieles zu bestimmen
        if (isset($_GET['chessID']) && isset($_GET['winner'])) {
            $chessID = $_GET['chessID'];
            $winner = $_GET['winner'];
            // Gamestatus wird auf "inactive" gesetzt, um von aktive Spiele in Alte Spiele zu rutschen
            $sql = "UPDATE chess SET activeGame = 'inactive' WHERE chessID= $chessID";
            if ($con->query($sql) === true) {
                echo "";
            } else {
                echo "Error updating record: " . $con->error;
            }
            // Textausgabe ja nach State varierend
            $draw = "_draw_";
            $deleted= "_deleted_";
            if ($winner != $deleted) {
                if ($winner != $draw) {
                    if ($winner == "_deleted_") {
                        echo "<h2>Der Gegner hat das Spiel #$chessID gewonnen</h2>";
                    } else {
                        echo "<h2>$winner hat das Spiel #$chessID gewonnen</h2>";
                    }
                } else {
                    echo "<h2>Das Spiel #$chessID wurde mit einem Patt beendet</h2>";
                }
                echo "  Willst du dieses Spiel <a href='Schachübersicht.php?show=gameDelete&chessID=$chessID'>löschen</a>? <br>
                    Das Spiel wird standardmäßig gespeichert. <br> <br>";
                echo "<a href= 'Schachübersicht.php'>zurück zur Übersicht</a>";
            } else {
                echo "<center> Willst du dieses Spiel <a href='Schachübersicht.php?show=gameDelete&chessID=$chessID'>löschen</a>?  <br> <br>
                <a href= 'Schachübersicht.php'>zurück zur Übersicht</a></center>";
            }
        }
    } elseif ($_GET['show'] == "gameDelete") {// wenn Spiel für User gelöscht werden soll
        if (isset($_GET['chessID'])) {
            $chessID = $_GET['chessID'];
            // Ersetztung des Usernamen in der DB durch "_deleted_"
            // dadurch wird er nicht mehr angezeigt
            $stm = $con->prepare("SELECT user1 FROM chess WHERE chessID=?;");
            $stm->bind_param("i", $chessID);
            $stm-> execute();
            $result = $stm->get_result();
            $stm -> close();
            while ($i = $result->fetch_assoc()) {
                $user1 = $i["user1"];
            }
            if ($user1 == $user) {
                $sql = "UPDATE chess SET user1 = '_deleted_' WHERE chessID= $chessID";
                if ($con->query($sql) === true) {
                    echo "";
                } else {
                    echo "Error updating record: " . $con->error;
                }
            } else {
                $sql = "UPDATE chess SET user2 = '_deleted_' WHERE chessID= $chessID";
                if ($con->query($sql) === true) {
                    echo "";
                } else {
                    echo "Error updating record: " . $con->error;
                }
            }
        }
        header("Location: Schachübersicht.php");
    }
} elseif (isset($_GET['gameStart'])) { // Zwischenseite üm Spiel mit $_GET['gameStart'] zu starten
    $user2 = $_GET['gameStart']; // Gegner wird aus URL gelesen

    $fieldString = '{"fields":[[{"row":1,"column":1,"figure":{"unicode":"♖","figureType":1,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"A1"},{"row":1,"column":2,"figure":{"unicode":"♘","figureType":2,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C3"},{"row":3,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A3"}]},"realName":"B1"},{"row":1,"column":3,"figure":{"unicode":"♗","figureType":3,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"C1"},{"row":1,"column":4,"figure":{"unicode":"♕","figureType":4,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"D1"},{"row":1,"column":5,"figure":{"unicode":"♔","figureType":5,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"E1"},{"row":1,"column":6,"figure":{"unicode":"♗","figureType":3,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"F1"},{"row":1,"column":7,"figure":{"unicode":"♘","figureType":2,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H3"},{"row":3,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F3"}]},"realName":"G1"},{"row":1,"column":8,"figure":{"unicode":"♖","figureType":1,"color":{"isWhiteVar":true},"hittableFields":[]},"realName":"H1"}],[{"row":2,"column":1,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A3"},{"row":4,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A4"}]},"realName":"A2"},{"row":2,"column":2,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B3"},{"row":4,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B4"}]},"realName":"B2"},{"row":2,"column":3,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C3"},{"row":4,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C4"}]},"realName":"C2"},{"row":2,"column":4,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D3"},{"row":4,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D4"}]},"realName":"D2"},{"row":2,"column":5,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E3"},{"row":4,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E4"}]},"realName":"E2"},{"row":2,"column":6,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F3"},{"row":4,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F4"}]},"realName":"F2"},{"row":2,"column":7,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G3"},{"row":4,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G4"}]},"realName":"G2"},{"row":2,"column":8,"figure":{"unicode":"♙","figureType":0,"color":{"isWhiteVar":true},"hittableFields":[{"row":3,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H3"},{"row":4,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H4"}]},"realName":"H2"}],[{"row":3,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A3"},{"row":3,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B3"},{"row":3,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C3"},{"row":3,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D3"},{"row":3,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E3"},{"row":3,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F3"},{"row":3,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G3"},{"row":3,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H3"}],[{"row":4,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A4"},{"row":4,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B4"},{"row":4,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C4"},{"row":4,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D4"},{"row":4,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E4"},{"row":4,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F4"},{"row":4,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G4"},{"row":4,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H4"}],[{"row":5,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A5"},{"row":5,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B5"},{"row":5,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C5"},{"row":5,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D5"},{"row":5,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E5"},{"row":5,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F5"},{"row":5,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G5"},{"row":5,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H5"}],[{"row":6,"column":1,"figure":{"unicode":"","figureType":null},"realName":"A6"},{"row":6,"column":2,"figure":{"unicode":"","figureType":null},"realName":"B6"},{"row":6,"column":3,"figure":{"unicode":"","figureType":null},"realName":"C6"},{"row":6,"column":4,"figure":{"unicode":"","figureType":null},"realName":"D6"},{"row":6,"column":5,"figure":{"unicode":"","figureType":null},"realName":"E6"},{"row":6,"column":6,"figure":{"unicode":"","figureType":null},"realName":"F6"},{"row":6,"column":7,"figure":{"unicode":"","figureType":null},"realName":"G6"},{"row":6,"column":8,"figure":{"unicode":"","figureType":null},"realName":"H6"}],[{"row":7,"column":1,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"A7"},{"row":7,"column":2,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"B7"},{"row":7,"column":3,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"C7"},{"row":7,"column":4,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"D7"},{"row":7,"column":5,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"E7"},{"row":7,"column":6,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"F7"},{"row":7,"column":7,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"G7"},{"row":7,"column":8,"figure":{"unicode":"♟","figureType":0,"color":{"isWhiteVar":false}},"realName":"H7"}],[{"row":8,"column":1,"figure":{"unicode":"♜","figureType":1,"color":{"isWhiteVar":false}},"realName":"A8"},{"row":8,"column":2,"figure":{"unicode":"♞","figureType":2,"color":{"isWhiteVar":false}},"realName":"B8"},{"row":8,"column":3,"figure":{"unicode":"♝","figureType":3,"color":{"isWhiteVar":false}},"realName":"C8"},{"row":8,"column":4,"figure":{"unicode":"♛","figureType":4,"color":{"isWhiteVar":false}},"realName":"D8"},{"row":8,"column":5,"figure":{"unicode":"♚","figureType":5,"color":{"isWhiteVar":false}},"realName":"E8"},{"row":8,"column":6,"figure":{"unicode":"♝","figureType":3,"color":{"isWhiteVar":false}},"realName":"F8"},{"row":8,"column":7,"figure":{"unicode":"♞","figureType":2,"color":{"isWhiteVar":false}},"realName":"G8"},{"row":8,"column":8,"figure":{"unicode":"♜","figureType":1,"color":{"isWhiteVar":false}},"realName":"H8"}]],"gameState":0,"activePlayerColor":{"isWhiteVar":true},"isCheck":false,"numPossibleMoves":20}';
    //$fieldString ist das standard Schachbrett

    try {

        //Eintragung der beiden Spieler, activePlayer ist wer den letzten Eintrag gemacht hat, und fieldString ist das standard Schachbrett
        $stmt = $con->prepare("INSERT INTO chess (user1, user2 , activePlayer, fieldString,activeGame) VALUES (? , ? , ? , ?, ?)");
        if ($stmt) {
            $tmp = "active";
            $stmt->bind_param("sssss", $user, $user2, $user2, $fieldString, $tmp);
            $stmt->execute();
        } else {
            echo("Error description (INSERT): " . $con -> error);
        }
        $chessID = $con->insert_id;

        // chessID ist die unique ID des speziellen Spiels (AutoIncrement)
        $stmt -> close();
        header("Location: index.php?chessID=".$chessID);
        //Weiterleitung an dieses Spiel
    } catch (mysqli_sql_exception $e) {
        echo $e->__toString();
    }
} else {// Allen anderen Anfragen wird die Standardseite zurückgegeben

    // Übersicht aller Spiele die der User am Laufen hat in umgekehrter Reinfolge
    if ($query = $con->prepare("SELECT * FROM chess WHERE (user1 = ? OR user2 = ?) AND activeGame = 'active' ORDER BY chessId DESC")) {
        // SELECT wenn user == user1||user2
        $query->bind_param("ss", $user, $user);
        $query->execute();
        $result = $query->get_result();
    } else {
        $error = $con->errno . ' ' . $con->error;
        echo $error;
    }

    echo "<h2>Spiel-Übersicht:</h2>";
    //anzeigen aller Spiele / oder "Keine Spiele vorhanden!"
    if ($result->num_rows === 0) {
        echo "Keine aktiven Spiele gefunden! <br>
        klicke unten um ein neues Spiel zu starten <br>";
    } elseif ($result->num_rows === 1) {
        // Anzeigen wenn einzelnes Spiel gefunden
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $opponent  = opponent($user, $row['user1'], $row['user2']);

            echo checkIfplayerDeleted($con, $opponent, $row['chessID']);
            echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel mit {$opponent}</a></li>";
        }
        echo "</ul>";
    } else {
        // Anzeigen wenn mehr als ein Spiel gefunden wurde
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $opponent  = opponent($user, $row['user1'], $row['user2']);
            echo checkIfplayerDeleted($con, $opponent, $row['chessID']);
            echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel #{$row['chessID']}  mit {$opponent}</a></li>";
            //link zu Spiel mit Nummer
        }
        echo "</ul>";
    }

    echo "<br>";
    //Alle alten Spiele ("inactive") und noch nicht gelöscht
    echo "<h2>Deine alten Spiele:</h2>";
    if ($query = $con->prepare("SELECT * FROM chess WHERE (user1 = ? OR user2 = ?) AND activeGame = 'inactive' ORDER BY chessId DESC")) {
        // SELECT wenn user == user1||user2
        $query->bind_param("ss", $user, $user);
        $query->execute();
        $result = $query->get_result();
    } else {
        $error = $con->errno . ' ' . $con->error;
        echo $error;
    }

    if ($result->num_rows === 0) {
        echo "Keine alten Spiele gefunden! <br>";
    } elseif ($result->num_rows === 1) {
        // Anzeigen wenn einzelnes Spiel gefunden
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $opponent  = opponent($user, $row['user1'], $row['user2']);

            if ($opponent !== "_deleted_") {
                echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel #{$row['chessID']}  mit {$opponent}</a></li>";
            } else {
                echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel #{$row['chessID']} </a></li>";
            }
        }
        echo "</ul>";
    } else {
        // Anzeigen wenn mehr als ein Spiel gefunden wurde
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $opponent  = opponent($user, $row['user1'], $row['user2']);


            if ($opponent !== "_deleted_") {
                echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel #{$row['chessID']}  mit {$opponent}</a></li>";
            } else {
                echo " <li><a href= 'index.php?chessID=".$row['chessID']."'>Spiel #{$row['chessID']} </a></li>";
            }

            //link zu Spiel mit Nummer
        }
        echo "</ul>";
    }

    echo "<br><a href= 'Schachübersicht.php?show=newGame'>Neues Spiel erstellen</a>";
}


?>
</div>
</div>
</div>
</body>

</html>
