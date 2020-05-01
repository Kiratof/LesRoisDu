class Pion {
	constructor(parcours, player, position, nbCases) {
		//Petit toolBox des familles
		this.toolBox = new ToolBox();

		//Informations de la map
		this.map = new Map();

		this.nbCases = nbCases;

		this.player = player;
		//Position dans le canvas
		this.setPlayer(player);

		this.setPosition(position);
		this.posCases = parcours.casesPosition;
		this.positionnePionByPositionDansParcours();
		this.updateXandYposition();

		//Position du pion avant le déplacement
		this.oldCol = 0;
		this.oldLig = 0;

		//Prochaine case où il faut se déplacer
		this.nextCol = 0;
		this.nextLig = 0;

		//Etat du pion
		this.isSelected = false;

		//Etat du dé
		this.faceCouranteDe = 0;

		// Chargement de l'image dans l'attribut image
		this.image = new Image();
		this.image.referenceDuPerso = this;
		this.image.onload = function () {
			if (!this.complete)
				throw "Erreur de chargement du sprite nommé \"" + url + "\".";

			// Taille du pion
			this.referenceDuPerso.largeur = this.width;
			this.referenceDuPerso.hauteur = this.height;
		}

		this.image.src = assetsBaseDir + "sprites/" + "pion_" + this.couleur + ".png";

		this.compteur = 0;

	}



	setPlayer(player){

		switch (player) {
			case 1:
				this.posXPlayer = 32 - 32 / 2;
				this.posYPlayer = 32 - 32 / 2;
				this.couleur = 'vert';
				break;

			case 2:
				this.posXPlayer = 96 - 32 / 2;
				this.posYPlayer = 32 - 32 / 2;
				this.couleur = 'rouge';
				break;


			case 3:
				this.posXPlayer = 32 - 32 / 2;
				this.posYPlayer = 96 - 32 / 2;
				this.couleur = 'jaune';
				break;

			case 4:
				this.posXPlayer = 96 - 32 / 2;
				this.posYPlayer = 96 - 32 / 2;
				this.couleur = 'bleu';
				break;

			default:
				alert('Il ne peut exister de joueur ' + player + '.');
				break;
		}
	}

	setPosition(position){
			this.posPion = position;
	}

	update() {

		if (this.isSelected) {
			this.advanceBasedOnPawnValue();
		}

		this.updateXandYposition();
		this.setPositionIntoAPI(this.posPion, this.player);
	}

	updatePos() {
		this.compteur = this.compteur + 1;
		if ( this.compteur == 300) {
			fetch(parametres)
			.then(response => {
				return response.json()
			})
			.then(data => {
					var posPion = data["plateau_de_jeu"]["pions"][this.player-1]["position"];
					this.setPosition(posPion);
					this.goToNextCase();
			})
			this.compteur = 0;
		}
	}

	updateOnClick(x, y) {
		if (this.isClicked(x, y)) {

			this.isSelected = true;
			this.showMeSelected();

		}else{

			this.isSelected = false;
			this.showMeNormally();

		}
	}

	updateFaceCourante(faceCourante) {

		this.faceCouranteDe = faceCourante;
		this.update();

	}

	draw(context) {
		context.drawImage(
			this.image,
			(((this.col - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posXPlayer,
			(((this.lig - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posYPlayer,
			this.largeur,
			this.hauteur
		);
	}

	isClicked(x, y) {
		var myTop = this.y;
		var myRgt = this.x + this.largeur;
		var myBot = this.y + this.hauteur;
		var myLft = this.x;

		var clicked = true;
		if (y < myTop || y > myBot || x < myLft || x > myRgt) {
			clicked = false;
		}
		return clicked;

	}
	setCol(col) {
		this.col = col;
	}

	setLig(lig) {
		this.lig = lig;
	}

	teleportToCase(col, lig) {

		//On change de position
		this.setCol(col);
		this.setLig(lig);


	}

	goToNextCase() {

		var posCol = this.map.parcours.casesPosition[this.posPion][0];
		var posLig = this.map.parcours.casesPosition[this.posPion][1];

		this.teleportToCase(posCol, posLig);

	}

	advanceBasedOnPawnValue() {

		if (this.posPion >= this.nbCases - 1) {
			alert('STOP ! Vous êtes arrivé au bout du parcours !');
		} else {

			alert('Vous avez obtenu ' + this.faceCouranteDe + '.');

			for (let i = 0; i < this.faceCouranteDe; i++) {

				if (this.posPion < this.nbCases - 1) {

					this.posPion = this.posPion + 1;
					this.goToNextCase();
				}

				if (this.posPion == this.nbCases - 1) {

					this.goToNextCase();

					alert('Bravo ! Vous avez terminé le parcours !');

					break;
				}

			}
		}
	}

	showMeSelected() {
		this.image.src = assetsBaseDir + "sprites/" + "pion_" + this.couleur + "_selected.png";
	}

	showMeNormally() {
		this.image.src = assetsBaseDir + "sprites/" + "pion_" + this.couleur + ".png";
	}

	positionnePionByPositionDansParcours(){

		if (this.posPion > 0) {

			var col = this.posCases[this.posPion][0];
			var lig = this.posCases[this.posPion][1];
			this.col = col;
			this.lig = lig;

		} else {
			this.col = 0;
			this.lig = 0;
		}

	}

	setPositionIntoAPI(position, player){

		if(position > this.nbCases){
			var pos = this.nbCases-1;
		}
		else{
			var pos = position;
		}

		var pionstab = [{'player': player, 'placement': pos}];

		var jsonString = JSON.stringify({pions: pionstab});

		$.ajax({
	        type: "POST",
	        url: parametres,
	        data: "$data="+jsonString
    	});
	}

	updateXandYposition(){
		//On met à jour la position
		this.x = (this.col * this.map.TILE_WIDTH) + this.posXPlayer;
		this.y = (this.lig * this.map.TILE_HEIGHT) + this.posYPlayer;
	}

}
