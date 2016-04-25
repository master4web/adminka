function createjsDOMenu() {
     
   
 
 absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
    addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Баннеры", "",script + '?admin=adv_items'));
	addMenuItem(new menuItem("Информеры", "", script + '?admin=informers'));    
	addMenuItem(new menuItem("Контекстная реклама", "", script + '?admin=contexts'));
	addMenuItem(new menuItem("Seoстатьи", "", script + '?admin=seopubls'));
	addMenuItem(new menuItem("Seoконторы", "", script + '?admin=seocompany'));
	}
 
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Реклама", absoluteMenu9));
	setActivateMode("over");
  }
}