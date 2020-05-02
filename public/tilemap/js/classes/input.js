class InputHandler {
    constructor(mouse) {

        canvas.addEventListener('mousedown', function (event) {
            var rect = canvas.getBoundingClientRect();

            mouse.setNewMouseState(event.x - rect.left, event.y - rect.top)

        })

        canvas.addEventListener('mouseup', function (event) {
            mouse.setNewMouseState();
        })
    }
}
