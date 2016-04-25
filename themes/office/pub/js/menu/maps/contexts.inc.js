function createjsDOMenu() {
    
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
   addMenuItem(new menuItem("Контекстная реклама", "", script + '?admin=contexts'));
	}
   
 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Реклама", absoluteMenu1));
     setActivateMode("over");
	}
}