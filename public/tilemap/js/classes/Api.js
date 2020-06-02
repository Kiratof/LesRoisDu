class Api {

    static getSmthSync(nom) {
      var parametres = 'http://localhost:8000/api/partie/' + idPartie;

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

    static postPositionPionSync(nom) {

      var parametres = 'http://localhost:8000/api/partie/' + idPartie;

        // Création de l'objet XmlHttpRequest
        var xhr = getXMLHttpRequest();

        // Chargement du fichier
        xhr.open("POST", parametres, false);
        xhr.send(null);
        if (xhr.readyState != 4 || (xhr.status != 200 && xhr.status != 0)) // Code == 0 en local
            throw new Error("Impossible de charger la carte nommée \"" + nom + "\" (code HTTP : " + xhr.status + ").");
        var mapJsonData = xhr.responseText;

        // Récupération des données
        var mapData = JSON.parse(mapJsonData);

        return mapData;

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

}
