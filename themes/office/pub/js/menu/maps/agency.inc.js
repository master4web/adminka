function createjsDOMenu() {
     
   
 
 absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
    addMenuItem(new menuItem("Блог", "", script+'?admin=posts_nov'));
	addMenuItem(new menuItem("Комментарии к блогу", "", script+'?admin=comments_posts_nav'));
	}
 
    
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Блоги", absoluteMenu9));
	setActivateMode("over");
  }
}