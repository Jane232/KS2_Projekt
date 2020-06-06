//written by @AlexanderAisenbrey
class ChessGame {
  // Der aktuelle Status des Spiels wird gespeichert
    static GAMESTATES = {
        RUNNING: 0,
        WHITE_WON: 1,
        BLACK_WON: 2,
        DRAW: 3
    }
// lokale Variablen werden deklariert
    fields = [[undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined], [undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined]]; // row, dann column
    gameState = ChessGame.GAMESTATES.RUNNING;
    activePlayerColor = new Color("white");
    selectedField;
    isCheck = false;
    numPossibleMoves;
    numEmptyFields;
    // Diese Funktion ist für die Einleitung der gesammten Berechnung zuständig
    selectField(row, column) {
        let clickedField = this.getField(row, column);
        if (clickedField === undefined || this.gameState !== ChessGame.GAMESTATES.RUNNING)
            return;

        // 2. Klick: Die Figure wird von dem ausgewählten Feld auf des angklickte Feld bewegt
        if (clickedField.isHittable) {
            this.move(this.selectedField, clickedField);
            this.selectedField = undefined;

            // die Anzahl der Figuren auf dem Feld wird berechnet
            this.numEmptyFields=0;
            this.numFigures();

            // Überprüfung und Ausführung der Verwandlung ds Bauerns
            if (clickedField.figure.figureType === Figure.FIGURETYPES.Pawn && (row === 1 || row === 8))
                this.pawnMiracle(clickedField);

            this.deleteCalculatedFields();
            this.isCheck = CheckChecker.checkCheck(clickedField);

            // Der aktive Spieler wird getauscht
            this.activePlayerColor = new Color(!this.activePlayerColor.isWhite);
            CheckChecker.calculatePossibleFields();// die begehbaren Felder des nächsten Spielers werden berechnet

            // Setzen des Spielstatus
            if (this.numPossibleMoves === 0 || this.numEmptyFields===62)
                this.gameState = this.isCheck
                    ? this.activePlayerColor.isWhite ? ChessGame.GAMESTATES.BLACK_WON : ChessGame.GAMESTATES.WHITE_WON
                    : ChessGame.GAMESTATES.DRAW;
            this.selectedField = clickedField;
        }


        this.makeAllUnhittable();
        // 1. Klick: Alle begehbaren Felder werden markiert(noch nicht gefärbt), wenn das angeklickte Feld ein eigenes, besetztes Feld ist
        if (clickedField.isOccupied && this.activePlayerColor.equals(clickedField.figure.color)) {
            this.selectedField = clickedField;
            for (let hittableField of clickedField.figure.hittableFields) {
                if (hittableField instanceof Field) {
                    chessGame.getField2(hittableField).markHittable(true);
                }
            }
        }
    }
    // Diese Funktion entfernt alle Felder aus den begehbaren Feldern
    deleteCalculatedFields() {
        this.fields.forEach(row => row.forEach(field => {
            if (field.isOccupied)
                field.figure.hittableFields = undefined
        }));
    }
    // Diese Funktion bewegt eine Figur an eine andere Stelle
    move(from, to) {
        to.changeFigure(new Figure(from.figure.unicode));
        from.figure = undefined;
    }
    // Diese Funktion berechnet alle Felder, die von gegnerischen Figuren getroffen werden können
    getAllFieldsUnderAttack() {
        let fieldsUnderAttack = new Set();
        for (let row of this.fields) {
            for (let field of row) {
                if (field.isOccupied && field.figure.color.isOpponent(this.activePlayerColor)) {
                    // go through all enemies
                    HittableFieldsCalculator.getHittableFields(field).forEach(v => fieldsUnderAttack.add(v));
                }
            }
        }
        return fieldsUnderAttack;
    }
    // Diese Funktion gibt ein Feld zurück, das um je ein Feld verschoben ist
    getField(row, column) {
        if (Field.isInBound(row, column))
            return this.fields[row - 1][column - 1];
        else
            return undefined;
    }
    // Diese Funktion gibt ein Feld zurück
    getField2(field) {
        if (field === undefined)
            return undefined;
        return this.getField(field.row, field.column);
    }
    // Diese Funktion beseitigt alle markierungen von begehbaren Feldern
    makeAllUnhittable() {
        for (const row of this.fields) {
            for (const field of row) {
                field.markHittable(false);
            }
        }
    }
    // Diese Funktion macht den Bauern zu einer Dame wenn er sich auf dem letzten Feld befindet
    pawnMiracle(field) {
        if (field.figure.color.isWhite)
            field.figure = new Figure("♕");
        else
            field.figure = new Figure("♛")
    }
    // Diese Funktion berechnet die Anzahl der leeren Figuren auf dem Feld
    numFigures(){
      for (let row of this.fields) {
          for (let field of row) {
              if (!field.isOccupied){
                this.numEmptyFields++;
              }
      }
    }
  }
}
