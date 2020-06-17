class InputHandler {
    constructor(canvas, mouse) {
        var clicked = false;

        var x = "";
        var y = "";

        canvas.addEventListener('mousedown', function (event) {
          console.log(document.getElementById('plateau-1'));
          console.log(event);
            var rect = {
              'left' : document.getElementById('plateau-1').offsetLeft,
              'top' : document.getElementById('plateau-1').offsetTop
            }
            clicked = true;
            x = event.x - rect.left;
            y = event.y - rect.top;
            mouse.setNewMouseState(x, y, clicked);
        })

        canvas.addEventListener('mouseup', function (event) {
            clicked = false;
            x = -1;
            y = -1;
            mouse.setNewMouseState(x, y, clicked);
        })
    }
}
