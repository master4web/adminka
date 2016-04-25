function createjsDOMenu() {
     
   
 
 absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
    addMenuItem(new menuItem("Новости", "", script + '?admin=ar'));
	addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
	}
 
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Новости", absoluteMenu9));
	setActivateMode("over");
  }
}