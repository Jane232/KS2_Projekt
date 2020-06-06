<?php
// written by @Jane232

// PHP-Teil der Backendseite Pageregister : um bestimmte Seiten für bestimmte User freizuschalten
// Varablen übernehmen
if (isset($_POST['pagename'])) {
    $pagename = $_POST['pagename'];
    $link = $_POST['link'];
    $access = $_POST['access'];
    $groups = $_POST['groups'];
    if (isset($_POST['display'])) {
        $display = 1;
    } else {
        $display = 0;
    }

    // check ob Seite schon vergeben
    $stm = $con->prepare("SELECT * FROM pages WHERE link=? AND access=?");
    $stm->bind_param("ss", $link, $access);
    $stm-> execute();
    $result = $stm->get_result();
    $stm -> close();
    if ($result->num_rows === 0) {
        $ready = true;
    } else {
        $seiteverg = true;
    }
}

if (isset($ready)) { // wenn Seite noch nicht vergeben, dann Parameter in DB eintragen
    try {
        $stmt = $con->prepare("INSERT INTO pages (pagename , link, access, groupAccess, display) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo "<p>Fehler!</p>". $con -> error;
        }
        $stmt->bind_param("ssssi", $pagename, $link, $access, $groups, $display);
        $stmt-> execute();
        $stmt -> close();
    } catch (Exception $e) {
        echo "<p>Fehler!</p>";
    }
}
