//written by @AlexanderAisenbrey
class Color {
    // Variable zur Überprüfung der Figurenfarbe
    isWhiteVar;

    constructor(value) {
      // Überprüfung, ob nach einem  String oder einer boolean gefragt wird
        if (typeof value === "string")
            this.isWhiteVar = value === "white";
        else
            this.isWhiteVar = value === true;
    }
    // Diese Funktion überprüft die Farbe einer Figure
    get isWhite() {
        return this.isWhiteVar;
    }
    // Diese Funktion überprüft, ob eine gegebene Farbe der Farbe von "isWhiteVar" entspricht
    equals(color ) {
        return color !== undefined && color.isWhiteVar === this.isWhiteVar;
    }
    // Diese Funktion überprüft, ob eine gegebene Farbe nicht der Farbe von "isWhieVar" entspricht
    isOpponent(color ) {
        return color !== undefined && color.isWhiteVar !== this.isWhiteVar;
    }
}
