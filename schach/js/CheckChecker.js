//written by @AlexanderAisenbrey
class CheckChecker {
    //field ist das Feld auf dem der aktuelle Zug endet
    static checkCheck(field) {
        let enemyKingField = this.findFirstField(Figure.FIGURETYPES.King, new Color(!chessGame.activePlayerColor.isWhite));
        // 1. Überprüfung, ob die letzte bewegte Figur den König Schach setzt
        let nextFields = HittableFieldsCalculator.getHittableFields(field);
        if (nextFields.includes(enemyKingField))
            return true;
        // 2. Überprüfung, ob das letzte bewegte Feld den Weg für die Dame, den Läufer oder den Turm zum König frei macht
        let enemyKingAsRookFields = HittableFieldsCalculator.getHittableFieldsRook(enemyKingField);
        if (this.fieldsContainEnemy(enemyKingAsRookFields, Figure.FIGURETYPES.Rook)
            || this.fieldsContainEnemy(enemyKingAsRookFields, Figure.FIGURETYPES.Queen))
            return true;

        let enemyKingAsBishopFields = HittableFieldsCalculator.getHittableFieldsBishop(enemyKingField);
        if (this.fieldsContainEnemy(enemyKingAsBishopFields, Figure.FIGURETYPES.Bishop)
            || this.fieldsContainEnemy(enemyKingAsBishopFields, Figure.FIGURETYPES.Queen))
            return true;
    }

    // berechnet und speichert die begehbaren Felder jeder eigenen Figur
    static calculatePossibleFields() {
        // Findes des eigenen Königs
        let unmovedKingField = this.findFirstField(Figure.FIGURETYPES.King, chessGame.activePlayerColor);
        let numberLegalHittableFields = 0;
        for (let row of chessGame.fields) {
            for (let field of row) {
                // Aussortieren der gegnerischen Figuren
                if (field.isOccupied && field.figure.color.equals(chessGame.activePlayerColor)) {
                    // begehbare Felder der Figur berechnen
                    let hittableFields = HittableFieldsCalculator.getHittableFields(field);
                    let trueHittableFields = [];

                    // Überprüfung, ob das Feld der König ist
                    let isKing = field.figure.figureType === Figure.FIGURETYPES.King;
                    for (let hittableField of hittableFields) {
                        // Kurzzeitiges Bewegen der Figur während die alte Position gespeichert bleibt
                        let oldFieldFigure = field.figure;
                        let oldHittableFieldFigure = hittableField.figure;
                        chessGame.move(field, hittableField);
                        // Wenn das Feld der König ist wird überprüft, ob die begehbaren Felder tatsächlich begehbar sind; wenn das der Fall ist wird das Feld den begehbaren Felder hinzugefügt
                        // Wenn das Feld nicht der König ist wird überprüft, ob der Zug den König Schach setzt; wenn das nicht der Fall ist wird das Feld den begehbaren Felder hinzugefügt
                        if (!chessGame.getAllFieldsUnderAttack().has(isKing ? hittableField : unmovedKingField))
                            trueHittableFields.push(hittableField);

                        // Der Zug wird rückgängig gemacht
                        field.figure = oldFieldFigure;
                        hittableField.figure = oldHittableFieldFigure;
                    }
                    // die begehbaren Felder werden in der Figur gespeichert und die Anzahl der begehbaren Felder werden berechnet
                    field.figure.hittableFields = trueHittableFields;
                    numberLegalHittableFields += trueHittableFields.length;
                }
            }
        }
        // die Anzahl der möglichen Züge wird berechnet
        chessGame.numPossibleMoves = numberLegalHittableFields;
    }

    // Diese Funktion findet das erste Feld, auf dem eine bestimmte Figur mit einer bestimmten Farbe steht
    static findFirstField(figureType, color) {
        for (let row of chessGame.fields) {
            for (let field of row) {
                if (field.isOccupied && field.figure.figureType === figureType
                    && field.figure.color.equals(color))
                    return field;
            }
        }
        return undefined;
    }

    // Diese Funktion überprüft, ob ein bestimmter Array eine bestimmte Figur enthält
    static fieldsContainEnemy(fields, figureType) {
        for (let field of fields) {
            if (field.isOccupied && field.figure.color.isOpponent(chessGame.activePlayerColor)
                && field.figure.figureType === figureType)
                return true;
        }
    }
}
