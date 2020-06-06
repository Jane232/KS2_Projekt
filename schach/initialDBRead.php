<?php
// written by @Jane232

// Alle wichtigen Parameter von dem Spiel werden in aus der Patenbank in Variablen geschrieben
require_once("../config/functions.php");
$con = db();
$sql = "SELECT * FROM chess WHERE chessID = $chessID";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fieldStringFromDB = $row["fieldString"];
        $activePlayerFromDB = $row["activePlayer"];
        $user1 = $row["user1"];
        $user2 = $row["user2"];
        $numMoves = $row["numMoves"];
        if ($row['user1'] == $user) {
            $opponent = $row['user2'];
        } else {
            $opponent = $row['user1'];
        }
        $activeGame = $row['activeGame'];
    }
} else {
    header("Location: Schachübersicht.php");
}
