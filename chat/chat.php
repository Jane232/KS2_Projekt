<?php
// written by @Jane232
// Teil des Nachrichten-Ausgebe / -Anzeige-Systems
require_once("../config/functions.php");
$con = db();
require_once($rel."chat/chatfunctions.php");

// alle messages von get_msg
session_start();
if (isset($_SESSION["chatID"])) {
    $chatID = $_SESSION["chatID"];
    $messages = get_msg($con, $chatID);
} else {
    echo "Kein Chat ausgewählt";
    $messages = false;
}

/*
 jede message aus dem message array
 z.b.:
    messages
    (
      message:
        ID
        time
        message
        ...
      message:
        ID
        time
        message
        ...
    )
*/

if ($messages != false) {
    foreach ($messages as $message) {
        // Anzeige des Usernames __ tooltip: Hovereffekt mit Absendezeit
        echo "<div id='userprintout'> <div class= 'tooltip'>".$message['username'].":   <span class='tooltiptext-chat-time'> Gesendet: ".$message['time']."</span></div> </div> " ;
        //eigener Span für jede Message
        // Ersetzen von verschiedenen problematischen Zeichen(-ketten)
        $htmlText = htmlentities($message['message'], ENT_QUOTES, "UTF-8");
        echo "<span id='{$message['ID']}'>{$htmlText}</span><br>\n";
    }
}
