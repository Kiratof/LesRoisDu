class Pion extends Element {
	constructor(col, lig, zIndex, parcours, player, position, nbCases) {
		super(col, lig, zIndex);
		this.setId('pion');
		this.posXPlayer = "";
		this.posYPlayer = "";

		this.nbCases = nbCases;
		this.parcours = parcours;
		this.compteur = 0;
		this.player = player;

		this.widthRatio = 1;
		this.heightRatio = 1;

		this.setPosition(position);

		this.posCases = parcours.casesPosition;
		this.positionnePionByPositionDansParcours();

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
		this.state = 'unselect';
		this.image = this.loadImage();
		this.setLargeurInitiale(this.image[this.state].width);
		this.setHauteurInitiale(this.image[this.state].height);
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

	getWidthRatio(){
		return this.widthRatio;
	}

	updateRatio(widthRatio, heightRatio){
		this.setWidthRatio(widthRatio);
		this.setHeightRatio(heightRatio);
	}

	setPosXPlayer(PosXPlayer){
		this.posXPlayer = PosXPlayer;
	}

	getPosXPlayer(){
		return this.posXPlayer;
	}

	setPosYPlayer(PosYPlayer){
		this.posYPlayer = PosYPlayer;
	}

	getPosYPlayer(){
		return this.posYPlayer;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

		//Positionne l'élément
		var x = ToolBox.convertColToX(this.col, map.TILE_WIDTH);
		var y = ToolBox.convertLigToY(this.lig, map.TILE_HEIGHT);
		this.setPositionXYInitiale(x, y);
		this.setPlayerRelativePosition(this.player, 1, 1);
	}

	setPositionXYInitiale(xInitiale, yInitiale){
		this.setXInitiale(xInitiale + this.posXPlayer);
		this.setYInitiale(yInitiale + this.posYPlayer);
	}

	setPositionXY(x, y){
		this.setX(x + this.posXPlayer);
		this.setY(y + this.posYPlayer);
	}

	updateOnResizing(widthRatio, heightRatio){
		this.updateRatio(widthRatio, heightRatio);
		this.updateSize(this, widthRatio, heightRatio);
		this.setPlayerRelativePosition(this.player, widthRatio, heightRatio);
		this.updateXandYposition(this, widthRatio, heightRatio);
	}

	updateXandYposition(){
		var x = ToolBox.convertColToX(this.col, this.map.TILE_WIDTH) * this.widthRatio;
		var y = ToolBox.convertLigToY(this.lig, this.map.TILE_HEIGHT) * this.heightRatio;
		this.setPositionXY(x, y);
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
	   return ((1 * tailleOctogone) - 2 * taillePion) / 4;
	}

	troisiemeQuart(tailleOctogone, taillePion) {
   	return ((3 * tailleOctogone) - 2 * taillePion) / 4;
	}

	setPlayerRelativePosition(player, widthRatio, heightRatio){
		switch (player) {
			case 1:
				this.setPosXPlayer(this.premierQuart(this.map.TILE_WIDTH * widthRatio, this.largeur));
				this.setPosYPlayer(this.premierQuart(this.map.TILE_HEIGHT * heightRatio, this.hauteur));
				break;

			case 2:
				this.setPosXPlayer(this.troisiemeQuart(this.map.TILE_WIDTH * widthRatio, this.largeur));
				this.setPosYPlayer(this.premierQuart(this.map.TILE_HEIGHT * heightRatio, this.hauteur));
				break;


			case 3:
				this.setPosXPlayer(this.premierQuart(this.map.TILE_WIDTH * widthRatio, this.largeur));
				this.setPosYPlayer(this.troisiemeQuart(this.map.TILE_HEIGHT * heightRatio, this.hauteur));
				break;

			case 4:
				this.setPosXPlayer(this.troisiemeQuart(this.map.TILE_WIDTH * widthRatio, this.largeur));
				this.setPosYPlayer(this.troisiemeQuart(this.map.TILE_HEIGHT * heightRatio, this.hauteur));
				break;

			default:
				alert('Il ne peut exister de joueur ' + player + '.');
		}
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

	draw(context) {

		if (this.isSelected) {
			context.drawImage(
				this.image['select'],
				this.x,
				this.y,
				this.largeur,
				this.hauteur
			);
		}else {
			context.drawImage(
				this.image['unselect'],
				this.x,
				this.y,
				this.largeur,
				this.hauteur
			);
		}

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

	loadImage(){

		var images = {
			'select' : Graphics.newImage('sprites/large/pion_'+ this.couleur +'_selected_128.png'),
			'unselect' : Graphics.newImage('sprites/large/pion_'+ this.couleur +'_128.png')
		}

		return images;
	}
}
