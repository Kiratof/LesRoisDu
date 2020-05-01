class SceneGameplay {

  constructor(parametresJSON)
  {
    //Liste de tous les acteurs du jeu
    this.listeActeurs = [];

    //Paramètres de la partie
    var nbCases = parametresJSON.plateau_de_jeu.nbCases + 1;
    var nbFacesDe = parametresJSON.nbFacesDe;
    var casesDuPlateau = parametresJSON.plateau_de_jeu.cases;
    var nbPion = parametresJSON.nbPionsParPlateau;
    var lesPions = parametresJSON.plateau_de_jeu.pions;


    //Création de l'objet contenant toutes les informations de la map
    var nomMap = 'plateau_' + nbCases +'_128';
    var map = new Map(nomMap);


    //Création du canvas
    this.createCanvas(map);

    //Création du background
    this.background = new Background();

    this.listeActeurs.push(this.background);

    //Créer le dé
    this.dice = new De(nbFacesDe);
    this.listeActeurs.push(this.dice);

    //Créer le parcours
    var defis = [];
    const caseDepart = "Case départ, pas de défi !";
    defis.push(caseDepart);

    for (let index = 0; index < casesDuPlateau.length; index++) {
        const element = casesDuPlateau[index];
        defis.push(element.defi);
    }
    this.parcours = new Parcours(defis);
    this.listeActeurs.push(this.parcours);

    //Nos cases
    this.cases = this.parcours.getCases();

    //Créer le/les pions
    this.pions = [];
    for (let index = 0; index < nbPion; index++) {

        var pion = new Pion(this.parcours, lesPions[index].player, lesPions[index].position, nbCases);
        this.listeActeurs.push(pion);
        this.pions.push(pion);

    }

    //Gestionnaire d'évênement
    new InputHandler(this);

    //Ajout des observers
    this.pions.forEach(pion => {
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

  load(){ // Chargement des images et des sons

  }

  update(){

  }

  draw(){
    this.ctx.clearRect(0, 0, this.GAME_WIDTH, this.GAME_HEIGHT);
    this.listeActeurs.forEach(item => {
      item.draw(this.ctx);
    });


  }

  createCanvas(map){
    var canvas = document.createElement('canvas');
    this.setCanvasSize(canvas, map);

    var body = document.getElementById('canvas');
    body.appendChild(canvas);

    this.ctx = canvas.getContext("2d");

  }

  setCanvasSize(canvas, map){
    canvas.width  = map.getLargeur();
    canvas.height = map.getHauteur();
  }

}
