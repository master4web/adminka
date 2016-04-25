function createjsDOMenu() {
     
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  
  
     
   absoluteMenu5 = new jsDOMenu(140, "absolute");
	with (absoluteMenu5) {
		addMenuItem(new menuItem("Регистрация он-лайн", "", script + '?admin=online'));
		addMenuItem(new menuItem("Вопросы и ответы", "", script + '?admin=onlinequest'));
		addMenuItem(new menuItem("-"));
		addMenuItem(new menuItem("Пресс-конференции", "", script + '?admin=presscenter'));
	
  	}
  
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
  	addMenuBarItem(new menuBarItem("Пресс-центр", absoluteMenu5));
	setActivateMode("over");
  }
}