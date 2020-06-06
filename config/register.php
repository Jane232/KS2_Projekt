<?php
// written by @Jane232

// PHP-Teil der Registrierungsseite

if (isset($_POST['username'])) {
    if ($_POST['password'] == $_POST['password2']) {
        // Varablen übernehmen
        $username = $_POST['username'];
        $password = $_POST['password'];
        // eingegebenes Passwort hashen
        //TODO hash auf Client seite legen
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // check ob User bereits erstellt
        $stm = $con->prepare("SELECT * FROM users WHERE name=?;");
        $stm->bind_param("s", $username);
        $stm-> execute();
        $result = $stm->get_result();

        if ($result->num_rows === 0) {
            $ready = true;
        } else {
            $nutzerverg = true;
        }
    } else {
        $passwordinkorrekt = true;
    }
}
if (isset($ready)) { // wenn noch nicht vorhanden: Eintragung in die DB
    try {
        $stm = $con->prepare("INSERT INTO users (name , password, entrydate) VALUES (?, ?, NOW())");
        if (!$stm) {
            echo "<p>Fehler!</p>". $con -> error;
        }
        $stm->bind_param("ss", $username, $hashed);
        $stm-> execute();
        $stm -> close();
        $registriert = true;
    } catch (Exception $e) {
        echo "<p>Fehler!</p>". $e;
    }
}
