<!--written by @Jane232 -->
<div id = "backgroundimg">
  <div class="homepageHeader">
    <h1 class="homepageText">Informatik-Homepage</h1>
    <a href="#HomepageBottom" id="scrolldown">Erfahre mehr</a>
  </div>
</div>

<script>
  if (value == "hell") {document.getElementById("backgroundimg").style.backgroundImage = "url('Bilder/backgrounds/DSCF0107.jpg')";}
</script>

<div id = "HomepageBottom">
  <div class="homepageBoxes">

    <div class="content">
      <h1>Diese Homepage</h1>
      Auf dieser Webseite findest du einen Chat und ein Schachspiel.<br>
      Um diese Nutzen zu können musst du dich <a href= "index.php?display=login"><span class = "highlight">anmelden</span></a>.
      Wenn du noch keinen Account hast dann geh bitte auf Login und dann auf Registrierung.
    </div>

    <div class="content">
      <h1>Projekte</h1>
      <ul>
        <li><span class = "highlight">LogIn-System:</span> Mit PHP und MySQL werden Nutzer eingetragen und abgefragt. </li>
        <li><span class = "highlight">Chat:</span> Mit PHP und MySQL werden Nachrichten an den Server übertragen und gespeichert.</li>
        <li><span class = "highlight">Schach:</span> Ein Informatik-Schulprojekt mit Alex für unseren Informatik-Kurs </li>
      </ul>
    </div>

  </div>
  <div class="homepageBoxes" id="projectsBoxes">

    <div class="content">
      <h1>Chat</h1>
        Der Chat bietet zwei Möglichkeiten der Kommunikation:
        <ul>
          <li>
            Der <span class = "highlight">Globalchat</span> um mit allen Nutzern in Kontakt zu treten.
          </li>
          <li>
            Die <span class = "highlight">Privatchats</span> bieten die Möglichkeit mit einzelnen Nutzern zu schreiben.
          </li>
        </ul>
    </div>

    <div class="content">
      <h1>Schach</h1>
      Das Schachspiel ist mit JavaScript programmiert, die Speicherung des aktuellen Spielstands wird von PHP und MySQL übernommen.
      <ul>
          <li>
            Bei jedem abgeschlossenen Spiel hast du die Möglichkeit das Spiel zu <span class = "highlight">löschen</span>, dies bewirkt, dass deinem Partner nur noch eine ID angezeigt wird. <br>
            Wenn du es <span class = "highlight">nicht löschen</span> willst wird es automatisch in eine seperate Kategorie verschoben.
          </li>
          <li>
            Unter jedem Schachspiel wird dir ein <span class = "highlight"> Chat </span> mit deinem Schach-Gegner angezeigt. Dieser übermittelt deine Nachrichten fast in Echtzeit auf zu deinem Gegner
          </li>
        </ul>
    </div>

  </div>
</div>

<div class="zurückAnfang">
  <a href="#">zurück an den Anfang</a>
</div>
