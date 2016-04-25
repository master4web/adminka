function createjsDOMenu() {

  
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
  		addMenuItem(new menuItem("Новости", "", script + '?admin=charity_news'));
      	addMenuItem(new menuItem("Люди", "", script + '?admin=charity'));
  }

 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
      addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
      setActivateMode("over");
  }

}