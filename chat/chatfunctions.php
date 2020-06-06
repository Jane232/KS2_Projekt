<?php
// written by @Jane232

//relevante Chatfunktionen : senden / empfangen / layout

//Sendet Nachrichten
//z.B. send_msg($con, "0000003100000123", "Peter", "das ist die Nachricht");
//returns true (success) / false (error)
function send_msg($con, $chatID, $sender, $message)
{
    if (!empty($message) && !empty($sender) && !empty($chatID)) {
        try {
            //Datenbankeintrag mit chatID , username , message
            $stmt = $con->prepare("INSERT INTO chat (chatID , username , message) VALUES (? , ? , ?)");
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param("sss", $chatID, $sender, $message);
            $stmt-> execute();
            $stmt-> close();
            $con -> close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    } else {
        return false;
    }
}
// Fragt Nachrichten ab
// z.B. get_msg($con, "0000003100000123")
// returns false + Beschreibung (keine Nachrichten vorhanden) / Array mit Nachrichten siehe chat.php
function get_msg($con, $chatID)
{
    $stm = $con->prepare("SELECT username, message,time,ID FROM chat WHERE chatID = $chatID ORDER BY ID DESC");
    // Datenbankabfrage von username, message,time,ID mit vorgegebnener ChatID --> in absteigender Reinfolge : neuste Nachrichten stehen oben
    $stm -> execute();
    $result = $stm->get_result();
    $messages = array();
    $i = 0;
    $stm-> close();
    $con -> close();
    if ($result -> num_rows === 0) {
        echo "Es sind noch keine Nachrichten vorhanden! <br>
            Schreibe eine Nachricht in das untenliegende Textfeld";
        return false;
    } elseif ($result -> num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[$i] = $row;
            $i++;
        }
        return $messages;
    }
}
// Funktion zur Bestimmung der gemeinsamen ChatID
// returns string (z.B. "0000003100000123")
function chatID($chatpartnerID, $ID)
{
    // Sortieren der IDs nach Größe
    if ($chatpartnerID >= $ID) {
        $maxID = $chatpartnerID;
        $minID = $ID;
    } else {
        $maxID = $ID;
        $minID = $chatpartnerID;
    }
    //0er Auffüllen von 123 zu 00000123
    for ($i=0; $i < 10; $i++) {
        if (strlen($maxID)<8) {
            $maxID = "0".$maxID;
        }
        if (strlen($minID)<8) {
            $minID = "0".$minID;
        }
    }
    $chatID = $minID.$maxID;
    //$chatID in $_SESSION["chatID"] um später verwendet zu werden
    $_SESSION["chatID"] = $chatID;
    return $chatID;
}
// Vorbereitungen für Chat (IDs der User abfragen)
// z.B. chatInit($con, $rel, "Peter");
// returns array($ID, $chatpartnerID, $chatpartner) / Redirect auf index.php
function chatInit($con, $rel, $chatpartner)
{
    $user = logedIn();
    if (!empty($chatpartner)) {
        //ID des Chatpartners
        $stm = $con->prepare("SELECT ID FROM users WHERE name = ?");
        $stm->bind_param("s", $chatpartner);
        $stm -> execute();
        $result = $stm->get_result();
        if ($result != false || !empty($result)) {
            while ($row = $result->fetch_assoc()) {
                //ID des Chatpartners
                $chatpartnerID = $row['ID'];
            }
        } else {
            //Bei Fehler / leerer ID -> Redirect
            header("Location: {$rel}chat/index.php");
        }
        //eigene ID
        $stm->bind_param("s", $user);
        $stm -> execute();
        $result = $stm->get_result();
        while ($row = $result->fetch_assoc()) {
            $ID = $row['ID'];
        }
        $stm -> close();
        return array($ID, $chatpartnerID, $chatpartner);
    } else {
        header("Location: {$rel}chat/index.php");
    }
}
// Funktion die je nach Parametern den HTML-Code für den Chat erstellt
// z.B. echo chatBody($rel, "Peter", "Annegret");
// chatBody($rel (!), Name (nicht !), Partner (nicht !), Titel (wenn 2. / 3. leer dann Mlg für Customtitel),  TextArea ("") / Input ("String"))
// returns String
function chatBody($rel, $user = "", $chatpartner = "", $titleInput = "", $type = "")
{
    // Einzelne HTML-Bausteine
    if (!empty($chatpartner)&& !empty($user)) {
        if ($chatpartner != $user) {
            $title = "<h2 id='h2Chat'><li><a href='index.php'>Chat mit {$chatpartner}</a></li></h2>";
        } else {
            $title =  "<h2 id='h2Chat'> <li><a href='index.php'>Notiz an mich selbst</a></li></h2>";
        }
    } elseif (!empty($titleInput)) {
        $title = "<h2 id='h2Chat'><li><a href='index.php'>".$titleInput."</a></li></h2>";
    } else {
        $title = "";
    }
    if (empty($type)) {
        $input = '<div id="tArea"><textarea placeholder= "Bitte Nachricht eingeben" name="message2" id="messageTextInput" required></textarea></div>';
    } else {
        $input = '<input type="text" placeholder= "Bitte Nachricht eingeben" name="message2" id="messageTextInput" class="inputField" autocomplete="off" required>';
    }
    return $title.'
<div id="messagebox"><!-- Box für alle Nachrichten: wird von autochat.js angesprochen -->
  <div id="message">  </div>
</div>
<div id = "input">
  <form  action="#" method="post" id="messageForm">
  '.$input.'
    <button type="submit" name="senden" value="1">Senden</button>
  </form>
<div id="chatFeedback"> </div><!-- Nachricht gesendet etc... wird von sendchat.js´s Success-Funktion angesprochen  -->
</div>
<script src="'.$rel.'Js/jquery-3.4.1.min.js"> </script>
<script src="'.$rel.'Js/autochat.js"> </script>
<script src="'.$rel.'Js/sendchat.js"> </script>';
}
