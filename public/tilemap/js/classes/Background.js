class Background{
	constructor(url){

		//Chargement de la map
		this.map = new Map();

    //Chargement du Tileset composant le background
    var nomTileSet = 'Octogone_128.png'
    this.tileset = new Tileset(nomTileSet);

	}

	draw(context){

		//Dessin du tileset
		var nbLignes = this.map.terrain.length / this.map.terrainWidth;
		var ligne = 0;
		var colonne = 0;


		for (ligne; ligne < nbLignes; ligne++) {

			for (var colonne = 0, nbColonne = this.map.terrainWidth; colonne < nbColonne; colonne++) {

				var tuile = this.map.terrain[(ligne * nbColonne) + colonne];
				var xDestination = colonne * this.map.TILE_HEIGHT;
				var yDestination = ligne * this.map.TILE_HEIGHT;

				var xSourceEnTiles = tuile % this.tileset.largeur;
				if(xSourceEnTiles == 0){
					xSourceEnTiles = this.tileset.largeur;
				}
				var ySourceEnTiles = Math.ceil(tuile / this.tileset.largeur);
				var xSource = (xSourceEnTiles - 1) * this.map.TILE_WIDTH;
				var ySource = (ySourceEnTiles - 1) * this.map.TILE_HEIGHT;


				context.drawImage(
					this.tileset.image,
					xSource,
					ySource,
					this.map.TILE_WIDTH,
					this.map.TILE_HEIGHT,
					xDestination,
					yDestination,
					this.map.TILE_WIDTH,
					this.map.TILE_HEIGHT);
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

	getNumCase(x, y, map){
		return position = {
			col: Math.floor(x/map.TILE_WIDTH),
			lig: Math.floor(y/map.TILE_HEIGHT)
		}
	}

}
