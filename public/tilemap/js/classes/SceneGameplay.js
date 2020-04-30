class SceneGameplay {

  constructor(parametresJSON)
  {
    //Liste de tous les acteurs du jeu
    this.listeActeur = [];

    var nbCases = parametresJSON.plateau_de_jeu.nbCases + 1;
    var map = 'plateau_' + nbCases +'_128';
    //Récupération des infos dans les fichiers JSON
    //Récupérations des informations relatives à la map
    //this.map = loadPlateau(this.idPlateau);
    this.map = new Map(map);

    //Récupérations des informations relatives aux défis

    //INITIALISATION DU PLATEAU
    //Récupération des défis
    var casesDuPlateau = parametresJSON.plateau_de_jeu.cases;
    var defis = [];

    const caseDepart = "Case départ, pas de défi !";
    defis.push(caseDepart);

    for (let index = 0; index < casesDuPlateau.length; index++) {
        const element = casesDuPlateau[index];
        defis.push(element.defi);
    }
    this.parcours = new Parcours(defis, this.map);
    this.parcours.creerCasesDuParcours();
    //Nos cases
    this.cases = this.parcours.getCases();

    //INITIALISATION DU/DES PIONS(S)
    var nbPion = parametresJSON.nbPionsParPlateau;
    var lesPions = parametresJSON.plateau_de_jeu.pions;
    this.pions = [];
    for (let index = 0; index < nbPion; index++) {

        this.pions.push(new Pion(this.map, this.parcours, lesPions[index].player, lesPions[index].position, nbCases));

    }

    //INITIALISATION DU DE
    var nbFacesDe = parametresJSON.nbFacesDe;
    this.dice = new De(nbFacesDe);

    //Gestionnaire d'évênement
    new InputHandler(this);

    this.GAME_WIDTH =  this.map.getLargeur();
    this.GAME_HEIGHT = this.map.getHauteur();
    canvas.width  = this.GAME_WIDTH;
    canvas.height = this.GAME_HEIGHT;

    //Ajout des observers
    this.pions.forEach(pion => {
        //Ajout des pion à la map pour pouvoir les dessiner
        this.map.addPion(pion);

        //Chaque case observe l'état des pions
        this.cases.forEach(casess => {
            casess.addPionObserved(pion);
        })

    });

    this.map.setDice(this.dice);
    this.map.setParcours(this.parcours);

    //Chaque pion observe l'état du dé
    this.pions.forEach(pion => {
        this.dice.addObservers(pion);
    });

  }

  load(){

  }

  update(){

  }

  draw(){
    ctx.clearRect(0, 0, this.GAME_WIDTH, this.GAME_HEIGHT);
    this.map.draw(ctx);
  }

}
