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
    this.mouse = new Mouse();
    new InputHandler(this.mouse);
    this.oldMouseState = this.mouse.getState();


    //Chaque pion observe l'état du dé
    this.pions.forEach(pion => {
        this.dice.addObservers(pion);
    });

  }

  load(){ // Chargement des images et des sons

  }

  update(){

    //Récupération des informations
    var newMouseState = this.mouse.getState();
    var leftClick = false;
    var xClick = -1;
    var yClick = -1;
    if (newMouseState.isCliked && !this.oldMouseState.isCliked){
      leftClick = true;
      xClick = newMouseState.X;
      yClick = newMouseState.Y;
    }
    this.oldMouseState = newMouseState;

    //Traitement des informations
    if (leftClick) {


      var listObjectClicked = [];
      this.listeActeurs.forEach(acteur => {

        if (acteur.isClicked(xClick, yClick)) {
          listObjectClicked.push(acteur.getClickedItem(xClick, yClick));
        }
      });


      var objectToUpdate = {
        z: 0,
      };
      listObjectClicked.forEach(object => {
        if (object.z > objectToUpdate.z) {
          objectToUpdate = object;
        }
      });


      switch (objectToUpdate.id) {
        case "case":
          objectToUpdate.displayDefi();
          break;

        case "pion":

          if (objectToUpdate.isSelected) {
            objectToUpdate.unselect();
            this.dice.toggleSwitch();

          }else {
            this.pions.forEach(pion => {
              pion.unselect();
            });
            objectToUpdate.select();

            if (!this.dice.isDisplayed) {
              this.dice.toggleSwitch();
            }
          }


          break;

        case "de":
        objectToUpdate.lancerDe();
        objectToUpdate.toggleSwitch();
          break;

        default:

      }
    }


  }

  draw(){
    this.ctx.clearRect(0, 0, this.GAME_WIDTH, this.GAME_HEIGHT);
    this.background.draw(this.ctx);
    this.listeActeurs.forEach(acteur => {
      acteur.draw(this.ctx);
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

  setMouseXPosition(x){
    this.mouse.setMouseXPosition(x);
  }

  setMouseYPosition(y){
    this.mouse.setMouseYPosition(y);
  }

}
