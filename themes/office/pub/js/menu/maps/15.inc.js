function createjsDOMenu() {
    
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Статьи газеты", "", script+'?admin=number_publ'));
    addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
	}
   
 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu1));
     setActivateMode("over");
	}
}