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

    this.widthRatio = 1;
    this.heightRatio = 1;

    //Création de l'objet contenant toutes les informations de la map
    var nomMap = 'Large/L_' + this.nbCases;
    this.map = new Map(nomMap);


    //Création du canvas dans le bloc correspondant
    this.createCanvas('tabs__content--active');

    //Création du background
    this.background = new Background(this.map.getLargeur(), this.map.getHauteur());

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
    this.parcours = new Parcours(defis);
    this.listeActeurs.push(this.parcours);
    this.parcours.connectMap(this.map);
    this.parcours.creerCasesDuParcours();
    this.cases = this.parcours.getCases();
    this.cases.forEach( i => {
      i.connectMap(this.map);
    });

    //Créer le/les pions
    this.pions = [];
    for (let index = 0; index < lesPions.length; index++) {
      var pion = new Pion(0, 0, 2, this.parcours, lesPions[index].player, lesPions[index].position, this.nbCases);
      pion.setPlateau(this.id);
      this.listeActeurs.push(pion);
      this.pions.push(pion);
    }

    //Chaque pion observe l'état du dé
    this.pions.forEach(pion => {
      this.dice.addObservers(pion);
    });

    //Tous les éléments du jeu on accès à la Map
    this.dice.connectMap(this.map);
    this.pions.forEach(pion => {
      pion.connectMap(this.map);
    });

    //Gestionnaire d'évênement
    this.mouse = new Mouse();
    new InputHandler(this.canvas, this.mouse);
    this.oldMouseState = this.mouse.getState();

    new ResizeHandler(this);
    this.sacrefonction();
  }

  load(){ // Chargement des images et des sons

  }

  update(){
    //Récupération des informations d'input
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

  createCanvas(htmlBlock){
    this.canvas = document.createElement('canvas');
    this.setCanvasSize(htmlBlock);

    var tabsToInsert = document.getElementById('plateau-' + this.id);
    tabsToInsert.appendChild(this.canvas);

    this.ctx = this.canvas.getContext("2d");
  }

  setCanvasSize(htmlBlock){
    var blockSize = this.getBlockSize(htmlBlock);
    this.canvas.width  = blockSize.width;
    this.canvas.height = blockSize.height;

  }

  getBlockSize(block){
    var blck = document.getElementsByClassName(block);
    var block_width = blck[0].clientWidth;
    var block_height = blck[0].clientHeight;

    return {
      width: block_width,
      height: block_height
    }
  }

  setWidthRatio(ratio){
    this.widthRatio = ratio;
  }

  getWidthRatio(){
    return this.widthRatio;
  }

  setHeightRatio(ratio){
    this.heightRatio = ratio;
  }

  getWidthRatio(){
    return this.widthRatio;
  }

  updateRatio(widthRatio, heightRatio){
    this.setWidthRatio(widthRatio);
    this.setHeightRatio(heightRatio);
    this.notifyRatioObservers();
  }

  notifyRatioObservers(){
    this.dice.updateOnResizing(this.widthRatio, this.heightRatio);
    this.pions.forEach( i => {
      i.updateOnResizing(this.widthRatio, this.heightRatio);
    });

    this.parcours.updateOnResizing(this.widthRatio, this.heightRatio);
    this.background.updateOnResizing(this.widthRatio, this.heightRatio);
  }

  sacrefonction(){
    var widthRatio = "";
    var heightRatio = "";
    var widthInitiale = this.map.getLargeur();
    var heightInitiale = this.map.getHauteur();

    this.setCanvasSize('tabs__content--active');
    widthRatio = this.canvas.width / widthInitiale;
    heightRatio = this.canvas.height / heightInitiale;
    this.updateRatio(widthRatio, heightRatio);
  }
}
