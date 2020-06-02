class Graphics {

    static newImage(nom) {

      var sourceImage = assetsBaseDir + nom;
      // Chargement de l'image dans l'attribut image
  		var image = new Image();
  		image.onload = function () {

  			if (!this.complete)
  				throw "Erreur de chargement du sprite nommé \"" + sourceImage + "\".";
  		}

  		image.src = sourceImage;
      return image;
    }


}
