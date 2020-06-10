class Element{
	constructor(col, lig, zIndex){

		this.id = "";

		this.x = "";
		this.y = "";
		this.xInitiale = "";
		this.yInitiale = "";
		this.z = zIndex;
		this.col = col;
		this.lig = lig;
		this.map = "";

		this.image = "";
		this.largeur = "";
		this.hauteur = "";
		this.largeurInitiale = "";
		this.hauteurInitiale = "";
	}

	setId(id){
		this.id = id;
	}

	getId(){
		return this.id;
	}

	setImage(image){
		this.image = image;
	}

	getImage(){
		return this.image;
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

	setXInitiale(x){
		this.xInitiale = x;
	}

	getXInitiale(){
		return this.xInitiale;
	}

	setYInitiale(y){
		this.yInitiale = y;
	}

	getYInitiale(){
		return this.yInitiale;
	}

	setPositionXY(x, y){
		this.setX(x);
		this.setY(y);
	}

	setPositionXYInitiale(xInitiale, yInitiale){
		this.setXInitiale(xInitiale);
		this.setYInitiale(yInitiale);
	}

	setZ(z){
		this.z = z;
	}

	getZ(){
		return this.z;
	}

	setCol(col){
		this.col = col;
	}

	getCol(){
		return this.col;
	}
	setLig(lig){
		this.lig = lig;
	}

	getLig(){
		return this.lig;
	}

	setMap(map){
		this.map = map;
	}

	getMap(){
		return this.map;
	}

	connectMap(map){
		//Set la Map
		this.setMap(map);

		//Positionne l'élément
		var x = ToolBox.convertColToX(this.col, map.TILE_WIDTH);
		var y = ToolBox.convertLigToY(this.lig, map.TILE_HEIGHT);
		this.setPositionXYInitiale(x, y);
	}

	updateOnResizing(widthRatio, heightRatio){
		this.updateRelativePosition(this, widthRatio, heightRatio);
		this.updateSize(this, widthRatio, heightRatio);
	}

	updateSize(element, widthRatio, heightRatio){
		var largeur = element.getLargeurInitiale() * widthRatio;
		var hauteur =  element.getHauteurInitiale() * heightRatio;

		element.setLargeur(largeur);
		element.setHauteur(hauteur);
	}

	updateRelativePosition(element, widthRatio, heightRatio){
		var x = element.getXInitiale() * widthRatio;
		var y = element.getYInitiale() * heightRatio;

		element.setPositionXY(x, y);
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

	getClickedItem(x, y){
		if (this.isClicked(x,y)) {
			return this;
		}
	}

	update(){

	}

	draw(context){
	}

	loadImage(){

	}
}
