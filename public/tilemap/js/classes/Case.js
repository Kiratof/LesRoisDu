class Case {

	constructor(url, defi, colonne, ligne) {

		//Position dans la map
		this.colonne = colonne;
		this.ligne = ligne;

		this.map = new Map();

		//Position relative au Canvas
		this.x = this.colonne * 128;
		this.y = this.ligne * 128;
		this.z = 1;

		// Chargement de l'image dans l'attribut image
		this.image = new Image();
		this.image.referenceDuPerso = this;
		this.image.onload = function () {
			if (!this.complete) {
				throw "Erreur de chargement du sprite nommé \"" + url + "\".";
			}

			// Taille de la case
			this.referenceDuPerso.largeur = this.width;
			this.referenceDuPerso.hauteur = this.height;
		}

		this.defi = defi;
		this.image.src = assetsBaseDir + "sprites/" + url;


		this.pionObserved = [];
	}

	update() {
		this.displayDefi();
	}

	addPionObserved(pion) {
		this.pionObserved.push(pion);
	}

	isAnyPionSelected() {

		var isPionSelected = false;

		this.pionObserved.forEach(pion => {
			if (pion.isSelected) {
				isPionSelected = true;
			}

		});

		return isPionSelected;
	}

	draw(context) {
		context.drawImage(
			this.image,
			((this.colonne - 1) * this.map.TILE_WIDTH) + this.map.TILE_WIDTH,
			((this.ligne - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT,
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
			return false;
		}
		return clicked;

	}

	displayDefi() {
		alert(this.defi);
	}

}
