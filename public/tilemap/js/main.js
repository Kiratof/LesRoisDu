	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
    
    const GAME_WIDTH = 800;
    const GAME_HEIGHT = 600;
	canvas.width  = GAME_WIDTH;
	canvas.height = GAME_HEIGHT;


    var game = new Game(GAME_WIDTH, GAME_HEIGHT);

	var lastTime = 0;
	function gameLoop(timestamp) {
	  let deltaTime = timestamp - lastTime;
	  lastTime = timestamp;
	
	  ctx.clearRect(0, 0, GAME_WIDTH, GAME_HEIGHT);
	
	  game.start();
	  game.update(deltaTime);
	  game.draw(ctx);
	
	  requestAnimationFrame(gameLoop);
	}
	
	requestAnimationFrame(gameLoop);
