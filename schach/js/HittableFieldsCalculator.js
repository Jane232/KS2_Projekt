//written by @AlexanderAisenbrey
class HittableFieldsCalculator {
    // Diese Funktion überprüft, welche begehbaren Felder berechnet werden müssen
    static getHittableFields(field) {
        // Get the hittable fields
        switch (field.figure.figureType) {
            case Figure.FIGURETYPES.Pawn:
                return this.getHittableFieldsPawn(field);
            case Figure.FIGURETYPES.Rook:
                return this.getHittableFieldsRook(field);
            case Figure.FIGURETYPES.Bishop:
                return this.getHittableFieldsBishop(field);
            case Figure.FIGURETYPES.Knight:
                return this.getHittableFieldsKnight(field);
            case Figure.FIGURETYPES.Queen:
                return this.getHittableFieldsQueen(field);
            case Figure.FIGURETYPES.King:
                return this.getHittableFieldsKing(field);
            default:
                return [];
        }
    }
    // Diese Funktion berechnet die begehbaren Felder des Bauerns
    static getHittableFieldsPawn(field) {
        let direction = field.figure.color.isWhite ? 1 : -1;
        let fields = [];

        // 1. Die Felder schräg vor dem Bauer werden berechnet
        let tmpFields = [];
        tmpFields.push(chessGame.getField(field.row + direction, field.column - 1));
        tmpFields.push(chessGame.getField(field.row + direction, field.column + 1));

        for (let tmpField of tmpFields)
            if (tmpField !== undefined && tmpField.isOccupied && field.figure.color.isOpponent(tmpField.figure.color))
                fields.push(tmpField); // Wenn sich ein Gegner auf diesen Feldern befindet werden sie zu den begehbaren Feldern hinzugefügt

        // Das Feld vor dem Bauern wird berechnet
        let fieldInFront = chessGame.getField(field.row + direction, field.column);
        if (!fieldInFront.isOccupied) {
            fields.push(fieldInFront); // Wenn sich kein Gegner auf diesem Feld befindet wird es zu den begehbaren Feldern hinzugefügt

            // Das Feld zwei Felder vor dem Bauer wird berechnet
            if (field.row === 2 || field.row === 7) {
                let fieldTwoInFront = chessGame.getField(field.row + 2 * direction, field.column);
                if (fieldTwoInFront !== undefined && !fieldTwoInFront.isOccupied)
                    fields.push(fieldTwoInFront); // Wenn sich der Bauer entweder in der zweiten oder siebten Reiche befindet, das Feld zwei Felder vor ihm existiert und weder auf dem Feld direkt vor ihm noch auf dem Feld zwei Felder vor ihm eine Figur steht, wird das Feld zu den begehbaren Feldern hinzugefügt
            }
        }

        return fields;
    }

    // Diese Funktion bestimmt die directions des Turms
    static getHittableFieldsRook(field) {
        let directions = [[1, 0], [0, -1], [0, 1], [-1, 0]]
        return this.getFieldsInDirection(field, directions);
    }

// Diese Funktion bestimmt die directions des Springers
    static getHittableFieldsBishop(field) {
        let directions = [[1, 1], [1, -1], [-1, 1], [-1, -1]]
        return this.getFieldsInDirection(field, directions);
    }

// Diese Funktion bestimmt die directions des Läufers
    static getHittableFieldsKnight(field) {
        let offsets = [[1, 2], [-1, 2], [1, -2], [-1, -2], [2, 1], [-2, 1], [2, -1], [-2, -1]]
        return this.getFieldsAtOffsets(field, offsets);
    }

// Diese Funktion bestimmt die directions der Dame
    static getHittableFieldsQueen(field) {
        let directions = [[1, 1], [1, -1], [-1, 1], [-1, -1], [1, 0], [0, -1], [0, 1], [-1, 0]];
        return this.getFieldsInDirection(field, directions);
    }

// Diese Funktion bestimmt die offsets des Königs
    static getHittableFieldsKing(field) {
        let offsets = [[1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0], [-1, -1], [0, -1], [1, -1]];
        return this.getFieldsAtOffsets(field, offsets);
    }

    // Diese Funktion berechnet die begehbaren Felder einer bestimmten Figur auf einem bestimmten Feld mit bestimmten directions; Diese Figuren können sich so weit bewegen bis sie auf eine andere Figur treffen
    static getFieldsInDirection(field, directions) {
        let fields = [];
        // Durchgehen durch alle Elemente der directions
        for (let direction of directions) {
            let i = 1;
            let thisField;
            // Hinzufügen aller begehbaren Felder einer direction
            while ((thisField = chessGame.getField(field.row + direction[0] * i, field.column + direction[1] * i)) !== undefined) {
                if (thisField.isOccupied) {
                    if (field.figure.color.isOpponent(thisField.figure.color))
                        fields.push(thisField); // Das Feld wird zu den begehbaren Feldern der Figur hinzugefügt, wenn sich auf diesem Feld eine gegnerische Figur befindet
                    break; // Abbrechen der Funktion, wenn sich eine eigene Figur auf demm letzten Feld befindet
                }
                fields.push(thisField); // Das Feld wird zu den begehbaren Feldern der Figur hinzugefügt
                i++;
            }
        }
        return fields;
    }
    // Diese Funktion berechnet die begehbaren Felder einer bestimmten Figur auf einem bestimmten Feld mit bestimmten offsets; Diese Figuren können sich je nur ein Feld weit bewegen, ansonsten gleiche Vorgehensweise wie bei en directions
    static getFieldsAtOffsets(field, offsets) {
        let fields = [];
        for (let direction of offsets) {
            let thisField = chessGame.getField(field.row + direction[0], field.column + direction[1]);
            if (thisField !== undefined
                && (!thisField.isOccupied || field.figure.color.isOpponent(thisField.figure.color))) {
                fields.push(thisField);
            }
        }
        return fields;
    }
}
