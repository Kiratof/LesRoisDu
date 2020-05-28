class ResizeHandler {
    constructor(scene) {

        this.oldScreen = undefined;
        window.addEventListener('resize', function () {

              if (window.matchMedia("(max-width: 737px)").matches) {
                if (this.oldScreen !== 'petit') {
                  console.log('Je suis PETIT');
                  scene.resizePlateauSmaller();
                  this.oldScreen = 'petit';
                }

              } else{
                if (this.oldScreen !== 'grand') {
                  console.log('Je suis GRAND');
                  scene.resizePlateauLarger();
                  this.oldScreen = 'grand';
                }
              }

        })
    }

}
