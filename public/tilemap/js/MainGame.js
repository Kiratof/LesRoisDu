class MainGame {

	    constructor()
	    {

	    }

	    initialize(){
	      var parametresPartieJSON = this.getParametresPartie();
				this.gameScene = new SceneGameplay(parametresPartieJSON);
	    }

	    update(deltaTime){
				console.log("update est appelé");
				this.gameScene.update(deltaTime);
	    }

	    draw(ctx){
				console.log("draw est appelé");
				this.gameScene.draw(ctx);

	    }

	    getParametresPartie() {

	        var UrlApiPartie = 'http://localhost:8000/api/partie/' + idPartie;

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
