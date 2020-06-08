class Element{

	constructor(col, lig, zIndex){

		this.id = "";
		//Position du dé
		this.col = col;
		this.lig = lig;
		this.x = "";
		this.y = "";
		this.z = zIndex;
		this.map = "";

		this.images = this.loadImage();

		this.largeur = this.images[0].width;
		this.hauteur = this.images[0].height;
		this.ratio = 1;
}

	setRatio(ratio){
		this.ratio = ratio;
	}
	getRatio(){
		return this.ratio;
	}

	setMap(map){
		this.map = map;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

		//Positionne le dé
		this.setX(ToolBox.convertColToX(this.col, map.TILE_WIDTH));
		this.setY(ToolBox.convertLigToY(this.lig, map.TILE_HEIGHT));

	}

	setX(x){
		this.x = x;
	}
	getX(){
		return this.x;
	}
	setY(y){
		this.y = y;
	}
	getY(){
		return this.y;
	}

	setPosition(x, y){
		this.x = this.setX(x);
		this.Y = this.sety(y);
	}

	update(){

	}

	getClickedItem(x, y){
		if (this.isClicked(x,y)) {
			return this;
		}
	}

	addObservers(o){
		this.observers.push(o);
	}

	notifyObservers(){
		for (let o of this.observers) {
			o.updateFaceCourante(this.faceCourante);
		}
	}

	isClicked(x, y) {
		var myTop = this.y;
		var myRgt = this.x + this.largeur;
		var myBot = this.y + this.hauteur;
		var myLft = this.x;

		var clicked = true;
		if(y < myTop || y > myBot || x < myLft || x > myRgt)
		{
			return false;
		}
		return clicked;

	}

	lancerDe(){
		if (this.isDisplayed) {
			var faceObtenue = Math.floor(Math.random() * this.nbFaces) + 1;
			this.faceCourante = faceObtenue;
			alert("Vous avez obtenu : " + this.faceCourante);
			this.notifyObservers();
		}
	}

	toggleSwitch(){
		if (this.isDisplayed) {
			this.isDisplayed = false
		}else {
			this.isDisplayed = true;
		}
	}

	draw(context, map){
		if (this.isDisplayed) {
			context.drawImage(
				this.images[this.nbFaces - 1],
				this.x,
				this.y,
				this.largeur * this.ratio,
				this.hauteur * this.ratio
				);
		}
	}


	loadImage(){

		var images =
			[
				Graphics.newImage('sprites/large/De-1_128.png'),
				Graphics.newImage('sprites/large/De-2_128.png'),
				Graphics.newImage('sprites/large/De-3_128.png'),
				Graphics.newImage('sprites/large/De-4_128.png')
			]

		return images;
	}

	updateRatio(ratio){
		this.setRatio(ratio);
	}
}
