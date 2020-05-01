class Tileset{
	constructor(nomTileSet){

		//Chargement de l'image dans l'attribut image
		this.image = new Image();
		this.image.referenceDuTileset = this;
		this.image.onload = function(){
			if(!this.complete){
				throw new Error("Erreur de chargement du tileset nommé \"" + nomTileSet + "\".");
			}
			//Largeur du tileset et tile
			this.referenceDuTileset.largeur = this.width / 128;

		}

		this.image.src = assetsBaseDir + 'tilesets/' + nomTileSet;
	}


}
