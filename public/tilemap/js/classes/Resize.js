class ResizeHandler {
  constructor(scene) {

    var widthRatio = "";
    var heightRatio = "";
    var widthInitiale = scene.map.getLargeur();
    var heightInitiale = scene.map.getHauteur();

    window.addEventListener('resize', function () {
      scene.setCanvasSize('tabs__content');
      widthRatio = scene.canvas.width / widthInitiale;
      heightRatio = scene.canvas.height / heightInitiale;
      scene.updateRatio(widthRatio, heightRatio);
    })

  }
}
