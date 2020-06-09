class Case extends Element {
	constructor(col, lig, zIndex, defi) {
		super(col, lig, zIndex);
		this.id = "case";
		this.defi = defi;
		this.image = this.loadImage();
		this.setLargeurInitiale(this.image.width);
		this.setHauteurInitiale(this.image.height);
	}

	update() {
		this.displayDefi();
	}

	draw(context) {
		context.drawImage(
			this.image,
			this.x,
			this.y,
			this.largeur,
			this.hauteur
		);
	}

	displayDefi() {
		alert(this.defi);
	}

	loadImage(){
		var image = Graphics.newImage('sprites/large/case_128.png');
		return image;
	}
}
