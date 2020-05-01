class SceneGameplay {

  constructor(parametresJSON)
  {
    //Liste de tous les acteurs du jeu
    this.listeActeurs = [];

    //Création de l'objet contenant toutes les informations de la map
    var nbCases = parametresJSON.plateau_de_jeu.nbCases + 1;
    var nomMap = 'plateau_' + nbCases +'_128';
    var map = new Map(nomMap);

    //Création du background
    this.background = new Background();

    //Créer le dé
    var nbFacesDe = parametresJSON.nbFacesDe;
    this.dice = new De(nbFacesDe);

    //Créer le parcours
    var casesDuPlateau = parametresJSON.plateau_de_jeu.cases;
    var defis = [];

    const caseDepart = "Case départ, pas de défi !";
    defis.push(caseDepart);

    for (let index = 0; index < casesDuPlateau.length; index++) {
        const element = casesDuPlateau[index];
        defis.push(element.defi);
    }
    this.parcours = new Parcours(defis);
    this.parcours.creerCasesDuParcours();
    //Nos cases
    this.cases = this.parcours.getCases();

    //Créer le/les pions
    var nbPion = parametresJSON.nbPionsParPlateau;
    var lesPions = parametresJSON.plateau_de_jeu.pions;
    this.pions = [];
    for (let index = 0; index < nbPion; index++) {

        this.pions.push(new Pion(this.parcours, lesPions[index].player, lesPions[index].position, nbCases));

    }




    //Gestionnaire d'évênement
    new InputHandler(this);


    this.GAME_WIDTH =  map.getLargeur();
    this.GAME_HEIGHT = map.getHauteur();
    canvas.width  = this.GAME_WIDTH;
    canvas.height = this.GAME_HEIGHT;

    //Ajout des observers
    this.pions.forEach(pion => {
        //Ajout des pion à la map pour pouvoir les dessiner

        //Chaque case observe l'état des pions
        this.cases.forEach(casess => {
            casess.addPionObserved(pion);
        })

    });

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
    this.background.draw(ctx);
  }

}
