<!-- //written by @AlexanderAisenbrey-->
<!DOCTYPE html>
<html lang="german" data-theme="<?php echo $_COOKIE['theme']; ?>">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="schach.css">
</head>
<!--Einmaliges Ausführen der initialize bei jedem Neuladen der Seite-->
<body onload="initialize()">
<!--Erstellen der auf der Webseite angezeigten Tabelle inklusive der Standardaufstellung der Schachspiels-->
<table id="chess_board">
    <tr>
        <td class="label">&nbsp;</td>
        <td id="18" class="even" onclick="main(this.id)">&#9820;</td>
        <td id="28" class="oddd" onclick="main(this.id)">&#9822;</td>
        <td id="38" class="even" onclick="main(this.id)">&#9821;</td>
        <td id="48" class="oddd" onclick="main(this.id)">&#9819;</td>
        <td id="58" class="even" onclick="main(this.id)">&#9818;</td>
        <td id="68" class="oddd" onclick="main(this.id)">&#9821;</td>
        <td id="78" class="even" onclick="main(this.id)">&#9822;</td>
        <td id="88" class="oddd" onclick="main(this.id)">&#9820;</td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="17" class="oddd" onclick="main(this.id)">&#9823;</td>
        <td id="27" class="even" onclick="main(this.id)">&#9823;</td>
        <td id="37" class="oddd" onclick="main(this.id)">&#9823;</td>
        <td id="47" class="even" onclick="main(this.id)">&#9823;</td>
        <td id="57" class="oddd" onclick="main(this.id)">&#9823;</td>
        <td id="67" class="even" onclick="main(this.id)">&#9823;</td>
        <td id="77" class="oddd" onclick="main(this.id)">&#9823;</td>
        <td id="87" class="even" onclick="main(this.id)">&#9823;</td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="16" class="even" onclick="main(this.id)"></td>
        <td id="26" class="oddd" onclick="main(this.id)"></td>
        <td id="36" class="even" onclick="main(this.id)"></td>
        <td id="46" class="oddd" onclick="main(this.id)"></td>
        <td id="56" class="even" onclick="main(this.id)"></td>
        <td id="66" class="oddd" onclick="main(this.id)"></td>
        <td id="76" class="even" onclick="main(this.id)"></td>
        <td id="86" class="oddd" onclick="main(this.id)"></td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="15" class="oddd" onclick="main(this.id)"></td>
        <td id="25" class="even" onclick="main(this.id)"></td>
        <td id="35" class="oddd" onclick="main(this.id)"></td>
        <td id="45" class="even" onclick="main(this.id)"></td>
        <td id="55" class="oddd" onclick="main(this.id)"></td>
        <td id="65" class="even" onclick="main(this.id)"></td>
        <td id="75" class="oddd" onclick="main(this.id)"></td>
        <td id="85" class="even" onclick="main(this.id)"></td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="14" class="even" onclick="main(this.id)"></td>
        <td id="24" class="oddd" onclick="main(this.id)"></td>
        <td id="34" class="even" onclick="main(this.id)"></td>
        <td id="44" class="oddd" onclick="main(this.id)"></td>
        <td id="54" class="even" onclick="main(this.id)"></td>
        <td id="64" class="oddd" onclick="main(this.id)"></td>
        <td id="74" class="even" onclick="main(this.id)"></td>
        <td id="84" class="oddd" onclick="main(this.id)"></td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="13" class="oddd" onclick="main(this.id)"></td>
        <td id="23" class="even" onclick="main(this.id)"></td>
        <td id="33" class="oddd" onclick="main(this.id)"></td>
        <td id="43" class="even" onclick="main(this.id)"></td>
        <td id="53" class="oddd" onclick="main(this.id)"></td>
        <td id="63" class="even" onclick="main(this.id)"></td>
        <td id="73" class="oddd" onclick="main(this.id)"></td>
        <td id="83" class="even" onclick="main(this.id)"></td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="12" class="even" onclick="main(this.id)">&#9817;</td>
        <td id="22" class="oddd" onclick="main(this.id)">&#9817;</td>
        <td id="32" class="even" onclick="main(this.id)">&#9817;</td>
        <td id="42" class="oddd" onclick="main(this.id)">&#9817;</td>
        <td id="52" class="even" onclick="main(this.id)">&#9817;</td>
        <td id="62" class="oddd" onclick="main(this.id)">&#9817;</td>
        <td id="72" class="even" onclick="main(this.id)">&#9817;</td>
        <td id="82" class="oddd" onclick="main(this.id)">&#9817;</td>
    </tr>
    <tr>
        <td class="label">&nbsp;</td>
        <td id="11" class="oddd" onclick="main(this.id)">&#9814;</td>
        <td id="21" class="even" onclick="main(this.id)">&#9816;</td>
        <td id="31" class="oddd" onclick="main(this.id)">&#9815;</td>
        <td id="41" class="even" onclick="main(this.id)">&#9813;</td>
        <td id="51" class="oddd" onclick="main(this.id)">&#9812;</td>
        <td id="61" class="even" onclick="main(this.id)">&#9815;</td>
        <td id="71" class="oddd" onclick="main(this.id)">&#9816;</td>
        <td id="81" class="even" onclick="main(this.id)">&#9814;</td>
    </tr>

</table>

<!--Verknüpfung der HTML mit den JS-Dateien-->
<script type="text/javascript" charset="utf-8" src="js/Color.js"></script>
<script type="text/javascript" charset="utf-8" src="js/Figure.js"></script>
<script type="text/javascript" charset="utf-8" src="js/Field.js"></script>
<script type="text/javascript" charset="utf-8" src="js/HittableFieldsCalculator.js"></script>
<script type="text/javascript" charset="utf-8" src="js/CheckChecker.js"></script>
<script type="text/javascript" charset="utf-8" src="js/ChessGame.js"></script>
<script type="text/javascript" charset="utf-8" src="js/Main.js"></script>

</body>
</html>
