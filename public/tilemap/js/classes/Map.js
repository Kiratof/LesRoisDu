class Map {

	constructor(nomMap) {

		this.terrain = 0;
		this.terrainHeight = 0;
		this.terrainWidth = 0;
		this.TILE_HEIGHT = 0;
		this.TILE_WIDTH = 0;

		this.hydraterMap(nomMap);
	}

	hydraterMap(nom){

		var mapData = this.getMapJSON(nom)

		this.terrain = mapData.layers[0].data;
		this.terrainHeight = mapData.layers[0].height;
		this.terrainWidth = mapData.layers[0].width;
		this.TILE_HEIGHT = mapData.tileheight;
		this.TILE_WIDTH = mapData.tilewidth;
	}

	// Pour récupérer la taille (en tiles) de la carte
	getLargeur() {
		return this.terrainWidth * this.TILE_WIDTH;
	}
	getHauteur() {
		return this.terrainHeight * this.TILE_HEIGHT;
	}




	update(deltaTime) {
		// Update des cases
		for (var i = 0, l = this.cases.length; i < l; i++) {
			this.cases[i].update(deltaTime);
		}
	}

	draw(context) {

		//Dessin du tileset
		var nbLignes = this.terrainHeight;
		var ligne = 0;
		var colonne = 0;
		for (ligne; ligne < nbLignes; ligne++) {

			for (var colonne = 0, nbColonne = this.terrainWidth; colonne < nbColonne; colonne++) {
				var tuile = this.terrain[(ligne * nbColonne) + colonne];
				this.tileset.draw(tuile, context, this, colonne * this.TILE_HEIGHT, ligne * this.TILE_HEIGHT);
			}
		}

		this.dice.draw(context, this);

		this.parcours.draw(context, this);

		// Dessin des pions
		for (var i = 0, l = this.pions.length; i < l; i++) {
			this.pions[i].draw(context);
		}
	}

	getMapJSON(nom) {

			// Création de l'objet XmlHttpRequest
			var xhr = getXMLHttpRequest();

			// Chargement du fichier
			xhr.open("GET", assetsBaseDir + 'plateaux/' + nom + '.json', false);
			xhr.send(null);
			if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)) // Code == 0 en local
					throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
			var mapJsonData = xhr.responseText;

			// Récupération des données
			var mapData = JSON.parse(mapJsonData);

			return mapData;

	}

}
