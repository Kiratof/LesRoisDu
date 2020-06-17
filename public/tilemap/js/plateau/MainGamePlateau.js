window.onload = function() {
	var deltaTime = 60;
	var mainGame = new MainGamePlateau();
	mainGame.initialize();
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

	initialize(){
			var PlateauJSON = Api.getPlateauJSON(url);
			var idPlateau = 1;
			var gameScene = new SceneGameplayPlateau(idPlateau, PlateauJSON);
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
