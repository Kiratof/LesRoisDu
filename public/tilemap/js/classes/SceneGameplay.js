class SceneGameplay {

  constructor(id, plateauJSON)
  {
    //Identifiant pour insertion dans le bon onglet
    this.id = id;

    //Liste de tous les acteurs du jeu
    this.listeActeurs = [];

    //Paramètres de la partie
    this.nbCases = plateauJSON.nombre_de_cases + 1;
    var nbFacesDe = plateauJSON.nombre_de_face_de;
    var casesDuPlateau = plateauJSON.cases;
    var lesPions = plateauJSON.pions;

    this.taille = this.setTaille('Large');

    //Création de l'objet contenant toutes les informations de la map
    var nomMap = 'Large/L_' + this.nbCases;
    this.map = new Map(nomMap);


    //Création du canvas
    this.createCanvas(this.map);

    //Création du background
    this.background = new Background(this.map);

    //Créer le dé
    var col = 0;
    var lig = 1;
    var zIndex = 2;
    this.dice = new De(col, lig, zIndex, nbFacesDe);
    this.listeActeurs.push(this.dice);

    //Créer le parcours
    var defis = [];
    const caseDepart = "Case départ, pas de défi !";
    defis.push(caseDepart);

    for (let index = 0; index < casesDuPlateau.length; index++) {
        const element = casesDuPlateau[index];
        defis.push(element.defi);
    }
    this.parcours = new Parcours(defis, this.map);
    this.listeActeurs.push(this.parcours);

    //Nos cases
    this.cases = this.parcours.getCases();

    //Créer le/les pions
    this.pions = [];
    for (let index = 0; index < lesPions.length; index++) {
        var pion = new Pion(this.parcours, lesPions[index].player, lesPions[index].position, this.nbCases);
        this.listeActeurs.push(pion);
        this.pions.push(pion);
    }

    //Gestionnaire d'évênement
    this.mouse = new Mouse();
    new InputHandler(this.canvas, this.mouse);
    this.oldMouseState = this.mouse.getState();

    //Taille du plateau
    new ResizeHandler(this);
    this.setPlateauSize();


    //Chaque pion observe l'état du dé
    this.pions.forEach(pion => {
        this.dice.addObservers(pion);
    });

    //Tous les éléments du jeu on accès à la Map
    this.dice.connectMap(this.map);
    this.pions.forEach(pion => {
        pion.connectMap(this.map);
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
    if (leftClick) { //Si cliqué
      //Récupération des objets cliqués
      var listObjectClicked = [];
      this.listeActeurs.forEach(acteur => {
        if (acteur.isClicked(xClick, yClick)) {
          listObjectClicked.push(acteur.getClickedItem(xClick, yClick));
        }
      });

      //Détermination de l'élément le plus proche selon l'index z
      var objectToUpdate = {
        z: 0,
      };
      listObjectClicked.forEach(object => {
        if (object.z > objectToUpdate.z) {
          objectToUpdate = object;
        }
      });
      //Comportement selon l'objet selectionné
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
            objectToUpdate.setPositionIntoAPI(objectToUpdate);


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
    this.canvas = document.createElement('canvas');
    this.setCanvasSize();

    var tabsToInsert = document.getElementById('plateau-' + this.id);
    tabsToInsert.appendChild(this.canvas);

    this.ctx = this.canvas.getContext("2d");

  }

  setCanvasSize(){
    this.canvas.width  = this.map.getLargeur();
    this.canvas.height = this.map.getHauteur();
  }

  setMouseXPosition(x){
    this.mouse.setMouseXPosition(x);
  }

  setMouseYPosition(y){
    this.mouse.setMouseYPosition(y);
  }

  resizePlateauSmaller(){
    //Modifier la taille des éléments
    //Canvas
    this.map.hydraterMap('Small/S_' + this.nbCases);
    this.setCanvasSize();
    //pions
    this.pions.forEach(pion => {
        pion.resizeSmaller();
        pion.setPositionXY();
    });
    //Background
    this.background.resizeTilesetSmaller();
    //cases
    this.cases.forEach(casess => {
        casess.resizeSmaller();

    });
    //Dé
    this.dice.resizeSmaller();
    this.dice.connectMap(this.map);

  }

  resizePlateauLarger(){
    //Modifier la taille des éléments
    //Canvas
    this.map.hydraterMap('Large/L_' + this.nbCases);
    this.setCanvasSize();
    //pions
    this.pions.forEach(pion => {
        pion.resizeLarger();
        pion.connectMap(this.map);

    });
    //Background
    this.background.resizeTilesetLarger();
    //cases
    this.cases.forEach(casess => {
        casess.resizeLarger();

    });
    //Dé
    this.dice.resizeLarger();
    this.dice.connectMap(this.map);

  }

  setPlateauSize(){

    var largeScreen = 737;

    //En fonction de la taille de l'écran
    if (screen.width <= largeScreen ) {
      this.resizePlateauSmaller();
    }else {
      this.resizePlateauLarger();
    }
  }

  setTaille(taille){

    this.taille = taille;
  }

  getTaille(){
    return this.taille;
  }

}
