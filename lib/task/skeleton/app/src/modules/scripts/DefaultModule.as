// ActionScript file
public function setNotFound():void
{
	if (this.currentState != "notfound")
	{
		this.currentState = "notfound";
	}
}
public function setWelcome():void
{
	if (this.currentState != "")
	{
		this.currentState = "";
	}
}