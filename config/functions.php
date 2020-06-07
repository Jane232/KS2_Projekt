<?php
// written by @Jane232
$rel = "";
/*
$rel ist eine Variable die zur Orientierung eingesetzt wird
Sie beinhaltet genau so viele "../" bis man im root-Verzeichniss ist
also kann jeder Link so aufgebaut sein:
$rel."config/test.html"
*/
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
/*
Überprüfung ob User eingeloggt ist, wenn nicht dann Weiterleitung
wenn doch dann wird Username zurückgegeben
*/
function logedIn()
{
    if (isset($_SESSION["user"])) {
        return $_SESSION["user"];
    } else {
        header("location: ../index.php");
        exit();
    }
}
/*
Erleichtert den Schreibaufwand
$add kann zusätzliche Stylesheets oder JS-Dateien enthalten
*/
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

function charAt($str, $pos)
{
    return $str{$pos};
}

/*
  1. $_COOKIE['theme'] -> Design
  2. int > 0  -> Länge
  3. float -> Größe des Captchas
  4. int -> fixer Grad der Drehung
  5. array (R,G,B) -> Hintergrundfarbe
  6. array (R,G,B) -> Schriftfarbe
  7. string -> alle auswählbaren Zeichen

  z.B.:
  <span id="captcha"><img src="data:image/png;base64,<?php echo $_SESSION["img"]; ?>" alt="Captcha"></span>
*/
function genCaptcha($mode = "dunkel", $lengh = "", $scale = 1, $degree = "", $c = array(47,47,47), $tc = array(139,135,135), $chars = "QERTZUPASDFGHJKYXCVBN123458123458?")
{
    if ($mode !="dunkel") { // Andere Farbe wenn hell
        $tc = array(185,185,185);
        $c = array(70, 70, 65);
    }
    // Überprüfen ob Parameter richtig sind
    if ((is_numeric($scale) && $scale != 0) &&(empty($lengh) || is_int($lengh)) && (empty($degree)|| is_int($degree)) &&(is_array($c)) && (is_array($tc)) && (strlen($chars) > 0)) {
        // verieren der Länge wenn nichts festgelegt
        if ($lengh == "") {
            $lengh = rand(3, 5);
        }
        $scale = abs($scale);
        $h = 100*$scale; //Height
        $font = dirname(__FILE__) . '/fonts/leadcoat.ttf'; // Schriftart in ttf-Format
        $text = "";
        $charlen = strlen($chars);
        $img = imagecreate((50*$lengh*$scale), $h); // Erstellung des img-Objects
        $color = imagecolorallocate($img, $c[0], $c[1], $c[2]); // Hintergrundfarbe
        $textcolor = imagecolorallocate($img, $tc[0], $tc[1], $tc[2]); //Schriftfarbe
        imagefill($img, 0, 0, $color);
        $deg = (is_int($degree)) ? $degree : rand(-135, 135);//entweder fix oder Start zwischen (-135, 135) Grad

        // Schleife die einzeln die Buchstaben einfügt
        for ($i=0; $i < $lengh; $i++) {
            if (empty($degree)) {//Änderung der Gradzahl -> zufällig
                $deg = rand($deg * 0.2, $deg * 1.8) + 30;
                if ($deg >360) {
                    $deg = $deg - 360;
                }
            }
            $selected = charAt($chars, rand(0, $charlen-1)); // random Zeichen aus $chars
            $text .= $selected; // Sting zur Überprüfung
            // Bestimmung der Höhe im Bild anhand der Höhe und der Gradzahl -> Zeichen sollten ganz sichtbar sein
            $a = ($h * 0.9) - abs($deg * $h /180);
            $Y = floor(($h > $a && $a > 0) ? $a : (($a < 0) ? abs($a) : $a-$h));
            $X = ($i*40 + 35)*$scale; // X-Wert im Bild
            imagettftext($img, 25*$scale, $deg, $X, $Y, $textcolor, $font, $selected); // Einfügen der Buchstaben in Bild
            // imagettftext( bildobj, font-size, Winkel, X , Y , font-color , Buchstabe / Text );
        }
        imagepng($img, session_id().".png"); // Erstellen / Überschreiben des Bildes mit session_id als Name
        imagedestroy($img); // Objekt löschen

        $image=file_get_contents(session_id().".png");
        $_SESSION["img"]=base64_encode($image); // Bild in Base64-Format in die Session schreiben
        $_SESSION["captcha"] = md5($text); // In Session eine "Überprüfsumme" schreiben, dank md5 nicht lesbar
        unlink(session_id().".png"); //Lösche Bild vom Server
    } else {
        echo "Parameter falsch eingegeben";
    }
}
/*
zu-überprüfernder-Captcha-Text
returns true / false
*/
function checkCaptcha($text)
{
    return $check = ($_SESSION["captcha"] == md5($text)) ? true : false ; // Überprüfen ob eingegebener Text dem Captcha enrspricht
}
