//written by @AlexanderAisenbrey
class Figure {
    // erstellen der FIGURETYPES zur einfacheren Erkennung der Figuren ohne bestimmte Farbe
    static FIGURETYPES = {
        Pawn: 0,
        Rook: 1,
        Knight: 2,
        Bishop: 3,
        Queen: 4,
        King: 5
    }
    // Erstellen der lokalen Variablen der Figur
    unicode;
    figureType;
    color;
    hittableFields;
    // Gleichsetzten der lokalen Variablen mit globalen Variablen
    constructor(unicodepoint) {
        this.unicode = unicodepoint;
        this.color = this.getColor();
        this.figureType = this.getFigure();
    }
    // Diese Funktion gibt den figureType eines Unicodes zuzück
    getFigure() {
        switch (this.unicode) {
            case "♖":
            case "♜":
                return Figure.FIGURETYPES.Rook;
            case "♘":
            case "♞":
                return Figure.FIGURETYPES.Knight;
            case "♗":
            case "♝":
                return Figure.FIGURETYPES.Bishop;
            case "♕":
            case "♛":
                return Figure.FIGURETYPES.Queen;
            case "♔":
            case "♚":
                return Figure.FIGURETYPES.King;
            case "♟":
            case "♙":
                return Figure.FIGURETYPES.Pawn;
            default:
                return null;
        }
    }
    // Diese Funktion bestimmt die Farbe eines Unicodes
    getColor() {
        switch (this.unicode) {
            case "♖":
            case "♘":
            case "♗":
            case "♕":
            case "♔":
            case "♙":
                return new Color("white");
            case "♜":
            case "♞":
            case "♝":
            case "♛":
            case "♚":
            case "♟":
                return new Color("black");
            default:
                return undefined;
        }
    }
}
