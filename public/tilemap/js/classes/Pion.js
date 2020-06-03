class Pion {
	constructor(parcours, player, position, nbCases) {
		//Informations de la map
		this.map = "";

		this.nbCases = nbCases;
		this.parcours = parcours;
		this.compteur = 0;
		this.player = player;


		this.setPosition(position);

		this.posCases = parcours.casesPosition;
		this.positionnePionByPositionDansParcours();

		this.z = 2;
		this.id = "pion";

		//Position du pion avant le déplacement
		this.oldCol = 0;
		this.oldLig = 0;

		//Prochaine case où il faut se déplacer
		this.nextCol = 0;
		this.nextLig = 0;

		//Etat du pion
		this.isSelected = false;
		this.setCouleur(player);
		//Etat du dé
		this.faceCouranteDe = 0;

		// Chargement de l'image dans l'attribut image
		this.taille = 'small';
		this.state = 'unselect';
		this.images = this.loadImage();
		this.largeur = this.images[this.taille][this.state].width;
		this.hauteur = this.images[this.taille][this.state].height;
	}

	setMap(map){
		this.map = map;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

		//Positionne le dé
    this.setPositionXY();

	}


	setCouleur(player){
		switch (player) {
			case 1:
				this.couleur = 'vert';
				break;

			case 2:
				this.couleur = 'rouge';
				break;

			case 3:
				this.couleur = 'jaune';
				break;

			case 4:
				this.couleur = 'bleu';
				break;

			default:
				alert('Il ne peut exister de joueur ' + player + '.');
				break;
		}
	}

	premierQuart(tailleOctogone, taillePion) {
	   var quart = ((1 * tailleOctogone) - 2 * taillePion) / 4;
	  return quart;
	}

	troisiemeQuart(tailleOctogone, taillePion) {

   	var quart = ((3 * tailleOctogone) - 2 * taillePion) / 4;

  	return quart;
	}

	setPositionXY(){
		switch (this.player) {
			case 1:
				this.posXPlayer = this.premierQuart(this.map.TILE_WIDTH, this.largeur);
				this.posYPlayer = this.premierQuart(this.map.TILE_HEIGHT, this.hauteur);
				break;

			case 2:
				this.posXPlayer = this.troisiemeQuart(this.map.TILE_WIDTH, this.largeur);
				this.posYPlayer = this.premierQuart(this.map.TILE_HEIGHT, this.hauteur);
				break;


			case 3:
				this.posXPlayer = this.premierQuart(this.map.TILE_WIDTH, this.largeur);
				this.posYPlayer = this.troisiemeQuart(this.map.TILE_HEIGHT, this.hauteur);
				break;

			case 4:
				this.posXPlayer = this.troisiemeQuart(this.map.TILE_WIDTH, this.largeur);
				this.posYPlayer = this.troisiemeQuart(this.map.TILE_HEIGHT, this.hauteur);
				break;

			default:
				alert('Il ne peut exister de joueur ' + player + '.');
		}

		this.updateXandYposition();
	}

	setPosition(position){
			this.posPion = position;
	}

	update() {

		if (this.isSelected) {
			this.advanceBasedOnPawnValue();
			this.updateXandYposition();
			this.unselect();
		}
	}

	toggleSwitch(){
		if (!this.isSelected) {
			this.isSelected = true;
		}else{
			this.isSelected = false;
		}
	}

	select(){
		this.isSelected = true;
	}
	unselect(){
		this.isSelected = false;
	}

	updateFaceCourante(faceCourante) {
		this.faceCouranteDe = faceCourante;
		this.update();
	}

	updatePos() {
		this.compteur = this.compteur + 1;
		if ( this.compteur == 300) {
			fetch(parametres)
			.then(response => {
				return response.json()
			})
			.then(data => {
					var posPion = data["plateau_de_jeu"]["pions"][this.player-1]["position"];
					this.setPosition(posPion);
					this.goToNextCase();
			})
			this.compteur = 0;
		}
	}

	getClickedItem(x, y){
		if (this.isClicked(x,y)) {
			return this;
		}
	}



	draw(context) {

		if (this.isSelected) {
			context.drawImage(
				this.images[this.taille]['select'],
				(((this.col - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posXPlayer,
				(((this.lig - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posYPlayer,
				this.images[this.taille]['select'].width,
				this.images[this.taille]['select'].height
			);
		}else {
			context.drawImage(
				this.images[this.taille]['unselect'],
				(((this.col - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posXPlayer,
				(((this.lig - 1) * this.map.TILE_HEIGHT) + this.map.TILE_HEIGHT) + this.posYPlayer,
				this.images[this.taille]['unselect'].width,
				this.images[this.taille]['unselect'].height
			);
		}

	}



	isClicked(x, y) {

		var largeur = 0;
		var hauteur = 0;
		if (this.taille === 'small') {
			largeur = 16;
			hauteur = 16;
		} else {
			largeur = 32;
			hauteur = 32;
		}
		var myTop = this.y;
		var myRgt = this.x + largeur;
		var myBot = this.y + hauteur;
		var myLft = this.x;

		var clicked = true;
		if (y < myTop || y > myBot || x < myLft || x > myRgt) {
			clicked = false;
		}
		return clicked;
	}

	setCol(col) {
		this.col = col;
	}

	setLig(lig) {
		this.lig = lig;
	}

	teleportToCase(col, lig) {
		//On change de position
		this.setCol(col);
		this.setLig(lig);
	}

	goToNextCase() {

		var posCol = this.parcours.casesPosition[this.posPion][0];
		var posLig = this.parcours.casesPosition[this.posPion][1];

		this.teleportToCase(posCol, posLig);

	}

	advanceBasedOnPawnValue() {

		if (this.posPion >= this.nbCases - 1) {
			alert('STOP ! Vous êtes arrivé au bout du parcours !');
		} else {

			for (let i = 0; i < this.faceCouranteDe; i++) {

				if (this.posPion < this.nbCases - 1) {

					this.posPion = this.posPion + 1;
					this.goToNextCase();
				}

				if (this.posPion == this.nbCases - 1) {

					this.goToNextCase();

					alert('Bravo ! Vous avez terminé le parcours !');

					break;
				}

			}
		}
	}

	positionnePionByPositionDansParcours(){

		if (this.posPion > 0) {

			var col = this.posCases[this.posPion][0];
			var lig = this.posCases[this.posPion][1];
			this.col = col;
			this.lig = lig;

		} else {
			this.col = 0;
			this.lig = 0;
		}

	}

	setPositionIntoAPI(pion){
		var parametres = 'http://localhost:8000/api/partie/' + idPartie;

		if(pion.position > pion.nbCases){
			var pos = pion.nbCases-1;
		}
		else{
			var pos = pion.position;
		}

		var pionstab = [{'player': pion.player, 'placement': pion.pos}];

		var jsonString = JSON.stringify({pions: pionstab});

		$.ajax({
	        type: "POST",
	        url: parametres,
	        data: "$data="+jsonString
    	});
	}

	updateXandYposition(){
		//On met à jour la position
		this.x = ToolBox.convertColToX(this.col, this.map.TILE_WIDTH) + this.posXPlayer;
		this.y = ToolBox.convertLigToY(this.lig, this.map.TILE_HEIGHT) + this.posYPlayer;
	}


	setTaille(taille){
		this.taille = taille;
	}

	getTaille(){
		return this.taille;
	}

	resizeSmaller(){
		this.setTaille('small');
	}

	resizeLarger(){
		this.setTaille('large');
	}

	loadImage(){

		var images = {
			'small' : {
				'select' : Graphics.newImage('sprites/small/pion_'+ this.couleur +'_selected_64.png'),
				'unselect' : Graphics.newImage('sprites/small/pion_'+ this.couleur +'_64.png')
			},
			'large' : {
				'select' : Graphics.newImage('sprites/large/pion_'+ this.couleur +'_selected_128.png'),
				'unselect' : Graphics.newImage('sprites/large/pion_'+ this.couleur +'_128.png')
			}
		}

		return images;
	}
}
