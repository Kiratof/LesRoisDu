class Mouse{
	constructor(){

		this.x = "";
    this.y = "";

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

	toggleSwitch() {

		if (this.isCliked) {
			this.isCliked = false
		}else {
			this.isCliked = true;
		}

	}

  setIsCliked(bool)
  {
		this.isCliked = bool;
  }

  isClicked(){
    return this.isCliked;
  }

  setNewMouseState(x, y, state){
    this.setIsCliked(state);
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
