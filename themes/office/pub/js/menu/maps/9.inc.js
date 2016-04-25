function createjsDOMenu() {
     
   
 
 absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
    addMenuItem(new menuItem("Новости Туризм", "", script+'?admin=news_turism'));
     addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
	}
 
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu9));
	setActivateMode("over");
  }
}