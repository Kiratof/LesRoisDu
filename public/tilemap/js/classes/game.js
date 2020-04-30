class Game {

    constructor()
    {
      
    }

    initialize(){
      var parametresPartieJSON = this.getParametresPartie();

      this.gameLoop();
    }

    load()
    {

    }


    update(deltaTime){

    }

    draw(ctx){
        this.map.draw(ctx);
    }

    gameLoop() {
        var lastTime = 0;
        function gameLoop(timestamp) {
          let deltaTime = timestamp - lastTime;
          lastTime = timestamp;

          ctx.clearRect(0, 0, game.GAME_WIDTH, game.GAME_HEIGHT);

          game.update(deltaTime);

          game.draw(ctx);

          requestAnimationFrame(gameLoop);
        }

        requestAnimationFrame(gameLoop);

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
