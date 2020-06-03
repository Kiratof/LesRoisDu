class Background{
	constructor(map){

		//Chargement de la map
		this.map = map;

    //Chargement du Tileset composant le background
		this.taille = 'large';
		this.tileset = this.loadImage();
		console.log(this.tileset[this.taille].width);

	}

	draw(context){

		//Dessin du tileset
		var nbLignes = this.map.terrain.length / this.map.terrainWidth;
		var ligne = 0;
		var colonne = 0;


		for (ligne; ligne < nbLignes; ligne++) {

			for (var colonne = 0, nbColonne = this.map.terrainWidth; colonne < nbColonne; colonne++) {

				var tuile = this.map.terrain[(ligne * nbColonne) + colonne];
				var xDestination = colonne * this.map.TILE_WIDTH;
				var yDestination = ligne * this.map.TILE_HEIGHT;

				var xSourceEnTiles = tuile % this.tileset[this.taille].width;
				if(xSourceEnTiles == 0){
					xSourceEnTiles = this.tileset[this.taille].width;
				}
				var ySourceEnTiles = Math.ceil(tuile / this.tileset[this.taille].width);
				var xSource = (xSourceEnTiles - 1) * this.map.TILE_WIDTH;
				var ySource = (ySourceEnTiles - 1) * this.map.TILE_HEIGHT;

				context.drawImage(
					this.tileset[this.taille],
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

	setTaille(taille){
		this.taille = taille;
	}

	getNumCase(x, y, map){
		return position = {
			col: Math.floor(x/map.TILE_WIDTH),
			lig: Math.floor(y/map.TILE_HEIGHT)
		}
	}

	resizeTilesetSmaller(){
		this.setTaille('small');
	}
	resizeTilesetLarger(){
		this.setTaille('large');
	}

	loadImage(){
		var images = {
			'small' : Graphics.newImage('tilesets/case_64.png'),
			'large' : Graphics.newImage('tilesets/case_128.png')
		}

		return images;
	}
}
