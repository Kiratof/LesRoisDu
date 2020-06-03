class ToolBox {

  static convertXtoCol(x, tileWidth) {
		return Math.floor(x / tileWidth);
	}

	static convertYtoLig(y, tileHeight) {
		return Math.floor(y / tileHeight);
  }

  static convertColToX(col, tileWidth){
        return (col * tileWidth);
  }

  static convertLigToY(lig, tileHeight){
        return (lig * tileHeight);
  }

  static getIdTile(col, lig, map){
        var id = "";
        if (col>= 0 && col <= map.terrainWidth && lig >= 0 && lig<= map.terrainHeight)
        {
            id = map.terrain[(lig  * map.terrainWidth) + col];
        }
        return id;
  }

  static (col, lig, map){

  }

}
