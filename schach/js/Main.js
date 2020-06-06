//written by @AlexanderAisenbrey
let chessGame; // die Variable ChessGame wird deklariert

// Diese Funktion wird bei jedem Klicken auf ein Feld ausgeführt
function main(id) {
  // Deklaration von Variablen
  let actRow;
  let actCol;
  let myColor = new Color(user === user1); // Überprüfung, ob es sich um den weißen oder um den schwarzen Speiler handelt(mithilfe der Datenbank)

  // Für den Schwarzen Spieler werden die gesammten Felder rotiet, sodass nun die schwarzen Figuren unten stehen; Beim weißen Spieler passiert nichts
  if (myColor.isWhite) {
    actRow = parseInt(id.substring(1, 2));
    actCol = parseInt(id.substring(0, 1));
  } else {
    actRow = 9 - parseInt(id.substring(1, 2));
    actCol = 9 - parseInt(id.substring(0, 1));
  }

// Das gesamte Programm darf nur dann ausgeführt werden wenn man selber der aktive Spieler ist
  if (chessGame.activePlayerColor.equals(myColor)) {
    chessGame.selectField(actRow, actCol);
    if (chessGame.activePlayerColor.isOpponent(myColor)) {
      writeData(JSON.stringify(chessGame));
}
  }
  updateUI((new Field(actRow, actCol)));
}
// Diese Funktion sendet die benötigten Daten an die Datenbank
function writeData(string) {
  console.log("DATEN WERDEN GESCHRIEBEN!");
  dataWritten = true;
  // Der String wird in 5 einzelne Stirng unterteilt, um die 2000B Begrenzung der post-Variable zu umgehen
  let stringlength = string.length;
  let string1 = string.substring(0, 1918);
  let string2 = string.substring(1918, 3836);
  let string3 = string.substring(3836, 5754);
  let string4 = string.substring(5754, 7672);
  let string5 = string.substring(7672);

//written by @Jane232
//eine AJAX Funktion die den Fieldstring übermittelt um in die DB zu schreiben
  $.ajax({ // WRITE DATA
    type: 'post',
    url: 'sendData.php',
    data: {
      task: "writeData",
      fieldString1: string1,
      fieldString2: string2,
      fieldString3: string3,
      fieldString4: string4,
      fieldString5: string5,
      chessID: chessID,
      activePlayer: user,
      numMovesFromDB: numMovesFromDB
    },
    success: function(data) {
      if (data !== "") {
        console.log(data);
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log("Status: " + textStatus);
      console.log("Error: " + errorThrown);
    }
  });

  active = false;
}

//written by @AlexanderAisenbrey

// Diese Funktion wird bei jedem Neuladen der Seite ausgeführt; Sie ersetzt das aktuelle Feld mi dem neuen Feld ovn der Datenbank
function initialize() {
  let chessGameCrap = JSON.parse(fieldStringFromDB);

  //Objekte in Klassen:
  chessGame = toClass(chessGameCrap, ChessGame.prototype);
  chessGame.activePlayerColor = toClass(chessGame.activePlayerColor, Color.prototype);
  for (let row of chessGame.fields) {
    for (let field of row) {
      field = fieldToClass(field);
    }
  }
  chessGame.selectedField = fieldToClass(chessGame.selectedField);

// Wenn es einen letzten Zug des anderen Spielers gibt wird das Feld auf dem der letzte Zug endete übertragen, damit die Schachberechnung vollständig funktionieren kann
  if (chessGame.selectedField !== undefined) {
    updateUI(chessGame.selectedField);
  } else
    updateUI(undefined);
}
// Diese Funktion lässt alle Berechnungen auf dem Schachbrett sichtbar werden
function updateUI(clickedField) {
  // Color all fields
  for (let i = 1; i <= 8; i++) {
    for (let j = 1; j <= 8; j++) {
      let field;
      let htmlField;
      // Wenn man der weiße Spieler ist wird das htmlField normal deklariert, wenn man der schwarze Spieler ist werden alle htmlFields gespiegelt deklariert
      if (user === user1) {
        field = chessGame.getField(i, j);
        htmlField = document.getElementById((new Field(i, j)).name);
      } else {
        field = chessGame.getField(9 - i, 9 - j);
        htmlField = document.getElementById((new Field(i, j)).name);
      }

      // Die Eigenschaften des ftmlFields werden zurückgesetzt/deklariert
      htmlField.innerText = "";
      htmlField.style.borderStyle = "solid";
      htmlField.style.borderColor = "#e7e3e3"; //Grid
      htmlField.style.borderWidth = "4px"
      htmlField.style.background = "";
      htmlField.style.boxShadow = "none";
      let theme = document.getElementsByTagName("html")[0].getAttribute("data-theme"); // hell - dunkel // Abhängig vom Theme auf der Webseite werden unterschiedliche Farben benutzt
      // Einfügen der Figuren auf dem Schachbrett
      if (field.isOccupied) {
        htmlField.innerText = field.figure.unicode;
      }
      // Die begehbaren Felder werden je nach Theme gefärbt
      if (field.isHittable) {
        if (theme === "hell") {
          htmlField.style.background = "#b69f63";
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #3e3e3e";
        } else {
          htmlField.style.background = "#5995cb";
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #3e3e3e";
        }
      }

      // Die Felder mit den Figuren, die sich noch bewegen können, wenn der König unter Schach steht, werden gefärbt
      if (chessGame.isCheck && field.isOccupied &&
        field.figure.hittableFields !== undefined && field.figure.hittableFields.length > 0) {
        if (theme === "hell") {
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #272d30";
          htmlField.style.background = "#543a1f";

        } else {
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #272d30";
          htmlField.style.background = "#639de0";
        }

      }
// Wenn der eigene König unter Schach steht wird er gefärbt
      if (chessGame.isCheck && field.isOccupied && field.figure.figureType === Figure.FIGURETYPES.King &&
        field.figure.color.equals(chessGame.activePlayerColor)) {
        if (theme === "hell") {
          htmlField.style.background = "#eb7338";
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #272d30";

        } else {
          htmlField.style.background = "#eb7338";
          htmlField.style.boxShadow = "inset 0px 0px 10px 0px #272d30";

        }

      }
    }
  }
// Je nach dem aktuellen Stuatus des Spiels wird eine entsprechende Nachricht ausgegeben; oder es passiert nichts
if (activeGame === "active") {
  switch (chessGame.gameState) {
    case ChessGame.GAMESTATES.WHITE_WON:
      alert("Weiß hat gewonnen!");
      window.location.href="Schachübersicht.php?show=gameEnd&winner="+user1+"&chessID="+chessID;
      break;
    case ChessGame.GAMESTATES.BLACK_WON:
      alert("Schwarz hat gewonnen!");
      window.location.href="Schachübersicht.php?show=gameEnd&winner="+user2+"&chessID="+chessID;
      break;
    case ChessGame.GAMESTATES.DRAW:
      alert("Unentschieden!");
      window.location.href="Schachübersicht.php?show=gameEnd&winner=_draw_&chessID="+chessID;
      break;
  }
}

  // Das ausgewählte Fel wird markiert
  if (clickedField !== undefined) {
    let theme = document.getElementsByTagName("html")[0].getAttribute("data-theme"); // hell - dunkel
    // Wenn es sich um den weißen Spieler handelt, wird das ausgewählte Feld normal markiert
    if (user === user1) {
      if (theme === "hell") {
        document.getElementById(clickedField.name).style.background = "rgb(173, 155, 110)";
        document.getElementById(clickedField.name).style.boxShadow = "inset 0px 0px 10px 0px #272d30";

      } else {
        document.getElementById(clickedField.name).style.background = "rgb(133, 137, 175)";
        document.getElementById(clickedField.name).style.boxShadow = "inset 0px 0px 10px 0px #272d30";

      }
    }
    // Wenn es sich um den schwarzen Spieler handelt, wird das ausgewählte Feld gedreht
    else {
      if (theme === "hell") {
        document.getElementById(clickedField.inverse().name).style.background = "rgb(173, 155, 110)";
        document.getElementById(clickedField.inverse().name).style.boxShadow = "inset 0px 0px 10px 0px #272d30";

      } else {
        document.getElementById(clickedField.inverse().name).style.background = "rgb(133, 137, 175)";
        document.getElementById(clickedField.inverse().name).style.boxShadow = "inset 0px 0px 10px 0px #272d30";

      }
    }

  }
}

function toClass(obj, proto) {
  if (obj !== undefined) {
    obj.__proto__ = proto;
  }
  return obj;
}

function fieldToClass(field) {
  if (field !== undefined) {
    field = toClass(field, Field.prototype);
    if (field.figure !== undefined) {
      field.figure = toClass(field.figure, Figure.prototype);
      if (field.figure.color !== undefined) {
        field.figure.color = toClass(field.figure.color, Color.prototype);
      }
      if (field.figure.hittableFields !== undefined) {
        for (let f of field.figure.hittableFields) {
          if (!(f instanceof Field)) {
            fieldToClass(f);
          }
        }
      }
    }
  }

  return field;
}
