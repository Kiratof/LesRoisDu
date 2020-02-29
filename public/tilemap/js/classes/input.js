class InputHandler {
    constructor(game) {

        //Récupération des éléments cliquable de notre jeu
        var dice = game.dice;
        var parcours = game.parcours;
        var map = game.map;
        var pion = game.pion;


        this.sourisX = 0;
        this.sourisY = 0;

        canvas.addEventListener('mousedown', function (event) {
            var rect = canvas.getBoundingClientRect();
            this.sourisX = event.pageX - rect.left;
            this.sourisY = event.pageY - rect.top;

            var col = Math.floor(this.sourisX / map.TILE_WIDTH);
            var lig = Math.floor(this.sourisY / map.TILE_HEIGHT);

            if (this.sourisX && this.sourisY) {
                var id = game.map.tileset.getIdTile(col, lig, game.map);

                switch (id) {
                    case 1:

                        if (pion.isClicked(this.sourisX, this.sourisY)) {
                            
                            alert('je suis un pion et je suis cliké');

                        } else {
                            for (var i = 0; i < parcours.cases.length; i++) {
                                if (parcours.cases[i].isClicked(col, lig)) {
                                    parcours.cases[i].displayDefi();
                                }
                            }

                        }


                        break;
                    case 2:
                        if (dice.isClicked(col, lig)) {

                            alert('Je suis le dé');
                        }
                        break;
                    default:
                        alert('Je sais même pas où t\'as cliqué fry');
                }

            }
        })

        canvas.addEventListener('mouseup', function (event) {
            this.sourisX = false;
            this.sourisY = false;
        })

    }


}
