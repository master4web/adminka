function createjsDOMenu() {
     
      
  absoluteMenu1 = new jsDOMenu(140, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar_lite'));
    addMenuItem(new menuItem("Посты", "", script+'?admin=posts_admin'));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
    addMenuItem(new menuItem("Регистратор ошибок", "",  script + '?admin=error'));
		
	/*addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Афоризмы", "", script + '?admin=aforms'));
	addMenuItem(new menuItem("Анекдоты", "", script + '?admin=anekdots'));*/
	
    }
  
    
 /* absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Анонсы RSS", "item1", ""));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Интерфакс", "", server + 'interfax.php'));
  }*/
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu1));
    setActivateMode("over");
  }
}