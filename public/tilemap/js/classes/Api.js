class Api {


  static postPositionPionSync(pion) {

    var url = urlPartie + idPartie;

    // Création de l'objet XmlHttpRequest
    var xhr = getXMLHttpRequest();

    var plateau = pion.getPlateau() - 1;
    var pionPlayer = pion.getPlayer() - 1;
    var position = pion.getPosPion();

    var pion = {
      'plateau' : plateau,
      'player': pionPlayer,
      'position': position
    };

    var jsonString = JSON.stringify(pion);

    // Chargement du fichier
    xhr.open("POST", url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("$data=" + jsonString);
  }

  static getParametresPartieJSON(partieId) {

    var UrlApiPartie = urlPartie + partieId;

    // Création de l'objet XmlHttpRequest
    var xhr = getXMLHttpRequest();

    // Chargement du fichier
    xhr.open("GET", UrlApiPartie, false);
    xhr.send(null);
    if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)){// Code == 0 en local
      throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
    }

    //Récupération des données & parsing
    var donneesTexte = xhr.responseText;
    var donneesJSON = JSON.parse(donneesTexte);

    return donneesJSON;
  }

  static getPlateauJSON(url) {

    var UrlApiPartie = url;

    // Création de l'objet XmlHttpRequest
    var xhr = getXMLHttpRequest();

    // Chargement du fichier
    xhr.open("GET", UrlApiPartie, false);
    xhr.send(null);
    if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)){// Code == 0 en local
      throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
    }

    //Récupération des données & parsing
    var donneesTexte = xhr.responseText;
    var donneesJSON = JSON.parse(donneesTexte);

    return donneesJSON;
  }

}
