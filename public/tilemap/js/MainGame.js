window.onload = function() {

	var deltaTime = 60;
	var mainGame = new MainGame();
	mainGame.initialize();
	mainGame.load();

	//GAMELOOP
	setInterval(function() {
		mainGame.update(deltaTime);
		mainGame.draw();
	}, deltaTime);
}

class MainGame {

	    constructor()
	    {
					this.plateaux = [];
	    }

	    initialize(){
	      var parametresPartieJSON = Api.getParametresPartieJSON(idPartie);

				for (var i = 0; i < parametresPartieJSON.nbPlateaux; i++) {
					var gameScene = new SceneGameplay(i + 1, parametresPartieJSON.plateaux[i]);
					this.plateaux.push(gameScene);
				}
	    }

			load(){
				this.plateaux.forEach(plateau => {
					plateau.load();
				});
			}

	    update(deltaTime){

				this.plateaux.forEach(plateau => {
					plateau.update(deltaTime);
				});

	    }

	    draw(){

				this.plateaux.forEach(plateau => {
					plateau.draw();
				});

	    }

	}
