<!--
written by @Jane232

HTML-Teil der  Backendseite Pageregister : um bestimmte Seiten für bestimmte User freizuschalten
-->

<br>
<div class="pageregister-box">

<form class="input-box" action="admin.php?display=edit" method="post" where="register">

      <h1>Seite registrieren </h1>
      <!-- Inputform für alle Parameter -->
      <input type="text" placeholder="Seitenname" name="pagename" required>
      <input type="text" placeholder="Link der Seite" name="link" required>
      <input type="text" placeholder="Berechtigter Nutzer" name="access" required>
      <input type="text" placeholder="Gruppen" name="groups" >

      <div class="eingabe-feld-checkbox">
        <label id="display-label" for="display-checkbox"> Display:</label>
        <input type="checkbox" id="display-checkbox" name="display" value="display" checked>
      </div>


      <button type="submit" name="login" value="1" >Aufnehmen</button>

      <center>
          <?php
          // Feedback für User
          if (isset($seiteverg)) {
              echo "<p> Diese Seite wurde bereits mit diesem Nutzer verknüpft! </p>";
          }
          if (isset($registriert)) {
              echo "<p> Diese Seite wurde erfolgreich registriert ! </p>";
          }
          ?>
      </center>

</form>
</div>

<br>
