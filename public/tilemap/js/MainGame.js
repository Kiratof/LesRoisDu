class MainGame {

	    constructor()
	    {

	    }

	    initialize(){
	      var parametresPartieJSON = this.getParametresPartieJSON();
				this.plateaux = [];
				for (var i = 0; i < 3; i++) {
					var gameScene = new SceneGameplay(parametresPartieJSON);
					this.plateaux.push(gameScene);
				}
				//this.gameScene = new SceneGameplay(parametresPartieJSON);
	    }

	    update(deltaTime){

				this.plateaux.forEach(plateau => {
					plateau.update(deltaTime);
				});


				//this.gameScene.update(deltaTime);
	    }

	    draw(){

				this.plateaux.forEach(plateau => {
					plateau.draw();
				});

				//this.gameScene.draw();
	    }

	    getParametresPartieJSON() {

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
