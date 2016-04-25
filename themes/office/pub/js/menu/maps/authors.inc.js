function createjsDOMenu() {
    
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Блоги", "", script+'?admin=blogs'));
	}
   
 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Проекты", absoluteMenu1));
     setActivateMode("over");
	}
}