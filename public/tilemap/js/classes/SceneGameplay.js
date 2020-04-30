class SceneGameplay {

  constructor(objRes)
  {

    var nbCases = objRes.plateau_de_jeu.nbCases + 1;
    var map = 'plateau_' + nbCases +'_128';
    //Récupération des infos dans les fichiers JSON
    //Récupérations des informations relatives à la map
    //this.map = loadPlateau(this.idPlateau);
    this.map = new Map(map);

    //Récupérations des informations relatives aux défis

    //INITIALISATION DU PLATEAU
    //Récupération des défis
    var casesDuPlateau = objRes.plateau_de_jeu.cases;
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
    var nbPion = objRes.nbPionsParPlateau;
    var lesPions = objRes.plateau_de_jeu.pions;
    this.pions = [];
    for (let index = 0; index < nbPion; index++) {

        this.pions.push(new Pion(this.map, this.parcours, lesPions[index].player, lesPions[index].position, nbCases));

    }

    //INITIALISATION DU DE
    var nbFacesDe = objRes.nbFacesDe;
    this.dice = new De(nbFacesDe);

    //Gestionnaire d'évênement
    new InputHandler(this);

    const GAME_WIDTH =  this.map.getLargeur();
    const GAME_HEIGHT = this.map.getHauteur();
  canvas.width  = GAME_WIDTH;
    canvas.height = GAME_HEIGHT;

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
    console.log("update est appelé");

  }

  draw(){
    console.log("draw est appelé");
    ctx.clearRect(0, 0, GAME_WIDTH, GAME_HEIGHT);
    this.map.draw(ctx);
  }

}
