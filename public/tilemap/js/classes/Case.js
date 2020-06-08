class Case {

	constructor(colonne, ligne, defi) {

		this.id = "case";
		//Position dans la map
		this.colonne = colonne;
		this.ligne = ligne;

		this.map = "";

		//Position relative au Canvas
		this.x = "";
		this.y = "";
		this.z = 1;
		this.widthRatio = 1;
		this.heightRatio = 1;

		this.defi = defi;

		this.images = this.loadImage();
		this.largeur = this.images.width;
		this.hauteur = this.images.height;

	}

	setMap(map){
		this.map = map;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

		this.x = ToolBox.convertColToX(this.colonne, map.TILE_WIDTH);
		this.y = ToolBox.convertLigToY(this.ligne, map.TILE_HEIGHT);
	}

	update() {
		this.displayDefi();
	}


	draw(context) {
		context.drawImage(
			this.images,
			(((this.colonne - 1) * this.map.TILE_WIDTH) + this.map.TILE_WIDTH) *  this.widthRatio,
			(((this.ligne - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) *  this.heightRatio,
			this.largeur * this.widthRatio,
			this.hauteur * this.heightRatio
		);
	}

	isClicked(x, y) {
		var myTop = this.y;
		var myRgt = this.x + this.largeur;
		var myBot = this.y + this.hauteur;
		var myLft = this.x;

		var clicked = true;
		if (y < myTop || y > myBot || x < myLft || x > myRgt) {
			return false;
		}
		return clicked;

	}

	displayDefi() {
		alert(this.defi);
	}

	loadImage(){
		var images = Graphics.newImage('sprites/large/case_128.png');
		return images;
	}

	setWidthRatio(ratio){
		this.widthRatio = ratio;
	}
	getWidthRatio(){
		return this.widthRatio;
	}

	setHeightRatio(ratio){
		this.heightRatio = ratio;
	}
	getHeightRatio(){
		return this.heightRatio;
	}

	updateRatio(widthRatio, heightRatio){
		this.setWidthRatio(widthRatio);
		this.setHeightRatio(heightRatio);
	}

}
