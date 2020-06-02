class ResizeHandler {
    constructor(scene) {

        this.oldScreen = undefined;
        window.addEventListener('resize', function () {

              if (window.matchMedia("(max-width: 737px)").matches) {
                if (this.oldScreen !== 'petit') {
                  scene.resizePlateauSmaller();
                  this.oldScreen = 'petit';
                }

              } else{
                if (this.oldScreen !== 'grand') {
                  scene.resizePlateauLarger();
                  this.oldScreen = 'grand';
                }
              }

        })
    }

}
