class De extends Element{
	constructor(col, lig, zIndex, nbFaces){
		super(col, lig, zIndex);
		this.setId('de')
		this.isDisplayed = false;
		//Nombre de face du dé
		this.nbFaces = nbFaces;
		//Face courante
		this.faceCourante = 0;
		//Obervers du dé
		this.observers = [];

		this.image = this.loadImage();
		this.setLargeurInitiale(this.image[0].width);
		this.setHauteurInitiale(this.image[0].height);
	}

	update(){

	}

	addObservers(o){
		this.observers.push(o);
	}

	notifyObservers(){
		for (let o of this.observers) {
			o.updateFaceCourante(this.faceCourante);
		}
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

	draw(context){
		if (this.isDisplayed) {
			context.drawImage(
				this.image[this.nbFaces - 1],
				this.x,
				this.y,
				this.largeur,
				this.hauteur
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
		];
		return images;
	}
}
