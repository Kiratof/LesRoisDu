class Parcours {

    constructor(tabDefis) {
        this.casesPosition = new Array();
        this.cases = new Array();
        this.map = new Map();
        this.toolBox = new ToolBox();
        this.listeDefis = tabDefis;
        this.creerCasesDuParcours();

    }

    getCases(){
        return this.cases;
    }

    update() {

    }

    updateOnClick(x , y){
        this.cases.forEach(casess => {
            casess.updateOnClick(x,y);
        });
	}

    draw(context, map) {
        for (var i = 0; i < this.cases.length; i++) {
            this.cases[i].draw(context, map);
        }
    }

    getInfoCasesAround() {

		//On récupère la position des cases autour du pion
		var posTop = { col: this.pos.col, lig: this.pos.lig - 1 }
		var posRgt = { col: this.pos.col + 1, lig: this.pos.lig }
		var posBot = { col: this.pos.col, lig: this.pos.lig + 1 }
		var posLft = { col: this.pos.col - 1, lig: this.pos.lig }

		//On récupère l'ID des cases autour du pion
		var idTop = this.toolBox.getIdTile(posTop.col, posTop.lig, this.map);
		var idRgt = this.toolBox.getIdTile(posRgt.col, posRgt.lig, this.map);
		var idBot = this.toolBox.getIdTile(posBot.col, posBot.lig, this.map);
		var idLft = this.toolBox.getIdTile(posLft.col, posLft.lig, this.map);

		var CasesAround = [
			[{ id: idTop, pos: posTop }],
			[{ id: idRgt, pos: posRgt }],
			[{ id: idBot, pos: posBot }],
			[{ id: idLft, pos: posLft }]
		];

		//On retourne l'ID et la position des cases autour du pion
        return CasesAround;
    }

    isNextCaseWasMyLastOne(caseAround) {

		if (caseAround[0].pos.col == this.oldCol &&
			caseAround[0].pos.lig == this.oldLig) {
			return true;
		} else {
			return false;
		}
	}

	isNextCaseIsActuallyOnTheCanvas(caseAround) {

		if (caseAround[0].pos.col >= 0 &&
			caseAround[0].pos.col <= this.map.terrainWidth - 1 &&
			caseAround[0].pos.lig >= 0 &&
			caseAround[0].pos.lig <= this.map.terrainHeight - 1) {

			return true;
		} else {
			return false;
		}
	}

    getPositionCases() {
        var colonne = 0;
        var ligne = 0;
        this.oldCol = 0;
        this.oldLig = 0;
        this.pos = { col: colonne, lig: ligne };
        this.casesPosition.push(new Array(this.pos.col, this.pos.lig));
        var finParc = false;

        while (finParc == false) {

            finParc = true;
            var casesAround = this.getInfoCasesAround();

            casesAround.forEach(caseAround => { //Pour toutes les cases autour de ma case

                if (caseAround[0].id == 1 && //Si la cases est une case du parcours
                    !this.isNextCaseWasMyLastOne(caseAround) && // Et qu'elle n'était pas ma case précédente
                    this.isNextCaseIsActuallyOnTheCanvas(caseAround)) { //Et que la case est dans le canvas

                    finParc = false;

                    colonne = caseAround[0].pos.col;
                    ligne = caseAround[0].pos.lig;

                }

            });

            this.oldCol = this.pos.col;
            this.oldLig = this.pos.lig;

            this.pos.col = colonne;
            this.pos.lig = ligne;

            if (!finParc) {
                this.casesPosition.push(new Array(this.pos.col, this.pos.lig));
            }

        }
        return this.casesPosition;
    }

    creerCasesDuParcours() {

        var positionCases = this.getPositionCases();

        for (var i = 0; i < positionCases.length; i++) {
            this.cases[i] = new Case('case_128.png',
                this.listeDefis[i],
                positionCases[i][0],
                positionCases[i][1]);
        }
    }
}
