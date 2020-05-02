class Mouse{
	constructor(){

		this.x = undefined;
    this.y = undefined;

    this.isCliked = false;
	}

  setMouseXPosition(mouseXPosition){
    this.x = mouseXPosition;
  }
  setMouseYPosition(mouseYPosition){
    this.y = mouseYPosition;
  }

  getXPosition(){
    return this.x;
  }
  getYPosition(){
    return this.y;
  }

  setIsCliked()
  {
    if (this.isCliked) {
      this.isCliked = false
    }else {
      this.isCliked = true;
    }
  }

  isClicked(){
    return this.isCliked;
  }

  setNewMouseState(x, y){
    this.setIsCliked();
    this.setMouseXPosition(x);
    this.setMouseYPosition(y);
  }

  getState(){
    var mouseState = {
      "X" : this.getXPosition(),
      "Y" : this.getYPosition(),
      "isCliked": this.isClicked()
    };

    return mouseState;
  }

}
