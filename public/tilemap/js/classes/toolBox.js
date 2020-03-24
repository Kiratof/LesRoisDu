/*
Classe SINGLETON
*/
class ToolBox {
    constructor() {

        if (ToolBox.instance instanceof ToolBox) {
            return ToolBox.instance;
        }

        Object.freeze(this);
        ToolBox.instance = this;
    }

    /*IL Y A DEUX GETJSON différent parce que je ne sais pour quelle raison,
     je n'arrive pas à récupérer l'attribut nom depuis l'objet parcours
    */
    getJsonDefi() {

        // Création de l'objet XmlHttpRequest
        var xhr = getXMLHttpRequest();

        // Chargement du fichier
        xhr.open("GET", assetsBaseDir + 'maps/' + 'defi' + '.json', false);
        xhr.send(null);
        if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)) // Code == 0 en local
            throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
        var mapJsonData = xhr.responseText;

        // Récupération des données 
        var mapData = JSON.parse(mapJsonData);

        return mapData;

    }

    getJson(nom) {

        // Création de l'objet XmlHttpRequest
        var xhr = getXMLHttpRequest();

        // Chargement du fichier
        xhr.open("GET", assetsBaseDir + 'maps/' + nom + '.json', false);
        xhr.send(null);
        if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)) // Code == 0 en local
            throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
        var mapJsonData = xhr.responseText;

        // Récupération des données 
        var mapData = JSON.parse(mapJsonData);

        return mapData;

    }

    getJsonFromApi(url) {

        var xhr = new XMLHttpRequest();

        // On souhaite juste récupérer le contenu du fichier, la méthode GET suffit amplement :
        xhr.open('GET', url);
    
        xhr.addEventListener('readystatechange', function() { // On gère ici une requête asynchrone
    
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) { // Si le fichier est chargé sans erreur
    
                console.log(this);
    
            }else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status != 200) {
    
                alert('Une erreur est survenue ! !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
            }
    
        });
    
        xhr.send(null); // La requête est prête, on envoie tout !

    }

    getJsonFromInside(url) {

        var xhr = new XMLHttpRequest();

        // On souhaite juste récupérer le contenu du fichier, la méthode GET suffit amplement :
        var url = assetsBaseDir + 'maps/' + url + '.json'
        xhr.open('GET', url);
    
        xhr.addEventListener('readystatechange', function() { // On gère ici une requête asynchrone
    
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) { // Si le fichier est chargé sans erreur
    
                //console.log(this);
    
            }else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status != 200) {
    
                alert('Une erreur est survenue ! !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
            }
    
        });
    
        xhr.send(null); // La requête est prête, on envoie tout !

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