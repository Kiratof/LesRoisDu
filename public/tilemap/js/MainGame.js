var urlPartie = 'http://localhost:8000/api/partie/';
var idPartie = document.getElementById('idPartie').innerHTML;
var assetsBaseDir = document.getElementById('baseDir').innerHTML;

window.onload = function() {
	var deltaTime = 60;
	var mainGame = new MainGame();
	mainGame.initialize(idPartie);
	mainGame.load();
	Api.setPositionPionAsyncInBoard();

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

	initialize(partie){
		var parametresPartieJSON = Api.getParametresPartieJSON(partie);
		for (var i = 0; i < parametresPartieJSON.nbPlateaux; i++) {
			var idPlateau = i + 1;
			var gameScene = new SceneGameplay(idPlateau, parametresPartieJSON.plateaux[i]);
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
			//Update uniquement du canvas affiché
			if (plateau.canvas.parentElement.className == 'tabs__content tabs__content--active') {
				plateau.update(deltaTime);
			}
		});
	}

	draw(){
		this.plateaux.forEach(plateau => {
			//Dessin uniquement du canvas affiché
			if (plateau.canvas.parentElement.className == 'tabs__content tabs__content--active') {
				plateau.draw();
			}
		});
	}
}
