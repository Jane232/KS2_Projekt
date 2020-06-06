<?php
// written by @Jane232

require_once("../config/functions.php");
$con = db();
if (isset($_GET["chessID"]) && isset($_GET["task"]) || isset($_POST["chessID"]) && isset($_POST["task"])) {
    // Variablen werden übernommen und vorbereitet
    if (isset($_GET["chessID"]) && isset($_GET["task"])) {
        $chessID = $_GET["chessID"];
        $task = $_GET["task"];
    } else {
        $task = $_POST["task"];
        $chessID = $_POST["chessID"];
    }

    if (isset($_POST["fieldString1"])) {
        $fieldString1 = $_POST["fieldString1"];
        $fieldString2 = $_POST["fieldString2"];
        $fieldString3 = $_POST["fieldString3"];
        $fieldString4 = $_POST["fieldString4"];
        $fieldString5 = $_POST["fieldString5"];
        $fieldString = "";
        $fieldString .= $fieldString1;
        $fieldString .= $fieldString2;
        $fieldString .= $fieldString3;
        $fieldString .= $fieldString4;
        $fieldString .= $fieldString5;
    }
    if (isset($_GET["activePlayer"])) {
        $activePlayer = $_GET["activePlayer"];
    } elseif (isset($_POST["activePlayer"])) {
        $activePlayer = $_POST["activePlayer"];
    }

    // task wird aus der jeweiligen AJAX Funktion übergeben um die Aufgabe festzulegen.
    if ($task == "readActiveUser") {
        // Parameter activePlayer aus Datenbank lesen und zurückgeben
        $sql = "SELECT * FROM chess WHERE chessID = $chessID";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo $row["activePlayer"];
            }
        } else {
            echo "0 results";
        }
    } elseif ($task == "writeData") {
        // überschreiben der überholten Parameter in DB
        $tempMoves = $_POST["numMovesFromDB"] + 1;

        try {
            $stmt = $con->prepare("UPDATE chess SET activePlayer=? , fieldString = ?, numMoves = ? WHERE chessID= ?");
            if (!$stmt) {
                echo "Error updating record: " . $con->error;
            }
            $stmt->bind_param("ssii", $activePlayer, $fieldString, $tempMoves, $chessID);
            $stmt-> execute();
            $stmt-> close();
            echo "";
        } catch (Exception $e) {
            echo "Error updating record: " . $con->error;
        }
    } elseif ($task == "numMoves") {
        // lesen des Parameter numMoves aus DB
        $sql = "SELECT numMoves FROM chess WHERE chessID = $chessID";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo $row["numMoves"];
            }
        } else {
            echo "Problem";
        }
    }
}
