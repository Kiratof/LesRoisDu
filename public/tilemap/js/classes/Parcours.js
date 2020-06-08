class Parcours {

    constructor(tabDefis) {
        this.casesPosition = new Array();
        this.cases = new Array();
        this.map = "";
        this.toolBox = new ToolBox();
        this.listeDefis = tabDefis;

    }

    setMap(map){
  		this.map = map;
  	}

  	connectMap(map){
  		//Set la Map
  		this.setMap(map);


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

    isClicked(x, y){
      var isCaseClicked = false;

      this.cases.forEach(casess => {
        if (casess.isClicked(x,y)) {
          isCaseClicked = true;
        }
      });

      return isCaseClicked;
    }

    getClickedItem(x, y){
      var caseClicked = "";
      this.cases.forEach(casess => {
        if (casess.isClicked(x,y)) {
          caseClicked = casess;
        }
      });
      return caseClicked;
    }

    draw(context) {
        for (var i = 0; i < this.cases.length; i++) {
            this.cases[i].draw(context);
        }
    }

    getInfoCasesAround() {

		//On récupère la position des cases autour du pion
		var posTop = { col: this.pos.col, lig: this.pos.lig - 1 }
		var posRgt = { col: this.pos.col + 1, lig: this.pos.lig }
		var posBot = { col: this.pos.col, lig: this.pos.lig + 1 }
		var posLft = { col: this.pos.col - 1, lig: this.pos.lig }

		//On récupère l'ID des cases autour du pion
		var idTop = ToolBox.getIdTile(posTop.col, posTop.lig, this.map);
		var idRgt = ToolBox.getIdTile(posRgt.col, posRgt.lig, this.map);
		var idBot = ToolBox.getIdTile(posBot.col, posBot.lig, this.map);
		var idLft = ToolBox.getIdTile(posLft.col, posLft.lig, this.map);

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

  resizeLarger(){
    this.cases.forEach(casess => {
      casess.resizeLarger();
    });

  }
  resizeSmaller(){
    this.cases.forEach(casess => {
      casess.resizeSmaller();
    });
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
              //console.log(caseAround[0].id);
                if (caseAround[0].id === 1 && //Si la cases est une case du parcours
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
            //Créer les cases
            this.cases[i] = new Case(
                positionCases[i][0],
                positionCases[i][1],
                this.listeDefis[i]);
            this.cases[i].setMap(this.map);
        }
    }

    setWidthRatio(ratio){
      this.cases.forEach(i => {
        i.setWidthRatio(ratio);
      });
    }

    setHeightRatio(ratio){
      this.cases.forEach(i => {
        i.setHeightRatio(ratio);
      });
    }

    getHeightRatio(){
      this.cases.forEach(i => {
        return i.getHeightRatio();
      });
    }
    getWidthRatio(){
      this.cases.forEach(i => {
        return i.getWidthRatio();
      });
    }

    updateRatio(widthRatio, heightRatio){
      this.setWidthRatio(widthRatio);
      this.setHeightRatio(heightRatio);
    }


}
