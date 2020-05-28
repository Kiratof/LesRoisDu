class De{

	constructor(faceDe){
		//Position du dé
		this.x = 0;
		this.y = 64;
		this.z = 2;
		this.id = "de";

		this.isDisplayed = false;

		//Nombre de face du dé
		this.nbFaces = faceDe;

		//Face courante
		this.faceCourante = 0;

		//Obervers du dé
		this.observers = [];

		// Chargement de l'image dans l'attribut image
		this.image = new Image();
		this.image.referenceDuPerso = this;
		this.image.onload = function() {
			if(!this.complete) {
				throw "Erreur de chargement du sprite";
			}
		// Taille du sprite De
		this.referenceDuPerso.largeur = this.width;
		this.referenceDuPerso.hauteur = this.height;
	}
	this.setSprite(faceDe);
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

	setSprite(nbFaceDe){

		switch (nbFaceDe) {
			case 1:
				this.image.src = assetsBaseDir + "sprites/large/De-1.png";
				break;

			case 2:
				this.image.src = assetsBaseDir + "sprites/large/De-2.png";
				break;

			case 3:
				this.image.src = assetsBaseDir + "sprites/large/De-3.png";
				break;

			case 4:
				this.image.src = assetsBaseDir + "sprites/large/De-4.png";
				break;

			default:

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
				this.image,
				this.x,
				this.y,
				this.largeur,
				this.hauteur
				);
		}
	}

	resizeSmaller(){

		switch (this.nbFaces) {
			case 1:
				this.image.src = assetsBaseDir + "sprites/small/De-1_64.png";
				break;

			case 2:
				this.image.src = assetsBaseDir + "sprites/small/De-2_64.png";
				break;

			case 3:
				this.image.src = assetsBaseDir + "sprites/small/De-3_64.png";
				break;

			case 4:
				this.image.src = assetsBaseDir + "sprites/small/De-4_64.png";
				break;

			default:

		}
	}

	resizeLarger(){

		switch (this.nbFaces) {
			case 1:
				this.image.src = assetsBaseDir + "sprites/large/De-1_64.png";
				break;

			case 2:
				this.image.src = assetsBaseDir + "sprites/large/De-2_64.png";
				break;

			case 3:
				this.image.src = assetsBaseDir + "sprites/large/De-3_64.png";
				break;

			case 4:
				this.image.src = assetsBaseDir + "sprites/large/De-4_64.png";
				break;

			default:

		}
	}
}
