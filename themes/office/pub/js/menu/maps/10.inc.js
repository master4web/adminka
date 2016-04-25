function createjsDOMenu() {
     
   
 
 absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
    addMenuItem(new menuItem("Новости Соцпакет", "", script+'?admin=news_social'));
	}
 
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu9));
	setActivateMode("over");
  }
}