class ResizeHandler {
    constructor(scene) {

      var widthRatio = 1;
      var heightRatio = 1;
      var newWidth = scene.map.getLargeur();
      var newHeight = scene.map.getHauteur();

      window.addEventListener('resize', function () {
        scene.setCanvasSize();
        widthRatio = scene.canvas.width / newWidth;
        heightRatio = scene.canvas.height / newHeight;
        scene.updateRatio(widthRatio, heightRatio);
    })

  }
}
