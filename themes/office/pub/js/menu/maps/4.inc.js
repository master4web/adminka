function createjsDOMenu() {

    
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
       addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
	/*addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar'));*/
 	addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
  addMenuItem(new menuItem("Комментарии к LIVE", "", script + '?admin=comments_live'));
	
	
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
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    setActivateMode("over");
  }
}
