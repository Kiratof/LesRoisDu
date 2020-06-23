var idPlateau = document.getElementById('idPlateau').innerHTML;
var url = 'http://localhost:8000/api/plateaux/' + idPlateau;


window.onload = function() {
	var deltaTime = 60;
	var mainGame = new MainGamePlateau();
	mainGame.initialize(url);
	mainGame.load();

	//GAMELOOP
	setInterval(function() {
		mainGame.update(deltaTime);
		mainGame.draw();
	}, deltaTime);
}

class MainGamePlateau {
	constructor()
	{
		this.plateaux = [];
	}

	initialize(urlPlateau){
			var PlateauJSON = Api.getPlateauJSON(urlPlateau);
			var ingameIdPlateau = 1;
			var gameScene = new SceneGameplayPlateau(ingameIdPlateau, PlateauJSON);
			this.plateaux.push(gameScene);
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
