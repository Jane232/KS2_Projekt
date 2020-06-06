<?php
// written by @Jane232

session_start();
require_once("../config/functions.php");
$con = db();
require_once($rel."chat/chatfunctions.php");

if (isset($_SESSION["user"]) && isset($_SESSION["chatID"])) {
    $chatID = $_SESSION["chatID"];
    $user = $_SESSION["user"];
    //chatID und Sender der Nachricht aus Session holen um später in Db einzutragen
}

if (isset($_GET['message']) && !empty($_GET['message'])) { //wenn Nachricht nicht leer ist, dann:
    $message = $_GET['message']; //übertragen von Nachricht von sendchat.js
    $chatID = $_SESSION["chatID"];
    if (isset($chatID)) {
        if (send_msg($con, $chatID, $user, $message)) { //Nachricht wird mit funktion gesendet
                echo "Nachricht gesendet";//Feedback für user
        } else {
            echo "Nachricht konnte nicht gesendet werden";//Feedback für user
        }
    } else {
        echo "Kein Chat ausgewählt";//Feedback für user
    }
} else {
    echo "Noch keine Nachricht eingegeben";//Feedback für user
}
