class ToolBox {
    constructor() {

        if (ToolBox.instance instanceof ToolBox) {
            return ToolBox.instance;
        }

        this.json = 0;
        Object.freeze(this);
        ToolBox.instance = this;
    }

    convertXtoCol(x, tileWidth) {
		return Math.floor(x / tileWidth);
	}

	convertYtoLig(y, tileHeight) {
		return Math.floor(y / tileHeight);
    }

    convertColToX(object){
        return (object.col * object.map.TILE_WIDTH) + object.posXPlayer;
    }

    convertLigToY(){
        return (object.lig * object.map.TILE_WIDTH) + object.posYPlayer;
    }

    getIdTile(col, lig, map){
        var id = "undefined";
        if (col>= 0 && col <= map.terrainWidth && lig >= 0 && lig<= map.terrainHeight)
        {
            id = map.terrain[(lig  * map.terrainWidth) + col];
        }
        return id;
    }

}
