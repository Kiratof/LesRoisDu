class Background{
	constructor(largeur, hauteur){

		this.largeurInitiale = largeur;
		this.hauteurInitiale = hauteur;
		this.largeur = 400;
		this.hauteur = 400;
		this.couleur = '#7da0a3';
	}

	setLargeurInitiale(largeur){
		this.largeurInitiale = largeur;
	}

	getLargeurInitiale(){
		return this.largeurInitiale;
	}

	setHauteurInitiale(hauteur){
		this.hauteurInitiale = hauteur;
	}

	getHauteurInitiale(){
		return this.hauteurInitiale;
	}

	setCouleur(couleur){
		this.couleur = couleur;
	}

	getCouleur(){
		return this.couleur;
	}

	setLargeur(largeur){
		this.largeur = largeur;
	}

	getLargeur(){
		return this.largeur;
	}

	setHauteur(hauteur){
		this.hauteur = hauteur;
	}

	getHauteur(){
		return this.hauteur;
	}

	draw(context){
		context.rect(0, 0, this.getLargeur(), this.getHauteur());
		context.fillStyle = this.getCouleur();
		context.fill();
	}


	updateOnResizing(widthRatio, heightRatio){
			this.updateSize(this, widthRatio, heightRatio);
	}

	updateSize(element, widthRatio, heightRatio){
		var largeur = element.getLargeurInitiale() * widthRatio;
		var hauteur =  element.getHauteurInitiale() * heightRatio;

		element.setLargeur(largeur);
		element.setHauteur(hauteur);
	}
}
