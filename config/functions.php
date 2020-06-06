<?php
// written by @Jane232
function logedIn()
{
    if (isset($_SESSION["user"])) {
        return $_SESSION["user"];
    } else {
        header("location: ../index.php");
        exit();
    }
}
function htmlHead($rel, $title, $add = "")
{
    $a = explode('$rel/', $add);
    if (count($a)>1) {
        $temp = "";
        for ($i=0; $i < count($a)-1; $i++) {
            $temp = $temp.$a[$i].$rel;
        }
        $temp = $temp.$a[count($a)-1];
        $add = $temp;
    }

    $head = '
  <head>
  <meta charset="utf-8">
  <title> '.$title.' </title>
  <link rel="icon" href="'.$rel.'Bilder/ico/favicon.ico">
  <link rel="stylesheet" href="'.$rel.'style.css">
  '.$add.'
  </head>
  ';
    return $head;
}

function db()// Verbindung mit der Datenbank:
{
    // Festlegung der Parameter
    $db_host = "localhost";
    $db_name = "informatikabgabe";
    $db_user = "";
    $db_pass = "";

    // Verbindung herstellen
    $con = @ new mysqli($db_host, $db_user, $db_pass, $db_name);
    // Wenn Verbindung fehlgeschlagen, dann Error ausgeben und Script abbrechen
    if ($con -> connect_error) {
        die("<p>connecion failed</p>" . $con -> connect_error);
    }
    //Verbindung als Objekt zuückgeben
    return $con;
}



$rel = "";
if (strpos(getcwd(), "/") === false) {
    $array = explode('\\', getcwd()); //link wird an \ geteilt
} else {
    $array = explode('/', getcwd()); //link wird an / geteilt
}

for ($i = count($array) - 1 ; $i > 1 ; $i--) {
    $path = "";
    for ($m=0; $m < $i; $m++) {
        $path = $path.$array[$m]."/";
    }
    if (file_exists($path."root")) {
        $o = ($i - $m) + 1;
        while ($o > 0) {
            $rel = "../".$rel;
            $o-- ;
        }
        break;
    }
}
$link = $_SERVER['REQUEST_URI'];
