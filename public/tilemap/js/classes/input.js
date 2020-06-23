class InputHandler {
    constructor(canvas, mouse) {
        var clicked = false;
        var rect = ""
        var x = "";
        var y = "";

        canvas.addEventListener('mousedown', function (event) {
            rect = canvas.getBoundingClientRect();
            clicked = true;
            x = event.clientX - rect.left;
            y = event.clientY - rect.top;
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
