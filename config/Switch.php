<?php
// written by @Jane232

// Hell-Dunkel Schalter
require_once("functions.php");
if (isset($_COOKIE['theme'])) {
    $wert = $_COOKIE['theme'];
} // Wenn Cookie "theme" nicht gesetzt ist (neue Sitzung) wird es mit "dunkel" gesetzt
else {
    setcookie('theme', 'dunkel', 0, "/");
    $wert = "dunkel";
}

echo '<div class="tooltip">';

if ($wert == "dunkel") { //Switch wird je nach geladenem Theme schon gedrückt geladen  oder nicht
    echo '<div class="container">
    <div id="c2"><input type="checkbox" id="switch" name="theme"/> <label for="switch">Toggle</label></div>
    </div>';
} else {
    echo '<div class="container">
    <div id="c2"><input type="checkbox" id="switch" name="theme" checked /> <label for="switch">Toggle</label></div>
    </div>';
}

echo ' <span class="tooltiptext" id="spanTheme"> Wechsel das Theme </span> </div> ';
?>


<script type="text/javascript">
    var checkbox = document.querySelector('input[name=theme]');

    let value = readCookie('theme');

    checkbox.addEventListener('change', function(){ // Wenn der switch gedrückt wird dann wird das HTML Attribute und das Cookie gesetzt (je nach aktuellem Stand (hell/dunkel) auf (dunkel/hell))
      if(this.checked){
        trans();
        document.cookie = "theme = hell ; ; path=/";
        document.documentElement.setAttribute('data-theme', 'hell');
        backgroundchange('Bilder/backgrounds/DSCF0107.jpg');
      }else{
        trans();
        document.cookie = "theme = dunkel; ; path=/";
        document.documentElement.setAttribute('data-theme', 'dunkel');
        backgroundchange('Bilder/backgrounds/DSCF0004.jpg');
      }
    }
    )
    //Funktion um Cookie auszulesen
    function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
    }

    let trans = () => {
      document.documentElement.classList.add('transition')
      window.setTimeout(() => {
        document.documentElement.classList.remove('transition')
      }, 1000)
    }

    let backgroundchange = (link) => {
      //document.getElementById("backgroundimg").style.backgroundColor = "red";
      //$('backgroundimg').addClass('fadein');
      //document.documentElement.classList.add('transition')
      document.getElementById("backgroundimg").style.backgroundImage = "url("+link+")";
    }
</script>
