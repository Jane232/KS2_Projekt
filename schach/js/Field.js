//written by @AlexanderAisenbrey
// Eine Klasse für Felder auf dem Schachbrett
class Field {
  // Deklaration der in Field gespeicherten Variablen
    row;
    column;
    figure;
    isHittable;
    realName;
    // speichert die globalen Variablen in den Variablen der Klasse Field; erstellt den "echten" Namen der Felder(nur für einfacheres Debuggen)
    constructor(row , column , figure) {
        this.row = row;
        this.column = column;
        this.figure = figure;

        this.realName = "" + String.fromCodePoint(64 + column) + this.row;
    }
    // Diese Funktion gibt die ID eines Feldes zurück
    get name() {
        return "" + this.column + this.row;
    }
    // Diese Funktion überprüft, ob sich ein feld tatsächlich auf dem Schachbrett befindet
    static isInBound(row, column) {
        return 0 < row && row <= 8 && 0 < column && column <= 8;
    }
    // Diese Funktion überprüft, ob sich eine Figur auf einem Feld befindet
    get isOccupied() {
        return this.figure !== undefined && this.figure.unicode !== "";
    }
    // Diese Funktion speichert begehbare Felder in der zugehörigen Figur
    markHittable(isHittable ) {
        this.isHittable = isHittable;
    }
    // Diese Funktion verändert eine Figur zu einer bestimmten neuen Figur
    changeFigure(newFigure){
        this.figure = newFigure;
    }
    // Diese Funktion dreht das gesammte Schachfeld
    inverse(){
        return new Field(9-this.row,9-this.column,undefined);
    }
}
