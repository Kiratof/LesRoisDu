class Background{
	constructor(map){

		//Chargement de la map
		this.map = "";

		this.ratio = 1;

    //Chargement du Tileset composant le background
		this.tileset = this.loadImage();
		this.largeur = this.tileset.width;
		this.hauteur = this.tileset.height;

	}

	setRatio(ratio){
		this.ratio = ratio;
	}
	getRatio(){
		return this.ratio;
	}

	updateRatio(ratio){
		this.setRatio(ratio);
	}

	setMap(map){
		this.map = map;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

	}

	draw(context){

		//Dessin du tileset
		var nbLignes = this.map.terrain.length / this.map.terrainWidth;
		var nbColonne = this.map.terrainWidth
		var ligne = 0;


		for (ligne; ligne < nbLignes; ligne++) {

			for (var colonne = 0; colonne < nbColonne; colonne++) {

				var tuile = this.map.terrain[(ligne * nbColonne) + colonne];
				var xDestination = colonne * this.map.TILE_WIDTH;
				var yDestination = ligne * this.map.TILE_HEIGHT;

				var xSourceEnTiles = tuile % this.tileset.width;
				if(xSourceEnTiles == 0){
					xSourceEnTiles = this.tileset.width;
				}
				var ySourceEnTiles = Math.ceil(tuile / this.tileset.width);
				var xSource = (xSourceEnTiles - 1) * this.map.TILE_WIDTH;
				var ySource = (ySourceEnTiles - 1) * this.map.TILE_HEIGHT;

				// context.drawImage(
				// 	this.tileset,
				// 	xSource,
				// 	ySource,
				// 	this.map.TILE_WIDTH,
				// 	this.map.TILE_HEIGHT,
				// 	xDestination,
				// 	yDestination,
				// 	this.largeur * this.ratio,
				// 	this.hauteur * this.ratio
				// );
			}
		}
	}



	getIdTile(col, lig, map){
		var id = "undefined";
		if (col>= 0 && col <= map.terrainWidth && lig >= 0 && lig<= map.terrainHeight)
		{
			id = map.terrain[(lig  * map.terrainWidth) + col];
		}

		return id;

	}

	setTaille(taille){
		this.taille = taille;
	}

	getNumCase(x, y, map){
		return position = {
			col: Math.floor(x/map.TILE_WIDTH),
			lig: Math.floor(y/map.TILE_HEIGHT)
		}
	}

	loadImage(){
		var images = Graphics.newImage('tilesets/case_128.png');
		return images;
	}
}
