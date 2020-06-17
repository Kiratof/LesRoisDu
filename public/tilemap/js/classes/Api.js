class Api {
  static getSmthSync() {
    var parametres = 'http://localhost:8000/api/partie/' + idPartie;

    // Création de l'objet XmlHttpRequest
    var xhr = getXMLHttpRequest();

    // Chargement du fichier
    xhr.open("GET", parametres, false);
    xhr.send(null);
    if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)){// Code == 0 en local
      throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
    }

    // Récupération des données
    var mapJsonData = xhr.responseText;
    //Parsing
    var mapData = JSON.parse(mapJsonData);
    return mapData;
  }

  static postPositionPionSync(pion) {

    var url = 'http://localhost:8000/api/partie/' + idPartie;

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

  static getSmthAsync(url) {

    var xhr = new XMLHttpRequest();

    xhr.addEventListener('readystatechange', function() { // On gère ici une requête asynchrone

      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) { // Si le fichier est chargé sans erreur

        return this.responseText;

      }else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status != 200) {
        alert('Une erreur est survenue ! !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
      }

    });

    // On souhaite juste récupérer le contenu du fichier, la méthode GET suffit amplement :
    xhr.open('GET', url);

    xhr.send(null); // La requête est prête, on envoie tout !
  }

  static getParametresPartieJSON(partieId) {

    var UrlApiPartie = 'http://localhost:8000/api/partie/' + partieId;

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
