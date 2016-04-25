function createjsDOMenu() {
 
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Статьи", "", script + '?admin=publ'));
    addMenuItem(new menuItem("Новости", "", script + '?admin=news'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Анонсы", "", script + '?admin=exportanons'));
	}
  
     
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    setActivateMode("over");
  }
  
  
}